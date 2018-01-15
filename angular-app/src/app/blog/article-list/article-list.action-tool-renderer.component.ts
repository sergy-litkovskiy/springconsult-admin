import {Component, ViewChild} from "@angular/core";

import {ICellRendererAngularComp} from "ag-grid-angular";
import {ArticleService} from "../article.service";
import {ArticleItem} from "../article-item.model";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {ActivatedRoute, Router} from "@angular/router";

@Component({
    selector: 'action-tool-cell',
    template: `
        <div class="btn-group">
            <button type="button"
                    data-action-type="edit"
                    class="btn btn-sm btn-warning"
                    (click)="onEditClick()">
                <i class="glyphicon glyphicon-pencil" data-action-type="edit"></i>
            </button>
            <button type="button"
                    data-action-type="remove"
                    class="btn btn-sm btn-danger"
                    (click)="onDeleteClick()">
                <i class="glyphicon glyphicon-remove" data-action-type="remove"></i>
            </button>
            <button type="button"
                    data-action-type="status"
                    [ngClass]="getButtonClassForStatusOn()"
                    (click)="onStatusChangeClick()">
                <i class="glyphicon glyphicon-eye-open" data-action-type="status"></i>
            </button>
            <button type="button"
                    data-action-type="status"
                    [ngClass]="getButtonClassForStatusOff()"
                    (click)="onStatusChangeClick()">
                <i class="glyphicon glyphicon-eye-close" data-action-type="status"></i>
            </button>
        </div>
    `
})

export class ArticleListActionToolRendererComponent implements ICellRendererAngularComp {
    public params: any;
    private actionButtonClassName: string;
    private articleItem: ArticleItem;

    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(private articleService: ArticleService,
                private router: Router,
                private route: ActivatedRoute) {}

    agInit(params: any): void {
        this.params = params;
console.log('agInit - params', params);
        this.articleItem = params.value;
        this.actionButtonClassName = "btn btn-";
    }

    getButtonClassForStatusOn(): string {
console.log('getButtonClassForStatusOn', this.articleItem.status);
        let currentStatus = +this.articleItem.status == 1 ? "success disabled" : "default";
        return this.actionButtonClassName + currentStatus;
    }

    getButtonClassForStatusOff(): string {
console.log('getButtonClassForStatusOff', this.articleItem.status);
        let currentStatus = +this.articleItem.status == 1 ? "default" : "success disabled";
        return this.actionButtonClassName + currentStatus;
    }

    onStatusChangeClick(): void {
console.log('onStatusChangeClick', this.articleItem);
        this.articleItem.status = !this.articleItem.status;

        // this.articleService.updateArticle(this.articleItem)
        //     .subscribe(
        //         (response: any) => console.log('response', response),
        //         (error) => {
        //             this.showErrorPopup(error);
        //
        //             this.articleItem.status = !this.articleItem.status;
        //         }
        //     );
    }

    onEditClick(): void {
console.log('onEditClick', this.articleItem);
        this.router.navigate(['/article-edit', this.articleItem.id], {relativeTo: this.route});
    }

    onDeleteClick(): void {
console.log('onDeleteClick', this.articleItem);

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

    refresh(): boolean {
        return false;
    }
}