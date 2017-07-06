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
var forms_1 = require("@angular/forms");
var http_1 = require("@angular/http");
var ngx_datatable_1 = require("@swimlane/ngx-datatable");
var app_routing_module_1 = require("./app.routing.module");
var app_main_component_1 = require("./app.main.component");
var menu_list_component_1 = require("./menu/menu-list/menu-list.component");
var menu_service_1 = require("./menu/menu.service");
var article_list_component_1 = require("./blog/article-list/article-list.component");
var common_1 = require("@angular/common");
var AppModule = (function () {
    function AppModule() {
    }
    return AppModule;
}());
AppModule = __decorate([
    core_1.NgModule({
        declarations: [
            app_main_component_1.AppMainComponent,
            menu_list_component_1.AppMenuComponent,
            article_list_component_1.AppArticleComponent
        ],
        imports: [
            platform_browser_1.BrowserModule,
            forms_1.FormsModule,
            http_1.HttpModule,
            app_routing_module_1.AppRoutingModule,
            ngx_datatable_1.NgxDatatableModule
        ],
        providers: [menu_service_1.MenuService, { provide: common_1.APP_BASE_HREF, useValue: '/' }],
        bootstrap: [app_main_component_1.AppMainComponent]
    })
], AppModule);
exports.AppModule = AppModule;
//# sourceMappingURL=app.module.js.map