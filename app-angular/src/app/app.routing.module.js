"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
Object.defineProperty(exports, "__esModule", { value: true });
var core_1 = require("@angular/core");
var router_1 = require("@angular/router");
var menu_list_component_1 = require("./menu/menu-list/menu-list.component");
var article_list_component_1 = require("./blog/article-list/article-list.component");
var article_item_component_1 = require("./blog/article-item/article-item.component");
var routes = [
    { path: '', redirectTo: '/menu-list', pathMatch: 'full' },
    { path: 'menu-list', component: menu_list_component_1.AppMenuComponent },
    { path: 'article-list', component: article_list_component_1.AppArticleListComponent },
    { path: 'article-edit/:id', component: article_item_component_1.AppArticleItemComponent },
    { path: 'article-edit', component: article_item_component_1.AppArticleItemComponent }
];
var AppRoutingModule = (function () {
    function AppRoutingModule() {
    }
    AppRoutingModule = __decorate([
        core_1.NgModule({
            imports: [router_1.RouterModule.forRoot(routes)],
            exports: [router_1.RouterModule]
        })
    ], AppRoutingModule);
    return AppRoutingModule;
}());
exports.AppRoutingModule = AppRoutingModule;
//# sourceMappingURL=app.routing.module.js.map