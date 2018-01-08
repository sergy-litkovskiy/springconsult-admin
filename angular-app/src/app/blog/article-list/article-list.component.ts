import {Component, OnInit, Output, ViewChild} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {Subscription} from 'rxjs/Subscription';
import {ColumnApi, GridApi, GridOptions} from "ag-grid";

@Component({
    selector: 'article-list',
    templateUrl: './article-list.component.html'
})

export class ArticleListComponent implements OnInit {
    articleItemList: ArticleItem[];
    rowData: any[];
    tempArticleList: ArticleItem[] = [];

    private articleListSubscription: Subscription;
    private gridOptions: GridOptions;
    public columnDefs: any[];

    private api: GridApi;
    private columnApi: ColumnApi;

    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(private articleService: ArticleService,
                private router: Router,
                private route: ActivatedRoute) {
        this.gridOptions = <GridOptions> {
            localeText: {
                noRowsToShow: ' '//hook to hide message
            },
            rowHeight: 35,
            pagination: true,
            paginationAutoPageSize: true,
            enableColResize: true,
            enableSorting: true,
            enableFilter: true,
            suppressRowClickSelection: true,
            toolPanelSuppressValues: true
        };
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
    }

    // onNewMenuItem() {
    //     this.router.navigate(['menu-new'], {relativeTo: this.route});
    // }

    onStatusChangeClick(articleItem: ArticleItem): void {
console.log('onStatusChangeClick', articleItem);
        articleItem.status = !articleItem.status;

        this.articleService.updateArticle(articleItem)
            .subscribe(
                (response: any) => console.log('response', response),
                (error) => {
                    this.showErrorPopup(error);

                    articleItem.status = !articleItem.status;
                }
            );
    }

    onEditClick(articleItem: ArticleItem): void {
console.log('onEditClick', articleItem);
        this.router.navigate(['/article-edit', articleItem.id], {relativeTo: this.route});
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

                    this.articleService.deleteArticle(articleItem)
                        .subscribe(
                            (response) => {
                                this.articleItemList = this.articleItemList.filter(obj => obj !== articleItem);
                            },
                            (error) => {
                                this.showErrorPopup(error);
                            }
                        );
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

            switch (actionType) {
                case "edit":
                    return this.onEditClick(data);
                case "remove":
                    return this.onDeleteClick(data);
                case "status":
                    return this.onStatusChangeClick(data);
            }
        }
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
                id: articleItem.id,
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
                tooltipField: 'HERE!!!!!',
                headerTooltip: 'Header HERE!!!!!',
                cellRenderer: function (params) {
                    let menuListSize = params.value.length;
                    let assignedItems = '';

                    for (let i = 0; i < menuListSize; i++) {
                        assignedItems = assignedItems + params.value[i].title;

                        if (i < (menuListSize - 1)) {
                            assignedItems = assignedItems + ' | ';
                        }
                    }

                    return '<span title="' + assignedItems + '">' + assignedItems + '</span>';
                }
            },
            {
                headerName: 'Actions',
                field: "actions",
                width: 160,
                pinned: 'right',
                suppressMenu: true,
                suppressSorting: true,
                cellRenderer: function (params) {
                    let activeStatusClass = "btn-default";
                    let inactiveStatusClass = "btn-default";

                    if (+params.value.status == 1) {
                        activeStatusClass = "btn-success disabled";
                    } else {
                        inactiveStatusClass = "btn-success disabled";
                    }

                    return `
                        <div class="btn-group">
                            <button type="button"
                                    data-action-type="edit"
                                    class="btn btn-sm btn-warning">
                                <i class="glyphicon glyphicon-pencil" data-action-type="edit"></i>
                            </button>
                            <button type="button"
                                    data-action-type="remove"
                                    class="btn btn-sm btn-danger">
                                <i class="glyphicon glyphicon-remove" data-action-type="remove"></i>
                            </button>
                            <button type="button"
                                    data-action-type="status"
                                    class="btn btn-sm ` + activeStatusClass + `">
                                <i class="glyphicon glyphicon-eye-open" data-action-type="status"></i>
                            </button>
                            <button type="button"
                                    data-action-type="status"
                                    class="btn btn-sm ` + inactiveStatusClass + `">
                                <i class="glyphicon glyphicon-eye-close" data-action-type="status"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ];
    }
}
