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
var article_item_model_1 = require("./article-item.model");
var ArticleService = (function () {
    function ArticleService() {
        this.articleItemSelected = new core_1.EventEmitter();
        this.articleItemList = [
            new article_item_model_1.ArticleItem(1, 'Article1', 'Some metadescription for article1', 'Some metakeywords for article1', true, ['Blog']),
            new article_item_model_1.ArticleItem(2, 'Article2', 'Some metadescription for article2', 'Some metakeywords for article2', false, ['Blog', 'Books', 'Job searching'])
        ];
    }
    ArticleService.prototype.getArticleItemList = function () {
        return this.articleItemList.slice();
    };
    ArticleService.prototype.getArticleItem = function (index) {
        return this.articleItemList[index];
    };
    return ArticleService;
}());
ArticleService = __decorate([
    core_1.Injectable(),
    __metadata("design:paramtypes", [])
], ArticleService);
exports.ArticleService = ArticleService;
//# sourceMappingURL=article.service.js.map