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
var article_item_model_1 = require("../article-item.model");
var article_service_1 = require("../article.service");
var popup_1 = require("@ngui/popup");
var forms_1 = require("@angular/forms");
var ng_pick_datetime_1 = require("ng-pick-datetime");
var AppArticleItemComponent = (function () {
    function AppArticleItemComponent(articleService, router, route) {
        this.articleService = articleService;
        this.router = router;
        this.route = route;
        this.articleItemList = [];
        this.editMode = false;
        this.dateTimePicker = new ng_pick_datetime_1.DateTimePickerModule();
    }
    AppArticleItemComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.route.params
            .subscribe(function (params) {
            _this.articleId = +params['id'];
            _this.editMode = params['id'] != null;
            _this.initForm();
        });
    };
    AppArticleItemComponent.prototype.onSubmit = function () {
        this.articleItem.title = this.articleForm.value['title'];
        if (this.editMode) {
            // this.articleService.updateArticle(this.articleId, this.recipeForm.value);
        }
        else {
            // this.articleService.addArticle(this.recipeForm.value);
        }
        this.onCancel();
    };
    // onAddIngredient() {
    //     (<FormArray>this.articleForm.get('ingredients')).push(
    //         new FormGroup({
    //             'name': new FormControl(null, Validators.required),
    //             'amount': new FormControl(null, [
    //                 Validators.required,
    //                 Validators.pattern(/^[1-9]+[0-9]*$/)
    //             ])
    //         })
    //     );
    // }
    // onDeleteIngredient(index: number) {
    //     (<FormArray>this.articleForm.get('ingredients')).removeAt(index);
    // }
    AppArticleItemComponent.prototype.onCancel = function () {
        this.router.navigate(['../'], { relativeTo: this.route });
    };
    AppArticleItemComponent.prototype.onDatePickerChange = function (moment) {
        this.createdAt = moment;
    };
    AppArticleItemComponent.prototype.showErrorPopup = function (error) {
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
    AppArticleItemComponent.prototype.initForm = function () {
        var assignedMenuList = new forms_1.FormArray([]);
        console.log('initForm - this.editMode', this.editMode);
        this.articleItem = new article_item_model_1.ArticleItem({});
        if (this.editMode) {
            this.articleItem = this.articleService.getArticleById(this.articleId);
            console.log('initForm - this.articleItem', this.articleItem);
            if (!this.articleItem) {
                this.showErrorPopup('Article item with ID ' + this.articleId + ' was not found');
                return false;
            }
            // if (this.articleItem.assignedMenuList.size > 0) {
            //     for (let assignedMenu of this.articleItem.assignedMenuList) {
            //         assignedMenuList.push(
            //             new FormGroup({
            //                 // 'name': new FormControl(assignedMenu.name, Validators.required)
            //             })
            //         );
            //     }
            // }
        }
        this.createdAt = this.articleItem.date || Date.now();
        this.articleForm = new forms_1.FormGroup({
            'id': new forms_1.FormControl(this.articleItem.id, forms_1.Validators.required),
            'createdAt': new forms_1.FormControl(this.articleItem.date, forms_1.Validators.required),
            'title': new forms_1.FormControl(this.articleItem.title, forms_1.Validators.required),
            'description': new forms_1.FormControl(this.articleItem.description),
            'text': new forms_1.FormControl(this.articleItem.text),
            'metaDescription': new forms_1.FormControl(this.articleItem.metaDescription),
            'metaKeywords': new forms_1.FormControl(this.articleItem.metaKeywords),
            // 'image': new FormControl(this.articleItem.image),
            'image': new forms_1.FormControl(null),
            'slug': new forms_1.FormControl(this.articleItem.slug, forms_1.Validators.required),
            'status': new forms_1.FormControl(this.articleItem.status, forms_1.Validators.required),
            'isSentMail': new forms_1.FormControl(this.articleItem.isSentMail),
            'numSequence': new forms_1.FormControl(this.articleItem.numSequence),
            'assignedMenuList': assignedMenuList
        });
    };
    return AppArticleItemComponent;
}());
__decorate([
    core_1.ViewChild(popup_1.NguiPopupComponent),
    __metadata("design:type", popup_1.NguiPopupComponent)
], AppArticleItemComponent.prototype, "popup", void 0);
AppArticleItemComponent = __decorate([
    core_1.Component({
        selector: 'article-item',
        template: "\n        <div class=\"content\">\n            <div class=\"row\">\n                <div class=\"col-xs-12\">\n                    <div class=\"box box-success\">\n                        <form [formGroup]=\"articleForm\" (ngSubmit)=\"onSubmit()\">\n                            <div class=\"box-body\">\n                                <div class=\"form-group\">\n                                    <label for=\"title\">Title</label>\n                                    <input\n                                            type=\"text\"\n                                            id=\"title\"\n                                            formControlName=\"title\"\n                                            class=\"form-control\">\n                                </div>\n                                <div class=\"row\">\n                                    <div class=\"col-xs-3\">\n                                        <div class=\"form-group\">\n                                            <label for=\"slug\">Slug</label>\n                                            <input\n                                                    type=\"text\"\n                                                    id=\"slug\"\n                                                    formControlName=\"slug\"\n                                                    class=\"form-control\">\n                                        </div>\n                                    </div>\n                                    <div class=\"col-xs-3\">\n                                        <div class=\"form-group\">\n                                            <label for=\"createdAt\">Date</label>\n                                            <input\n                                                    type=\"text\"\n                                                    id=\"createdAt\"\n                                                    formControlName=\"createdAt\"\n                                                    class=\"form-control\"\n                                                    dateTimePicker\n                                                    [returnObject]=\"'string'\"\n                                                    [viewFormat]=\"'YYYY-MM-DD HH:mm'\"\n                                                    [value]=\"createdAt | date: 'y-MM-dd HH:mm'\"\n                                                    [mode]=\"'dropdown'\"\n                                                    [autoClose]=\"true\"\n                                                    (onChange)=\"onDatePickerChange($event)\"\n                                            >\n                                        </div>\n                                    </div>\n                                    <div class=\"col-xs-3\">\n                                        <div class=\"form-group\">\n                                            <label for=\"image\">Image for preview</label>\n                                            <input\n                                                    type=\"file\"\n                                                    id=\"image\"\n                                                    formControlName=\"image\"\n                                                    class=\"form-control\"\n                                                    #image>\n                                        </div>\n                                    </div>\n                                    <div class=\"col-xs-3\">\n                                        <div class=\"form-group\">\n                                            image preview block\n                                            <!--<img [src]=\"image.value\" class=\"img-responsive\">-->\n                                        </div>\n                                    </div>\n                                </div>\n                                <div class=\"form-group\">\n                                    <label for=\"description\">Description</label>\n                                    <textarea\n                                            type=\"text\"\n                                            id=\"description\"\n                                            class=\"form-control\"\n                                            formControlName=\"description\"\n                                            rows=\"6\"></textarea>\n                                </div>\n                                <div class=\"form-group\">\n                                    <label for=\"metaDescription\">Meta Description</label>\n                                    <textarea\n                                            type=\"text\"\n                                            id=\"metaDescription\"\n                                            class=\"form-control\"\n                                            formControlName=\"metaDescription\"\n                                            rows=\"6\"></textarea>\n                                </div>\n                                <div class=\"form-group\">\n                                    <label for=\"metaKeywords\">Meta Keywords</label>\n                                    <textarea\n                                            type=\"text\"\n                                            id=\"metaKeywords\"\n                                            class=\"form-control\"\n                                            formControlName=\"metaKeywords\"\n                                            rows=\"6\"></textarea>\n                                </div>\n                                <div class=\"form-group\">\n                                    <label for=\"text\">Text</label>\n                                    <textarea\n                                            type=\"text\"\n                                            id=\"text\"\n                                            class=\"form-control\"\n                                            formControlName=\"text\"\n                                            rows=\"6\"></textarea>\n                                </div>\n                                <div class=\"form-group\">\n                                    <input\n                                            type=\"hidden\"\n                                            id=\"status\"\n                                            formControlName=\"status\"\n                                            class=\"form-control\">\n                                    <input\n                                            type=\"hidden\"\n                                            id=\"isSentMail\"\n                                            formControlName=\"isSentMail\"\n                                            class=\"form-control\">\n                                    <input\n                                            type=\"hidden\"\n                                            id=\"numSequence\"\n                                            formControlName=\"numSequence\"\n                                            class=\"form-control\">\n                                </div>\n                            </div>\n                            <div class=\"box-footer\">\n                                <button\n                                        type=\"submit\"\n                                        class=\"btn btn-success\"\n                                        [disabled]=\"!articleForm.valid\">Save\n                                </button>\n                                <button\n                                        type=\"button\"\n                                        class=\"btn btn-danger\"\n                                        (click)=\"onCancel()\">Cancel\n                                </button>\n                            </div>\n                        </form>\n                    </div>\n                </div>\n            </div>\n        </div>\n    "
    }),
    __metadata("design:paramtypes", [article_service_1.ArticleService,
        router_1.Router,
        router_1.ActivatedRoute])
], AppArticleItemComponent);
exports.AppArticleItemComponent = AppArticleItemComponent;
//# sourceMappingURL=article-item.component.js.map