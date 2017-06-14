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
var menu_service_1 = require("../menu.service");
var AppMenuComponent = (function () {
    function AppMenuComponent(menuService, router, route) {
        this.menuService = menuService;
        this.router = router;
        this.route = route;
    }
    AppMenuComponent.prototype.ngOnInit = function () {
        this.menuItemList = this.menuService.getMenuItemList();
    };
    AppMenuComponent.prototype.onNewMenuItem = function () {
        this.router.navigate(['menu-new'], { relativeTo: this.route });
    };
    return AppMenuComponent;
}());
AppMenuComponent = __decorate([
    core_1.Component({
        selector: 'menu-list',
        // templateUrl: '/app-angular/src/app/menu/menu-list.component.html'
        template: "\n        <div class=\"content-wrapper\" style=\"width: 100%; height: 50px; border: solid 1px red\">\n            <div class=\"row\">\n                <div class=\"col-xs-12\">\n                    <button class=\"btn btn-success\" (click)=\"onNewMenuItem()\">New Item</button>\n                </div>\n            </div>\n            <hr>\n            <div class=\"row\">\n                <div class=\"col-xs-12\">\n                    <!--<app-menu-item-->\n                            <!--*ngFor=\"let menuItem of menuItemList; let i = index\"-->\n                            <!--[menuItem]=\"menuItem\"-->\n                            <!--[index]=\"i\"></app-menu-item>-->\n                </div>\n            </div>\n        </div>"
        // ,
        // styleUrls: ['app.menu.css']
    }),
    __metadata("design:paramtypes", [menu_service_1.MenuService,
        router_1.Router,
        router_1.ActivatedRoute])
], AppMenuComponent);
exports.AppMenuComponent = AppMenuComponent;
//# sourceMappingURL=menu-list.component.js.map