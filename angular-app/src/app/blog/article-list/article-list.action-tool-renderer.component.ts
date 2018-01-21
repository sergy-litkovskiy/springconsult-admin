import {Component, EventEmitter, Output, ViewChild} from "@angular/core";

import {ICellRendererAngularComp} from "ag-grid-angular";
import {ArticleService} from "../article.service";
import {ArticleItem} from "../article-item.model";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {ActivatedRoute, Router} from "@angular/router";

@Component({
    selector: 'action-tool-cell',
    template: `
        <ngui-popup #popup></ngui-popup>
        <div class="btn-group">
            <button type="button"
                    data-action-type="edit"
                    class="btn btn-sm btn-warning"
                    (click)="onEditClick()">
                <i class="glyphicon glyphicon-pencil"></i>
            </button>
            <button type="button"
                    data-action-type="remove"
                    class="btn btn-sm btn-danger"
                    [popover]="articlePopover">
                <i class="glyphicon glyphicon-remove"></i>
            </button>
            <button type="button"
                    data-action-type="status"
                    [ngClass]="getButtonClassForStatusOn()"
                    (click)="onStatusChangeClick()">
                <i class="glyphicon glyphicon-eye-open"></i>
            </button>
            <button type="button"
                    data-action-type="status"
                    [ngClass]="getButtonClassForStatusOff()"
                    (click)="onStatusChangeClick()">
                <i class="glyphicon glyphicon-eye-close"></i>
            </button>
            <popover-content 
                    #articlePopover 
                    title="Are you sure you want to DELETE the article?" 
                    placement="left"
                    [closeOnClickOutside]="true">
                <div class="btn-group">
                    <button type="button" class="btn btn-sml btn-success" title="Accept" (click)="onDeleteClick();articlePopover.hide()">
                        Yes
                    </button>
                    <button type="button" class="btn btn-sml btn-default" title="Cancel" (click)="articlePopover.hide()">
                        No
                    </button>
                </div>
            </popover-content>
        </div>
    `
})

export class ArticleListActionToolRendererComponent implements ICellRendererAngularComp {
    public params: any;
    private actionButtonClassName: string;
    public articleItem: ArticleItem;

    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(private articleService: ArticleService,
                private router: Router,
                private route: ActivatedRoute) {}

    agInit(params: any): void {
        this.params = params;
        this.articleItem = params.value;
        this.actionButtonClassName = "btn btn-sm btn-";
    }

    getButtonClassForStatusOn(): string {
        let currentStatus = +this.articleItem.status == 1 ? "success disabled" : "default";
        return this.actionButtonClassName + currentStatus;
    }

    getButtonClassForStatusOff(): string {
        let currentStatus = +this.articleItem.status == 1 ? "default" : "success disabled";
        return this.actionButtonClassName + currentStatus;
    }

    onStatusChangeClick(): void {
        this.articleItem.status = !+this.articleItem.status;
        this.articleService.updateArticle(this.articleItem)
            .subscribe(
                (response: any) => console.log('response', response),
                (error) => {
                    this.showErrorPopup(error);

                    this.articleItem.status = !+this.articleItem.status;
                }
            );
    }

    onEditClick(): void {
        this.router.navigate(['/article-edit', this.articleItem.id], {relativeTo: this.route});
    }

    onDeleteClick(): void {
console.log('onDeleteClick - tool renderer');

        this.articleService.deleteArticle(this.articleItem)
            .subscribe(
                (response) => {
console.log('subscribe - response', response);
                },
                (error) => {
                    this.showErrorPopup(error);
                }
            );
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