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
var menu_service_1 = require("../../menu/menu.service");
var ng2_ckeditor_1 = require("ng2-ckeditor");
var app_main_ckeditor_helper_1 = require("../../app.main.ckeditor.helper");
var AppArticleItemComponentOld = (function () {
    function AppArticleItemComponentOld(articleService, menuService, router, route, appMainCkeditorHelper) {
        this.articleService = articleService;
        this.menuService = menuService;
        this.router = router;
        this.route = route;
        this.appMainCkeditorHelper = appMainCkeditorHelper;
        this.editMode = false;
        this.dateTimePicker = new ng_pick_datetime_1.DateTimePickerModule();
        this.ckeditorConfig = appMainCkeditorHelper.getCkeditorDefaultConfig();
    }
    AppArticleItemComponentOld.prototype.ngOnInit = function () {
        var _this = this;
        this.articleItemSubscription = this.route.params
            .subscribe(function (params) {
            _this.articleId = +params['id'];
            _this.editMode = params['id'] != null;
            _this.initEmptyForm();
            _this.articleItem = new article_item_model_1.ArticleItem({});
            _this.setAvailableMenuList();
            if (_this.editMode) {
                _this.initArticleItem();
            }
            else {
                _this.initForm();
            }
        });
    };
    AppArticleItemComponentOld.prototype.initEmptyForm = function () {
        this.articleForm = new forms_1.FormGroup({
            'id': new forms_1.FormControl(null),
            'createdAt': new forms_1.FormControl(null),
            'title': new forms_1.FormControl(null),
            'description': new forms_1.FormControl(null),
            'ckeditorContent': new forms_1.FormControl(null),
            'metaDescription': new forms_1.FormControl(null),
            'metaKeywords': new forms_1.FormControl(null),
            'image': new forms_1.FormControl(null),
            'slug': new forms_1.FormControl(null),
            'status': new forms_1.FormControl(null),
            'isSentMail': new forms_1.FormControl(null),
            'numSequence': new forms_1.FormControl(null)
        });
    };
    AppArticleItemComponentOld.prototype.initArticleItem = function () {
        var _this = this;
        var article = this.articleService.getArticleById(this.articleId);
        //try to fill article list after page was manually reloaded
        if (article) {
            this.articleItem = article;
            this.initForm();
            return;
        }
        this.articleDataSubscription = this.articleService.getArticleItemByIdFromServer(this.articleId)
            .subscribe(function (articleItem) {
            _this.articleItem = articleItem;
            _this.initForm();
        }, function (error) {
            _this.showErrorPopup(error);
        });
    };
    AppArticleItemComponentOld.prototype.setAvailableMenuList = function () {
        var _this = this;
        this.availableMenuList = this.menuService.getMenuItemList();
        if (!this.availableMenuList.length) {
            this.menuListSubscription = this.menuService.getMenuItemListFromService()
                .subscribe(function (menuItems) {
                _this.availableMenuList = menuItems;
            }, function (error) {
                _this.showErrorPopup(error);
            });
        }
    };
    AppArticleItemComponentOld.prototype.initForm = function () {
        var _this = this;
        this.assignedMenuItemList = this.articleItem.assignedMenuList;
        this.availableMenuList = this.availableMenuList.map(function (menuItem) {
            if (_this.assignedMenuItemList
                .find(function (assignedMenuItem) { return assignedMenuItem.id == menuItem.id; })) {
                menuItem.isChecked = true;
            }
            return menuItem;
        });
        this.createdAt = this.articleItem.date || Date.now();
        //set text for ckeditor replacement
        this.ckeditorContent = this.articleItem.text;
        //define image path for preview
        this.imagePath = this.articleItem.image ? '/img/blog/' + this.articleItem.image : '';
        this.articleForm = new forms_1.FormGroup({
            'id': new forms_1.FormControl(this.articleItem.id, forms_1.Validators.required),
            'createdAt': new forms_1.FormControl(this.articleItem.date, forms_1.Validators.required),
            'title': new forms_1.FormControl(this.articleItem.title, forms_1.Validators.required),
            'description': new forms_1.FormControl(this.articleItem.description),
            'ckeditorContent': new forms_1.FormControl(this.articleItem.text),
            'metaDescription': new forms_1.FormControl(this.articleItem.metaDescription),
            'metaKeywords': new forms_1.FormControl(this.articleItem.metaKeywords),
            'image': new forms_1.FormControl(null),
            'slug': new forms_1.FormControl(this.articleItem.slug, forms_1.Validators.required),
            'status': new forms_1.FormControl(this.articleItem.status, forms_1.Validators.required),
            'isSentMail': new forms_1.FormControl(this.articleItem.isSentMail),
            'numSequence': new forms_1.FormControl(this.articleItem.numSequence)
        });
    };
    AppArticleItemComponentOld.prototype.imageUpload = function (event) {
        var _this = this;
        var eventObj = event;
        var target = eventObj.target;
        var files = target.files;
        this.file = files[0];
        var reader = new FileReader();
        reader.onloadend = function () {
            //Assign the result to variable for setting the src of image element
            _this.imagePath = reader.result;
        };
        this.originalImageName = this.file.name;
        reader.readAsDataURL(this.file);
    };
    AppArticleItemComponentOld.prototype.onSubmit = function () {
        var _this = this;
        this.fillArticleItem();
        if (this.editMode) {
            this.articleService.updateArticle(this.articleItem)
                .subscribe(function (response) {
                _this.articleItem.imageData = null;
                _this.articleService.updateArticleItemInList(_this.articleItem);
                _this.redirectToArticleList();
            }, function (error) {
                _this.showErrorPopup(error);
            });
        }
        else {
            this.articleService.addArticle(this.articleItem)
                .subscribe(function (response) {
                _this.articleItem.imageData = null;
                _this.articleService.addArticleItemToList(_this.articleItem);
                _this.redirectToArticleList();
            }, function (error) {
                _this.showErrorPopup(error);
            });
        }
    };
    AppArticleItemComponentOld.prototype.fillArticleItem = function () {
        this.articleItem.title = this.articleForm.value.title;
        this.articleItem.imageData = this.file != null ? this.imagePath : null;
        this.articleItem.metaDescription = this.articleForm.value.metaDescription;
        this.articleItem.metaKeywords = this.articleForm.value.metaKeywords;
        this.articleItem.text = this.articleForm.value.ckeditorContent;
        this.articleItem.description = this.articleForm.value.description;
        this.articleItem.slug = this.articleForm.value.slug;
        this.articleItem.date = this.articleForm.value.createdAt;
        this.articleItem.image = this.file != null ? this.originalImageName : this.articleItem.image;
        this.articleItem.assignedMenuList = this.assignedMenuItemList;
    };
    AppArticleItemComponentOld.prototype.onCancel = function () {
        this.redirectToArticleList();
    };
    AppArticleItemComponentOld.prototype.redirectToArticleList = function () {
        this.router.navigate(['/article-list'], { relativeTo: this.route });
    };
    AppArticleItemComponentOld.prototype.onDatePickerChange = function (moment) {
        this.createdAt = moment;
    };
    AppArticleItemComponentOld.prototype.onChangeAssignment = function (menuItem) {
        var previousState = menuItem.isChecked;
        if (previousState) {
            this.assignedMenuItemList = this.assignedMenuItemList
                .filter(function (assignedMenuItem) { return assignedMenuItem.id !== menuItem.id; });
        }
        else {
            this.assignedMenuItemList.push(menuItem);
        }
        menuItem.isChecked = !previousState;
    };
    AppArticleItemComponentOld.prototype.showErrorPopup = function (error) {
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
    AppArticleItemComponentOld.prototype.ngOnDestroy = function () {
        console.log('article ITEM - ON DESTROY');
        if (this.articleItemSubscription != undefined) {
            this.articleItemSubscription.unsubscribe();
        }
        if (this.articleDataSubscription != undefined) {
            this.articleDataSubscription.unsubscribe();
        }
    };
    __decorate([
        core_1.ViewChild(popup_1.NguiPopupComponent),
        __metadata("design:type", popup_1.NguiPopupComponent)
    ], AppArticleItemComponentOld.prototype, "popup", void 0);
    __decorate([
        core_1.ViewChild(ng2_ckeditor_1.CKEditorModule),
        __metadata("design:type", ng2_ckeditor_1.CKEditorModule)
    ], AppArticleItemComponentOld.prototype, "ckeditorContainer", void 0);
    AppArticleItemComponentOld = __decorate([
        core_1.Component({
            selector: 'article-edit',
            styles: [
                '.form-control.ckeditor { padding: 0; height: auto!important; }',
                '.preview-image-container {padding-top: 6px}'
            ],
            template: "\n        <div class=\"content\">\n            <div class=\"row\">\n                <div class=\"col-xs-12\">\n                    <div class=\"box box-success\">\n                        <h2 *ngIf=\"articleItem.isActive()\" class=\"label bg-green\">Active</h2>\n                        <h2 *ngIf=\"!articleItem.isActive()\" class=\"label bg-red\">Inactive</h2>\n                        <form [formGroup]=\"articleForm\" (ngSubmit)=\"onSubmit()\">\n                            <div class=\"box-body\">\n                                <div class=\"form-group\">\n                                    <label for=\"title\">Title</label>\n                                    <input\n                                            type=\"text\"\n                                            id=\"title\"\n                                            formControlName=\"title\"\n                                            class=\"form-control\">\n                                </div>\n                                <div class=\"row\">\n                                    <div class=\"col-xs-3\">\n                                        <div class=\"form-group\">\n                                            <label for=\"image\">Image for preview (165 x 165)</label>\n                                            <input\n                                                    type=\"file\"\n                                                    id=\"image\"\n                                                    formControlName=\"image\"\n                                                    class=\"form-control\"\n                                                    (change)=\"imageUpload($event)\"\n                                                    #image>\n                                        </div>\n                                        <div class=\"form-group\">\n                                            <label for=\"slug\">Slug</label>\n                                            <input\n                                                    type=\"text\"\n                                                    id=\"slug\"\n                                                    formControlName=\"slug\"\n                                                    class=\"form-control\">\n                                        </div>\n                                        <div class=\"form-group\">\n                                            <label for=\"createdAt\">Date</label>\n                                            <input\n                                                    type=\"text\"\n                                                    id=\"createdAt\"\n                                                    formControlName=\"createdAt\"\n                                                    class=\"form-control\"\n                                                    dateTimePicker\n                                                    [returnObject]=\"'string'\"\n                                                    [viewFormat]=\"'YYYY-MM-DD HH:mm'\"\n                                                    [value]=\"createdAt | date: 'y-MM-dd HH:mm'\"\n                                                    [mode]=\"'dropdown'\"\n                                                    [autoClose]=\"true\"\n                                                    (onChange)=\"onDatePickerChange($event)\"\n                                            >\n                                        </div>\n                                    </div>\n                                    <div class=\"col-xs-3\">\n                                        <div class=\"form-group preview-image-container\">\n                                            <img [src]=\"imagePath\" class=\"img-responsive\">\n                                        </div>\n                                    </div>\n                                    <div class=\"col-xs-6\">\n                                        <div class=\"form-group\">\n                                            <label for=\"description\">Description</label>\n                                            <textarea\n                                                    type=\"text\"\n                                                    id=\"description\"\n                                                    class=\"form-control\"\n                                                    formControlName=\"description\"\n                                                    rows=\"6\"></textarea>\n                                        </div>\n                                    </div>\n                                </div>\n                                <div class=\"row\">\n                                    <div class=\"col-xs-6\">\n                                        <div class=\"form-group\">\n                                            <label for=\"metaDescription\">Meta Description</label>\n                                            <textarea\n                                                    type=\"text\"\n                                                    id=\"metaDescription\"\n                                                    class=\"form-control\"\n                                                    formControlName=\"metaDescription\"\n                                                    rows=\"6\"></textarea>\n                                        </div>\n                                    </div>\n                                    <div class=\"col-xs-6\">\n                                        <div class=\"form-group\">\n                                            <label for=\"metaKeywords\">Meta Keywords</label>\n                                            <textarea\n                                                    type=\"text\"\n                                                    id=\"metaKeywords\"\n                                                    class=\"form-control\"\n                                                    formControlName=\"metaKeywords\"\n                                                    rows=\"6\"></textarea>\n                                        </div>\n                                    </div>\n                                </div>        \n                                <div class=\"form-group\">\n                                    <label for=\"text\">Text</label>\n                                    <ckeditor\n                                        id=\"ckeditorContainer\"\n                                        class=\"form-control ckeditor\"\n                                        formControlName=\"ckeditorContent\"\n                                        [readonly]=\"false\"\n                                        debounce=\"500\"\n                                        [config]=\"ckeditorConfig\"\n                                        >\n                                    </ckeditor>\n                                </div>\n                                <div class=\"form-group\">\n                                    <label for=\"assignedMenuList\">Assigned menu list: </label>\n                                    <span *ngFor=\"let menuItem of availableMenuList;\">\n                                        <input\n                                                type=\"checkbox\"\n                                                [checked]=\"menuItem.isChecked\"\n                                                (change)=\"onChangeAssignment(menuItem)\"\n                                        > {{ menuItem.title }} |\n                                    </span>\n                                </div>\n                            </div>\n                            <div class=\"box-footer\">\n                                <button\n                                        type=\"submit\"\n                                        class=\"btn btn-success\"\n                                        [disabled]=\"!articleForm.valid\">Save\n                                </button>\n                                <button\n                                        type=\"button\"\n                                        class=\"btn btn-danger\"\n                                        (click)=\"onCancel()\">Cancel\n                                </button>\n                            </div>\n                        </form>\n                    </div>\n                </div>\n            </div>\n        </div>\n        <ngui-popup #popup></ngui-popup>\n    "
        }),
        __metadata("design:paramtypes", [typeof (_a = typeof article_service_1.ArticleService !== "undefined" && article_service_1.ArticleService) === "function" && _a || Object, typeof (_b = typeof menu_service_1.MenuService !== "undefined" && menu_service_1.MenuService) === "function" && _b || Object, router_1.Router,
            router_1.ActivatedRoute,
            app_main_ckeditor_helper_1.AppMainCkeditorHelper])
    ], AppArticleItemComponentOld);
    return AppArticleItemComponentOld;
    var _a, _b;
}());
exports.AppArticleItemComponentOld = AppArticleItemComponentOld;
//# sourceMappingURL=article-item.component.js.map