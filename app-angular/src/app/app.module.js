"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
Object.defineProperty(exports, "__esModule", { value: true });
var platform_browser_1 = require("@angular/platform-browser");
var core_1 = require("@angular/core");
var http_1 = require("@angular/http");
var ngx_datatable_1 = require("@swimlane/ngx-datatable");
var app_routing_module_1 = require("./app.routing.module");
var app_main_component_1 = require("./app.main.component");
var menu_list_component_1 = require("./menu/menu-list/menu-list.component");
var menu_service_1 = require("./menu/menu.service");
var article_list_component_1 = require("./blog/article-list/article-list.component");
var article_item_component_1 = require("./blog/article-item/article-item.component");
var popup_1 = require("@ngui/popup");
var forms_1 = require("@angular/forms");
var ng_pick_datetime_1 = require("ng-pick-datetime");
var ng2_ckeditor_1 = require("ng2-ckeditor");
// import { FileSelectDirective, FileDropDirective } from 'ng2-file-upload';
var common_1 = require("@angular/common");
var article_service_1 = require("./blog/article.service");
var app_main_ckeditor_helper_1 = require("./app.main.ckeditor.helper");
var AppModule = (function () {
    function AppModule() {
    }
    AppModule = __decorate([
        core_1.NgModule({
            declarations: [
                app_main_component_1.AppMainComponent,
                menu_list_component_1.AppMenuComponent,
                article_list_component_1.AppArticleListComponent,
                article_item_component_1.AppArticleItemComponent
                // ,
                // FileSelectDirective,
                // FileDropDirective
            ],
            imports: [
                platform_browser_1.BrowserModule,
                forms_1.FormsModule,
                http_1.HttpModule,
                app_routing_module_1.AppRoutingModule,
                ngx_datatable_1.NgxDatatableModule,
                popup_1.NguiPopupModule,
                forms_1.FormsModule,
                forms_1.ReactiveFormsModule,
                ng_pick_datetime_1.DateTimePickerModule,
                ng2_ckeditor_1.CKEditorModule,
            ],
            providers: [
                menu_service_1.MenuService,
                article_service_1.ArticleService,
                {
                    provide: common_1.APP_BASE_HREF,
                    useValue: '/'
                },
                app_main_ckeditor_helper_1.AppMainCkeditorHelper
            ],
            bootstrap: [app_main_component_1.AppMainComponent]
        })
    ], AppModule);
    return AppModule;
}());
exports.AppModule = AppModule;
//# sourceMappingURL=app.module.js.map