import {Component, OnInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";

@Component({
    selector: 'menu-list',
    template: `
        <div>
            <ngx-datatable
                    class="material ngx-datatable fixed-header fixed-row scroll-vertical scroll-horz"
                    [rows]="articleItemList"
                    [columnMode]="'force'"
                    [headerHeight]="50"
                    [footerHeight]="50"
                    [rowHeight]="'auto'"
                    [sortType]="'multi'"
                    [limit]="10"
            >
                <ngx-datatable-column name="ID" [width]="50">
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
                        {{row.metaDescription}}
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
    `,
    providers: [ArticleService]
})

export class AppArticleComponent implements OnInit {
    articleItemList: ArticleItem[];
    actionButtonClassName: string;

    constructor(
        private articleService: ArticleService,
        private router: Router,
        private route: ActivatedRoute
    ) {
    }

    ngOnInit() {
        this.articleItemList = this.articleService.getArticleItemList();
        this.actionButtonClassName = "btn btn-";
    }

    onNewMenuItem() {
        this.router.navigate(['menu-new'], {relativeTo: this.route});
    }

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
        //todo: change status by service
    }

    onEditClick(row: any): void {
console.log("onEditClick", row);
    }

    onDeleteClick(row: any): void {
console.log("onDeleteClick", row);
//todo: delete item by service
    }
}
