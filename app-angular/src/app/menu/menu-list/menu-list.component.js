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
require("rxjs/add/operator/map");
require("rxjs/add/operator/catch");
require("rxjs/add/observable/throw");
var AppMenuComponent = (function () {
    function AppMenuComponent(menuService, router, route) {
        this.menuService = menuService;
        this.router = router;
        this.route = route;
        this.tempMenuItemList = [];
    }
    AppMenuComponent.prototype.ngOnInit = function () {
        var _this = this;
        this.menuListSubscription = this.menuService.getMenuItemList()
            .subscribe(function (menuItems) {
            _this.menuItemList = menuItems;
            _this.tempMenuItemList = menuItems.slice();
        }, function (error) {
            // this.showErrorPopup(error);
        });
    };
    AppMenuComponent.prototype.ngOnDestroy = function () {
        console.log('menu LIST - ON DESTROY');
        this.menuListSubscription.unsubscribe();
    };
    AppMenuComponent.prototype.onNewMenuItem = function () {
        this.router.navigate(['menu-new'], { relativeTo: this.route });
    };
    return AppMenuComponent;
}());
AppMenuComponent = __decorate([
    core_1.Component({
        selector: 'menu-list',
        // templateUrl: './menu-list.component.html',
        template: "\n        <div>\n            <ngx-datatable\n                class=\"material ngx-datatable fixed-header fixed-row scroll-vertical scroll-horz\"\n                [rows]=\"rows\"\n                [columns]=\"columns\"\n                [columnMode]=\"'force'\"\n                [headerHeight]=\"50\"\n                [footerHeight]=\"50\"\n                [rowHeight]=\"'auto'\"\n                [sortType]=\"'multi'\"\n                [limit]=\"10\">\n            </ngx-datatable>\n        </div>\n    ",
        providers: [menu_service_1.MenuService]
    }),
    __metadata("design:paramtypes", [menu_service_1.MenuService,
        router_1.Router,
        router_1.ActivatedRoute])
], AppMenuComponent);
exports.AppMenuComponent = AppMenuComponent;
//# sourceMappingURL=menu-list.component.js.map