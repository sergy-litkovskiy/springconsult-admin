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
var menu_item_model_1 = require("./menu-item.model");
var Observable_1 = require("rxjs/Observable");
var http_1 = require("@angular/http");
require("rxjs/add/operator/map");
require("rxjs/add/operator/catch");
require("rxjs/add/observable/throw");
var MenuService = (function () {
    function MenuService(http) {
        this.http = http;
        this.menuItemSelected = new core_1.EventEmitter();
        this.urlToGetList = '/menu/list';
        this.menuItemList = [];
    }
    MenuService.prototype.getMenuItemList = function () {
        var _this = this;
        return this.http.get(this.urlToGetList)
            .map(function (response) {
            var menuDataList = response.json();
            for (var _i = 0, menuDataList_1 = menuDataList; _i < menuDataList_1.length; _i++) {
                var menuData = menuDataList_1[_i];
                var menuItem = new menu_item_model_1.MenuItem(menuData);
                _this.menuItemList.push(menuItem);
            }
            return _this.menuItemList;
        })
            .catch(function (error) {
            return Observable_1.Observable.throw(error.toString());
        });
    };
    MenuService.prototype.getMenuItem = function (index) {
        return this.menuItemList[index];
    };
    return MenuService;
}());
MenuService = __decorate([
    core_1.Injectable(),
    __metadata("design:paramtypes", [http_1.Http])
], MenuService);
exports.MenuService = MenuService;
//# sourceMappingURL=menu.service.js.map