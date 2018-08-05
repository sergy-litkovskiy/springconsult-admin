import {Component, OnInit} from '@angular/core';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {Subscription} from 'rxjs/Subscription';
import {ColumnApi, GridApi, GridOptions} from "ag-grid";
import {ArticleListActionToolsComponent} from "./article-list.action-tools.component";
import {OverlayInfoComponent} from "../../common/overlay.info.component";
import {MatDialog} from "@angular/material";

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

    readonly frameworkComponents;

    constructor(private articleService: ArticleService, private dialog: MatDialog) {
        this.frameworkComponents = {
            articleListActionToolsComponent: ArticleListActionToolsComponent
        };

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
            toolPanelSuppressValues: true,
            frameworkComponents: this.frameworkComponents
        };

        this.articleService.articleItemDeleted.subscribe(
            (articleItem: ArticleItem) => {
                this.articleItemList = this.articleItemList.filter(obj => obj !== articleItem);
                this.renderGrid();
            }
        );

        this.articleService.errorMessage.subscribe(
            (error: string) => {
console.log('this.articleService.errorMessage.subscribe!!!!!!!');
                this.showErrorPopup(error);
            }
        );
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

                        this.renderGrid();
                    },
                    (error) => {
console.log('---ngOnInit: error');
                        this.showErrorPopup(error);
                    }
                );
        } else {
            this.renderGrid();
        }
    }

    private renderGrid()
    {
        this.createRowData();
        this.createColumnDefs();
    }

    private showErrorPopup(error: string) {
        this.dialog.open(OverlayInfoComponent, {
            width: '400px',
            data: { message: error }
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
        const validValueTmpl = `<span class="label label-success"><i class="glyphicon glyphicon-thumbs-up"></i></span>`;
        const emptyValueTmpl = `<span class="label label-success"><i class="glyphicon glyphicon-thumbs-down"></i></span>`;

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
                width: 300
            },
            {
                headerName: "Slug",
                field: "slug",
                width: 200,
                cellRenderer: function (params) {
                    return params.value;
                }
            },
            {
                headerName: "Meta Description",
                field: "metaDescription",
                cellRenderer: function (params) {
                    return params.value.length ? validValueTmpl : emptyValueTmpl;
                }
            },
            {
                headerName: 'Meta Keywords',
                field: 'metaKeywords',
                cellRenderer: function (params) {
                    return params.value.length ? validValueTmpl : emptyValueTmpl;
                }
            },
            {
                headerName: 'Assigned to',
                field: 'assignedMenuList',
                width: 150,
                pinned: 'right',
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
                width: 165,
                pinned: 'right',
                suppressMenu: true,
                suppressSorting: true,
                cellRenderer: 'articleListActionToolsComponent'
            }
        ];
    }
}
