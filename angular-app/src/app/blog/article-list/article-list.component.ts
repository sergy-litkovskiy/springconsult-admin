import {Component, OnInit, Output, ViewChild} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {Subscription} from 'rxjs/Subscription';
import {ColumnApi, GridApi, GridOptions} from "ag-grid";
import {ArticleListActionToolRendererComponent} from "./article-list.action-tool-renderer.component";

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

    private frameworkComponents;

    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(private articleService: ArticleService,
                private router: Router,
                private route: ActivatedRoute) {
        this.frameworkComponents = {
            articleListActionToolRenderer: ArticleListActionToolRendererComponent
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

//     onStatusChangeClick(articleItem: ArticleItem): void {
// console.log('onStatusChangeClick', articleItem);
//         articleItem.status = !articleItem.status;
//
//         this.articleService.updateArticle(articleItem)
//             .subscribe(
//                 (response: any) => console.log('response', response),
//                 (error) => {
//                     this.showErrorPopup(error);
//
//                     articleItem.status = !articleItem.status;
//                 }
//             );
//     }

//     onEditClick(articleItem: ArticleItem): void {
// console.log('onEditClick', articleItem);
//         this.router.navigate(['/article-edit', articleItem.id], {relativeTo: this.route});
//     }

    onDeleteClick(articleItem: any): void {
console.log('onDeleteClick - main component', articleItem);

        // this.popup.open(NguiMessagePopupComponent, {
        //     classNames: 'small',
        //     title: articleItem.title,
        //     message: 'Are you sure you want to DELETE the article?',
        //     buttons: {
        //         OK: () => {
        //             this.popup.close();
        //
        //             this.articleService.deleteArticle(articleItem)
        //                 .subscribe(
        //                     (response) => {
        //                         this.articleItemList = this.articleItemList.filter(obj => obj !== articleItem);
        //                     },
        //                     (error) => {
        //                         this.showErrorPopup(error);
        //                     }
        //                 );
        //         },
        //         CANCEL: () => {
        //             this.popup.close();
        //         }
        //     }
        // });
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
                }
            },
            {
                headerName: 'Meta Keywords',
                field: 'metaKeywords',
                filter: 'metaKeywords'
            },
            {
                headerName: 'Assigned to',
                field: 'assignedMenuList',
                width: 120,
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
                cellRenderer: 'articleListActionToolRenderer'
            }
        ];
    }
}
