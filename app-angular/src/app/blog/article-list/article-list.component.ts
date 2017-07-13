import {Component, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {DatatableComponent} from "@swimlane/ngx-datatable";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";

@Component({
    selector: 'menu-list',
    template: `
        <div>
            <div class="input-group">
                <input class="form-control" placeholder="Search..." type="text" (keyup)='searchArticle($event)'>
            </div>
            <ngx-datatable
                    #articleListTable
                    class="material ngx-datatable fixed-header fixed-row"
                    [rows]="articleItemList"
                    [columnMode]="'force'"
                    [headerHeight]="40"
                    [footerHeight]="40"
                    [rowHeight]="'auto'"
                    [sortType]="'multi'"
                    [limit]="10"
            >
                <ngx-datatable-column name="ID" [width]="10">
                    <ng-template let-row="row" ngx-datatable-cell-template>
                        {{row.id}}
                    </ng-template>
                </ngx-datatable-column>
                <ngx-datatable-column name="Title">
                    <ng-template let-row="row" ngx-datatable-cell-template>
                        {{row.title}}
                    </ng-template>
                </ngx-datatable-column>
                <ngx-datatable-column name="Meta Keywords">
                    <ng-template let-row="row" ngx-datatable-cell-template>
                        {{row.metaKeywords}}
                    </ng-template>
                </ngx-datatable-column>
                <ngx-datatable-column name="Meta Description">
                    <ng-template let-row="row" ngx-datatable-cell-template>
                        {{row.metaDescription | slice:0:70}}...
                    </ng-template>
                </ngx-datatable-column>
                <ngx-datatable-column name="Actions">
                    <ng-template let-row="row" ngx-datatable-cell-template>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning" (click)="onEditClick(row)">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-danger" (click)="onDeleteClick(row)">
                                <i class="glyphicon glyphicon-remove"></i>
                            </button>
                            <button type="button"
                                    [ngClass]="getButtonClassForStatusOn(row)"
                                    (click)="onStatusChangeClick(row)">
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </button>
                            <button type="button"
                                    [ngClass]="getButtonClassForStatusOff(row)"
                                    (click)="onStatusChangeClick(row)">
                                <i class="glyphicon glyphicon-eye-close"></i>
                            </button>
                        </div>
                    </ng-template>
                </ngx-datatable-column>
            </ngx-datatable>
        </div>

        <ngui-popup #popup></ngui-popup>
    `,
    providers: [ArticleService]
})

export class AppArticleComponent implements OnInit {
    articleItemList: ArticleItem[] = [];
    actionButtonClassName: string;
    tempArticleList: ArticleItem[] = [];

    @ViewChild(DatatableComponent) articleListTable: DatatableComponent;
    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(
        private articleService: ArticleService,
        private router: Router,
        private route: ActivatedRoute
    ) {}

    ngOnInit() {
        this.articleService.getArticleItemList()
            .subscribe(
                (articleList: ArticleItem[]) => {
                    this.articleItemList = articleList;
                    this.tempArticleList = [...articleList];
                },
                (error) => {
                    showErrorPopup(error);
                }
            );

        this.actionButtonClassName = "btn btn-";
    }

    // onNewMenuItem() {
    //     this.router.navigate(['menu-new'], {relativeTo: this.route});
    // }

    getButtonClassForStatusOn(row: any): string {
        let currentStatus = row.status ? "success disabled" : "default";
        return this.actionButtonClassName + currentStatus;
    }

    getButtonClassForStatusOff(row: any): string {
        let currentStatus = row.status ? "default" : "success disabled";
        return this.actionButtonClassName + currentStatus;
    }

    onStatusChangeClick(row: any): void {
        row.status = !row.status;

        this.articleService.updateArticle(row)
            .subscribe(
                (response: any) => console.log('response', response),
                (error) => {
                    showErrorPopup(error);

                    row.status = !row.status;
                }
            );
    }

    onEditClick(row: any): void {
        console.log("onEditClick", row);
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
                                this.articleItemList = this.articleItemList.filter(obj => obj !== articleItem);
                            },
                            (error) => {
                                showErrorPopup(error);
                            }
                        );
                },
                CANCEL: () => this.popup.close();
            }
        });
    }

    searchArticle(event: any) {
        const val = event.target.value.toLowerCase();

        // filter our data
        this.articleItemList = this.tempArticleList.filter(function (articleItem) {
            return articleItem.title.toLowerCase().indexOf(val) !== -1 || !val;
        });

        // Whenever the filter changes, always go back to the first page
        this.articleListTable.offset = 0;
    }

    ngOnDestroy() {
        // this.articleItemList.unsubscribe();
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
}
