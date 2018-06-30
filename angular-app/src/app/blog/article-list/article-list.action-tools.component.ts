import {Component} from "@angular/core";

import {ICellRendererAngularComp} from "ag-grid-angular";
import {ArticleService} from "../article.service";
import {ArticleItem} from "../article-item.model";
import {ActivatedRoute, Router} from "@angular/router";

@Component({
    selector: 'action-tool-cell',
    templateUrl: '../../common/action-tools.html'
})
export class ArticleListActionToolsComponent implements ICellRendererAngularComp {
    public params: any;
    private actionButtonClassName: string;
    public articleItem: ArticleItem;

    constructor(private articleService: ArticleService,
                private router: Router,
                private route: ActivatedRoute) {
    }

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
                (response: any) => console.log('onStatusChangeClick response', response),
                (error) => {
                    this.articleService.errorMessage.emit(error);

                    this.articleItem.status = !+this.articleItem.status;
                }
            );
    }

    onEditClick(): void {
        this.router.navigate(['/article-edit', this.articleItem.id], {relativeTo: this.route});
    }

    onDeleteClick(): void {
        this.articleService.deleteArticle(this.articleItem)
            .subscribe(
                (articleItem: ArticleItem) => {
                    this.articleService.articleItemDeleted.emit(articleItem);
console.log('onDeleteClick articleItem', articleItem);
                },
                (error) => {
                    this.articleService.errorMessage.emit(error);
                }
            );
    }

    refresh(): boolean {
        return false;
    }
}
