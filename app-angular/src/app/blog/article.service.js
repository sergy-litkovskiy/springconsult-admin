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
var http_1 = require("@angular/http");
var Observable_1 = require("rxjs/Observable");
require("rxjs/add/operator/map");
require("rxjs/add/operator/catch");
require("rxjs/add/observable/throw");
var article_item_model_1 = require("./article-item.model");
var ArticleService = (function () {
    function ArticleService(http) {
        this.http = http;
        this.articleListUrl = '/article/list'; // URL to web API
        this.articleUpdateUrl = '/article/update'; // URL to web API
        this.articleItemSelected = new core_1.EventEmitter();
        this.articleItemList = [];
    }
    ArticleService.prototype.getArticleItemList = function () {
        var _this = this;
        return this.http.get(this.articleListUrl)
            .map(function (response) {
            var articleDataList = response.json();
            for (var _i = 0, articleDataList_1 = articleDataList; _i < articleDataList_1.length; _i++) {
                var articleData = articleDataList_1[_i];
                var articleItem = new article_item_model_1.ArticleItem(articleData);
                _this.articleItemList.push(articleItem);
            }
            return _this.articleItemList;
        })
            .catch(function (error) {
            return Observable_1.Observable.throw(error.toString());
        });
    };
    ArticleService.prototype.getArticleItem = function (index) {
        return this.articleItemList[index];
    };
    ArticleService.prototype.updateArticle = function (articleItem) {
        var headers = new http_1.Headers({ 'Content-Type': 'application/json' });
        return this.http
            .put(this.articleUpdateUrl, articleItem, { headers: headers })
            .map(function (response) {
            return response.json();
        })
            .catch(function (error) {
            return Observable_1.Observable.throw(error.statusText);
        });
    };
    return ArticleService;
}());
ArticleService = __decorate([
    core_1.Injectable(),
    __metadata("design:paramtypes", [http_1.Http])
], ArticleService);
exports.ArticleService = ArticleService;
//# sourceMappingURL=article.service.js.map