import {Component, OnInit, Output, ViewChild} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {Subscription} from 'rxjs/Subscription';
import {ColumnApi, GridApi, GridOptions} from "ag-grid";

@Component({
    selector: 'article-list',
    // styleUrls: [
        // "../../../../node_modules/ag-grid/dist/styles/ag-grid.css",
        // "../../../../node_modules/ag-grid/dist/styles/ag-theme-bootstrap.css",
        // "./article-list.component.css"
    // ],
    styles: [
        '.ag-theme-bootstrap .ag-body, .ag-theme-bootstrap .ag-header {padding: 10px !important;}',
        '.ag-theme-bootstrap .ag-header {background: red !important;}'
    ],
    templateUrl: './article-list.component.html'
})

export class ArticleListComponent implements OnInit {
    articleItemList: ArticleItem[];
    rowData: any[];
    actionButtonClassName: string;
    tempArticleList: ArticleItem[] = [];

    private articleListSubscription: Subscription;
    private gridOptions: GridOptions;
    public columnDefs: any[];
    public rowCount: string;

    private api: GridApi;
    private columnApi: ColumnApi;

    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(private articleService: ArticleService,
                private router: Router,
                private route: ActivatedRoute) {
        this.gridOptions = <GridOptions>{};
        this.gridOptions.rowHeight = 35;
    }

    private onReady(params) {
        this.api = params.api;
        this.columnApi = params.columnApi;
    }

    ngOnInit() {
        this.articleItemList = this.articleService.getArticleItemList();

        if (!this.articleItemList.length) {
            this.articleListSubscription = this.articleService.getArticleItemListFromServer()
                .subscribe(
                    (articleList: ArticleItem[]) => {
                        this.articleItemList = articleList;
                        this.tempArticleList = [...articleList];

                        this.createRowData();
                        this.createColumnDefs();
                    },
                    (error) => {
                       this.showErrorPopup(error);
                    }
                );
        } else {
            this.createRowData();
            this.createColumnDefs();
        }

        this.actionButtonClassName = "btn btn-";
    }

    // onNewMenuItem() {
    //     this.router.navigate(['menu-new'], {relativeTo: this.route});
    // }

    getButtonClassForStatusOn(row: any): string {
console.log('getButtonClassForStatusOn', row);
        let currentStatus = +row.status == 1 ? "success disabled" : "default";
        return this.actionButtonClassName + currentStatus;
    }

    getButtonClassForStatusOff(row: any): string {
console.log('getButtonClassForStatusOff', row);
        let currentStatus = +row.status == 1 ? "default" : "success disabled";
        return this.actionButtonClassName + currentStatus;
    }

    onStatusChangeClick(articleItem: ArticleItem): void {
console.log('onStatusChangeClick', articleItem);
        articleItem.status = !articleItem.status;

        // this.articleService.updateArticle(articleItem)
        //     .subscribe(
        //         (response: any) => console.log('response', response),
        //         (error) => {
        //             this.showErrorPopup(error);
        //
        //             articleItem.status = !articleItem.status;
        //         }
        //     );
    }

    onEditClick(articleItem: ArticleItem): void {
console.log('onEditClick', articleItem);
        // this.router.navigate(['/article-edit', articleItem.id], {relativeTo: this.route});
    }

    onDeleteClick(articleItem: ArticleItem): void {
console.log('onDeleteClick', articleItem);
        this.popup.open(NguiMessagePopupComponent, {
            classNames: 'small',
            title: articleItem.title,
            message: 'Are you sure you want to DELETE the article?',
            buttons: {
                OK: () => {
                    this.popup.close();

                    // this.articleService.deleteArticle(articleItem)
                    //     .subscribe(
                    //         (response) => {
                    //             this.articleItemList = this.articleItemList.filter(obj => obj !== articleItem);
                    //         },
                    //         (error) => {
                    //             this.showErrorPopup(error);
                    //         }
                    //     );
                },
                CANCEL: () => {
                    this.popup.close();
                }
            }
        });
    }

    onRowClicked(e) {
        if (e.event.target !== undefined) {
            let data = e.data;
            let actionType = e.event.target.getAttribute('data-action-type');

            switch(actionType) {
                case "edit":
                    return this.onEditClick(data);
                case "remove":
                    return this.onDeleteClick(data);
                case "status":
                    return this.onStatusChangeClick(data);
            }
        }
    }

    searchArticle(event: any) {
        const val = event.target.value.toLowerCase();

        // filter our data
        this.articleItemList = this.tempArticleList.filter(function (articleItem) {
            return articleItem.title.toLowerCase().indexOf(val) !== -1 || !val;
        });

        // Whenever the filter changes, always go back to the first page
        // this.articleListTable.offset = 0;
    }

    showErrorPopup(error: string) {
        this.popup.open(NguiMessagePopupComponent, {
            classNames: 'small',
            title: 'ERROR',
            message: error,
            buttons: {
                CLOSE: () => {
                    this.popup.close();
                }
            }
        });
    }

    ngOnDestroy() {
        console.log('article LIST - ON DESTROY');
        if (this.articleListSubscription != undefined) {
            this.articleListSubscription.unsubscribe();
        }
    }

    private createRowData() {
        this.rowData = [];

        for (let index in this.articleItemList) {
            let articleItem = this.articleItemList[index];

            this.rowData.push({
                date: articleItem.date,
                title: articleItem.title,
                slug: articleItem.slug,
                metaDescription: articleItem.metaDescription,
                metaKeywords: articleItem.metaKeywords,
                assignedMenuList: articleItem.assignedMenuList,
                actions: articleItem
            });
        }
    }

    private createColumnDefs() {
        this.columnDefs = [
            {
                headerName: "Date",
                field: "date",
                width: 100,
                pinned: 'left'
            },
            {
                headerName: "Title",
                field: "title",
                width: 250
            },
            {
                headerName: "Slug",
                field: "slug",
                width: 150,
                cellRenderer: function (params) {
                    return params.value;
                }
            },
            {
                headerName: "Meta Description",
                field: "metaDescription",
                cellRenderer: function (params) {
                    return params.value;
                },
                // pinned: true
                // ,
                // filter: 'set',
                // filterParams: {
                //     cellRenderer: countryCellRenderer,
                //     cellHeight: 20
                // },
                // cellEditor: 'agRichSelect',
                // cellEditorParams: {
                //     values: ["Argentina", "Brazil", "Colombia", "France", "Germany", "Greece", "Iceland", "Ireland",
                //         "Italy", "Malta", "Portugal", "Norway", "Peru", "Spain", "Sweden", "United Kingdom",
                //         "Uruguay", "Venezuela", "Belgium", "Luxembourg"],
                //     cellRenderer: countryCellRenderer,
                // },
                // editable: true
            },
            {
                headerName: 'Meta Keywords',
                field: 'metaKeywords',
                filter: 'metaKeywords'
            },
            {
                headerName: 'Assigned to',
                field: 'assignedMenuList',
                cellRenderer: function (params) {
                    let menuListSize = params.value.length;

                    for (let i = 0; i < menuListSize; i++) {
                        return params.value[i].title + ' | ';
                    }
                }
            },
            {
                headerName: 'Actions',
                field: "actions",
                width: 180,
                pinned: 'right',
                suppressMenu: true,
                suppressSorting: true,
                template:
                    `
                        <div class="btn-group">
                            <button type="button"
                                    data-action-type="edit"
                                    class="btn btn-sm btn-warning"
                                    (click)="onEditClick(params.value)">
                                <i class="glyphicon glyphicon-pencil" data-action-type="edit"></i>
                            </button>
                            <button type="button"
                                    data-action-type="remove"
                                    class="btn btn-sm btn-danger"
                                    (click)="onDeleteClick(params.value)">
                                <i class="glyphicon glyphicon-remove" data-action-type="remove"></i>
                            </button>
                            <button type="button"
                                    data-action-type="status"
                                    [ngClass]="getButtonClassForStatusOn(params.value)"
                                    (click)="onStatusChangeClick(params.value)">
                                <i class="glyphicon glyphicon-eye-open" data-action-type="status"></i>
                            </button>
                            <button type="button"
                                    data-action-type="status"
                                    [ngClass]="getButtonClassForStatusOff(params.value)"
                                    (click)="onStatusChangeClick(params.value)">
                                <i class="glyphicon glyphicon-eye-close" data-action-type="status"></i>
                            </button>
                        </div>
                    `
            }
        ];
    }
}
