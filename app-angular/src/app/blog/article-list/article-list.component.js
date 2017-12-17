"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
Object.defineProperty(exports, "__esModule", { value: true });
var core_1 = require("@angular/core");
var router_1 = require("@angular/router");
var article_service_1 = require("../article.service");
var ngx_datatable_1 = require("@swimlane/ngx-datatable");
var popup_1 = require("@ngui/popup");
var AppArticleListComponentOld = (function () {
    function AppArticleListComponentOld(articleService, router, route) {
        this.articleService = articleService;
        this.router = router;
        this.route = route;
        this.tempArticleList = [];
    }
    AppArticleListComponentOld.prototype.ngOnInit = function () {
        var _this = this;
        this.articleItemList = this.articleService.getArticleItemList();
        if (!this.articleItemList.length) {
            this.articleListSubscription = this.articleService.getArticleItemListFromServer()
                .subscribe(function (articleList) {
                _this.articleItemList = articleList;
                _this.tempArticleList = articleList.slice();
            }, function (error) {
                _this.showErrorPopup(error);
            });
        }
        this.actionButtonClassName = "btn btn-";
    };
    // onNewMenuItem() {
    //     this.router.navigate(['menu-new'], {relativeTo: this.route});
    // }
    AppArticleListComponentOld.prototype.getButtonClassForStatusOn = function (row) {
        var currentStatus = +row.status == 1 ? "success disabled" : "default";
        return this.actionButtonClassName + currentStatus;
    };
    AppArticleListComponentOld.prototype.getButtonClassForStatusOff = function (row) {
        var currentStatus = +row.status == 1 ? "default" : "success disabled";
        return this.actionButtonClassName + currentStatus;
    };
    AppArticleListComponentOld.prototype.onStatusChangeClick = function (row) {
        var _this = this;
        row.status = !row.status;
        this.articleService.updateArticle(row)
            .subscribe(function (response) { return console.log('response', response); }, function (error) {
            _this.showErrorPopup(error);
            row.status = !row.status;
        });
    };
    AppArticleListComponentOld.prototype.onEditClick = function (articleItem) {
        this.router.navigate(['/article-edit', articleItem.id], { relativeTo: this.route });
    };
    AppArticleListComponentOld.prototype.onDeleteClick = function (articleItem) {
        var _this = this;
        this.popup.open(popup_1.NguiMessagePopupComponent, {
            classNames: 'small',
            title: articleItem.title,
            message: 'Are you sure you want to DELETE the article?',
            buttons: {
                OK: function () {
                    _this.popup.close();
                    _this.articleService.deleteArticle(articleItem)
                        .subscribe(function (response) {
                        _this.articleItemList = _this.articleItemList.filter(function (obj) { return obj !== articleItem; });
                    }, function (error) {
                        _this.showErrorPopup(error);
                    });
                },
                CANCEL: function () {
                    _this.popup.close();
                }
            }
        });
    };
    AppArticleListComponentOld.prototype.searchArticle = function (event) {
        var val = event.target.value.toLowerCase();
        // filter our data
        this.articleItemList = this.tempArticleList.filter(function (articleItem) {
            return articleItem.title.toLowerCase().indexOf(val) !== -1 || !val;
        });
        // Whenever the filter changes, always go back to the first page
        this.articleListTable.offset = 0;
    };
    AppArticleListComponentOld.prototype.showErrorPopup = function (error) {
        var _this = this;
        this.popup.open(popup_1.NguiMessagePopupComponent, {
            classNames: 'small',
            title: 'ERROR',
            message: error,
            buttons: {
                CLOSE: function () {
                    _this.popup.close();
                }
            }
        });
    };
    AppArticleListComponentOld.prototype.ngOnDestroy = function () {
        console.log('article LIST - ON DESTROY');
        if (this.articleListSubscription != undefined) {
            this.articleListSubscription.unsubscribe();
        }
    };
    __decorate([
        core_1.ViewChild(ngx_datatable_1.DatatableComponent),
        __metadata("design:type", ngx_datatable_1.DatatableComponent)
    ], AppArticleListComponentOld.prototype, "articleListTable", void 0);
    __decorate([
        core_1.ViewChild(popup_1.NguiPopupComponent),
        __metadata("design:type", popup_1.NguiPopupComponent)
    ], AppArticleListComponentOld.prototype, "popup", void 0);
    AppArticleListComponentOld = __decorate([
        core_1.Component({
            selector: 'article-list',
            styles: [
                '.search-panel {padding-bottom: 10px; padding-left: 10px}'
            ],
            template: "\n        <div>\n            <div class=\"input-group col-md-3 search-panel\">\n                <input class=\"form-control\" placeholder=\"Search by title ...\" type=\"text\" (keyup)='searchArticle($event)'>\n            </div>\n            <ngx-datatable\n                    #articleListTable\n                    class=\"material ngx-datatable fixed-header fixed-row\"\n                    [rows]=\"articleItemList\"\n                    [columnMode]=\"'force'\"\n                    [headerHeight]=\"40\"\n                    [footerHeight]=\"40\"\n                    [rowHeight]=\"'auto'\"\n                    [sortType]=\"'multi'\"\n                    [limit]=\"10\"\n            >\n                <ngx-datatable-column name=\"ID\" [width]=\"10\">\n                    <ng-template let-row=\"row\" ngx-datatable-cell-template>\n                        {{row.id}}\n                    </ng-template>\n                </ngx-datatable-column>\n                <ngx-datatable-column name=\"On|Off\" [width]=\"15\">\n                    <ng-template let-row=\"row\" ngx-datatable-cell-template>\n                        <span *ngIf=\"row.isActive()\" class=\"label bg-green\">on</span>\n                        <span *ngIf=\"!row.isActive()\" class=\"label bg-gray\">off</span>\n                    </ng-template>\n                </ngx-datatable-column>\n                <ngx-datatable-column name=\"Title\">\n                    <ng-template let-row=\"row\" ngx-datatable-cell-template>\n                        {{row.title}}\n                    </ng-template>\n                </ngx-datatable-column>\n                <ngx-datatable-column name=\"Assigned to:\">\n                    <ng-template let-row=\"row\" let-value=\"value\" ngx-datatable-cell-template>\n                        <ul>\n                            <li *ngFor='let item of row.assignedMenuList'>\n                                {{item.title}}\n                            </li>\n                        </ul>\n                    </ng-template>\n                </ngx-datatable-column>\n                <ngx-datatable-column name=\"Meta Keywords\">\n                    <ng-template let-row=\"row\" ngx-datatable-cell-template>\n                        {{row.metaKeywords}}\n                    </ng-template>\n                </ngx-datatable-column>\n                <ngx-datatable-column name=\"Meta Description\">\n                    <ng-template let-row=\"row\" ngx-datatable-cell-template>\n                        {{row.metaDescription | slice:0:70}}...\n                    </ng-template>\n                </ngx-datatable-column>\n                <ngx-datatable-column name=\"Actions\">\n                    <ng-template let-row=\"row\" ngx-datatable-cell-template>\n                        <div class=\"btn-group\">\n                            <button type=\"button\" class=\"btn btn-warning\" (click)=\"onEditClick(row)\">\n                                <i class=\"glyphicon glyphicon-pencil\"></i>\n                            </button>\n                            <button type=\"button\" class=\"btn btn-danger\" (click)=\"onDeleteClick(row)\">\n                                <i class=\"glyphicon glyphicon-remove\"></i>\n                            </button>\n                            <button type=\"button\"\n                                    [ngClass]=\"getButtonClassForStatusOn(row)\"\n                                    (click)=\"onStatusChangeClick(row)\">\n                                <i class=\"glyphicon glyphicon-eye-open\"></i>\n                            </button>\n                            <button type=\"button\"\n                                    [ngClass]=\"getButtonClassForStatusOff(row)\"\n                                    (click)=\"onStatusChangeClick(row)\">\n                                <i class=\"glyphicon glyphicon-eye-close\"></i>\n                            </button>\n                        </div>\n                    </ng-template>\n                </ngx-datatable-column>\n            </ngx-datatable>\n        </div>\n\n        <ngui-popup #popup></ngui-popup>\n    "
        }),
        __metadata("design:paramtypes", [typeof (_a = typeof article_service_1.ArticleService !== "undefined" && article_service_1.ArticleService) === "function" && _a || Object, router_1.Router,
            router_1.ActivatedRoute])
    ], AppArticleListComponentOld);
    return AppArticleListComponentOld;
    var _a;
}());
exports.AppArticleListComponentOld = AppArticleListComponentOld;
//# sourceMappingURL=article-list.component.js.map