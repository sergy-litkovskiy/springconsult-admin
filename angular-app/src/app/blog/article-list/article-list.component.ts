import {Component, OnInit, Output, ViewChild, EventEmitter, Input} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {Subscription} from 'rxjs/Subscription';
import {isNullOrUndefined} from "util";
import {ColumnApi, GridApi, GridOptions} from "ag-grid";

import ArticleListDataMock from "./article-list-mock";


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

export class AppArticleListComponent implements OnInit {
    // articleItemList: ArticleItem[];
    rowData: ArticleItem[];
    rowData2: any[];
    actionButtonClassName: string;
    tempArticleList: ArticleItem[] = [];

    private articleListSubscription: Subscription;
    private gridOptions: GridOptions;
    // public rowData: any[];
    public columnDefs: any[];
    public rowCount: string;

    private api: GridApi;
    private columnApi: ColumnApi;

    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(private articleService: ArticleService,
                private router: Router,
                private route: ActivatedRoute) {
        this.gridOptions = <GridOptions>{};
        // this.createRowData();
        // this.createColumnDefs();
    }

    private onReady(params) {
console.log('onReady', params);
        this.api = params.api;
        this.columnApi = params.columnApi;
    }

    ngOnInit() {
        this.rowData = this.articleService.getArticleItemList();

        if (!this.rowData.length) {
            this.articleListSubscription = this.articleService.getArticleItemListFromServer()
                .subscribe(
                    (articleList: ArticleItem[]) => {
                        this.rowData = articleList;
                        this.tempArticleList = [...articleList];
console.log('this.articleListSubscription - ngOnInit', this.rowData);
                        this.createRowData();
                        this.createColumnDefs();
                    },
                    (error) => {
                        // this.showErrorPopup(error);
                    }
                );
        }
console.log('this.rowData', this.rowData);
        this.actionButtonClassName = "btn btn-";
    }

    // onNewMenuItem() {
    //     this.router.navigate(['menu-new'], {relativeTo: this.route});
    // }

    getButtonClassForStatusOn(row: any): string {
        let currentStatus = +row.status == 1 ? "success disabled" : "default";
        return this.actionButtonClassName + currentStatus;
    }

    getButtonClassForStatusOff(row: any): string {
        let currentStatus = +row.status == 1 ? "default" : "success disabled";
        return this.actionButtonClassName + currentStatus;
    }

    onStatusChangeClick(row: any): void {
        row.status = !row.status;

        this.articleService.updateArticle(row)
            .subscribe(
                (response: any) => console.log('response', response),
                (error) => {
                    // this.showErrorPopup(error);

                    row.status = !row.status;
                }
            );
    }

    onEditClick(articleItem: ArticleItem): void {
        this.router.navigate(['/article-edit', articleItem.id], {relativeTo: this.route});
    }

    onDeleteClick(articleItem: ArticleItem): void {
        this.popup.open(NguiMessagePopupComponent, {
            classNames: 'small',
            title: articleItem.title,
            message: 'Are you sure you want to DELETE the article?',
            buttons: {
                OK: () => {
                    this.popup.close();

                    this.articleService.deleteArticle(articleItem)
                        .subscribe(
                            (response: any) => {
                                this.rowData = this.rowData.filter(obj => obj !== articleItem);
                            },
                            (error) => {
                                // this.showErrorPopup(error);
                            }
                        );
                },
                CANCEL: () => {
                    this.popup.close();
                }
            }
        });
    }

    searchArticle(event: any) {
        const val = event.target.value.toLowerCase();

        // filter our data
        this.rowData = this.tempArticleList.filter(function (articleItem) {
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
        // this.rowData = [];
console.log('createRowData', this.rowData);
        for (let articleItem in this.rowData) {

            this.rowData2.push({
                date: articleItem.date,
                title: articleItem.title,
                slug: articleItem.slug,
                metaDescription: articleItem.metaDescription,
                metaKeywords: articleItem.metaKeywords,
                assignedMenuList: articleItem.assignedMenuList
            });
        }

        // for (let i = 0; i < 15; i++) {
        //     const countryData = ArticleListDataMock.countries[i % ArticleListDataMock.countries.length];
        //
        //     this.rowData.push({
        //         date: 'createdAt',
        //         title: 'some title-'+i,
        //         slug: 'slug-'+i,
        //         metaDescription: 'metaDescription-'+i,
        //         metaKeywords: 'metaKeywords-'+i,
        //         assignedMenuList: []
        //         // ,
        //         // country: countryData.country,
        //     });
        // }
    }

    private createColumnDefs() {
        this.columnDefs = [
            {
                headerName: "Date",
                field: "date",
                width: 100,
                pinned: true
                // ,
                // cellRenderer: function (params) {
                //     return pad(params.value.getDate(), 2) + '/' +
                //         pad(params.value.getMonth() + 1, 2) + '/' +
                //         params.value.getFullYear();
                // },
            },
            {
                headerName: "Title",
                field: "title",
                width: 150,
                // pinned: true
            },
            {
                headerName: "slug",
                field: "slug",
                width: 135,
                // pinned: true
            },
            {
                headerName: "MetaDescription",
                field: "metaDescription",
                // width: 150,
                // an example of using a non-React cell renderer
                cellRenderer: countryCellRenderer,
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
                headerName: "MetaKeywords",
                field: "metaKeywords",
                // width: 110,
                filter: 'metaKeywords',
                // pinned: true
            },
            {
                headerName: 'AssignedMenuList',
                field: "assignedMenuList",
                // width: 250,
                // pinned: true,
                cellRenderer: function (params) {
                    return 'menu item';
                    // return pad(params.value.getDate(), 2) + '/' +
                    //     pad(params.value.getMonth() + 1, 2) + '/' +
                    //     params.value.getFullYear();
                }
            }
        ];
    }
}

function countryCellRenderer(params) {
    return "- " + params.value;
}
