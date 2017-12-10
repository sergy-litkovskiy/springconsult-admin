"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
Object.defineProperty(exports, "__esModule", { value: true });
var core_1 = require("@angular/core");
var AppMainComponent = /** @class */ (function () {
    function AppMainComponent() {
        this.loadedFeature = 'menu-list';
    }
    AppMainComponent.prototype.onNavigate = function (feature) {
        this.loadedFeature = feature;
    };
    AppMainComponent = __decorate([
        core_1.Component({
            selector: 'adm-panel',
            template: "\n        <aside class=\"main-sidebar\">\n            <section class=\"sidebar\">\n                <ul class=\"sidebar-menu\">\n                    <li>\n                        <a routerLink=\"/menu-list\" routerLinkActive=\"active\">\n                            <span>Site menu</span>\n                        </a>\n                    </li>\n                    <li>\n                        <a routerLink=\"/article-list\">\n                            <span>Articles</span>\n                        </a>\n                    </li>\n                </ul>\n            </section>\n        </aside>\n        <div class=\"content-wrapper\">\n            <section class=\"content\">\n                <div class=\"row\">\n                    <router-outlet></router-outlet>\n                </div>\n            </section>\n        </div>\n    "
        })
    ], AppMainComponent);
    return AppMainComponent;
}());
exports.AppMainComponent = AppMainComponent;
//# sourceMappingURL=app.main.component.js.map