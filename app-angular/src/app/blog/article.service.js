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
        this.urlToGetList = '/article/list';
        this.urlToGetArticleItem = '/article/';
        this.urlToUpdate = '/article/update';
        this.urlToAdd = '/article/add';
        this.urlToDelete = '/article/delete';
        this.headers = new http_1.Headers({ 'Content-Type': 'application/json' });
        this.articleItemList = [];
    }
    ArticleService.prototype.getArticleItemList = function () {
        return this.articleItemList;
    };
    ArticleService.prototype.getArticleItemListFromServer = function () {
        var _this = this;
        return this.http.get(this.urlToGetList)
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
    ArticleService.prototype.getArticleItemByIdFromServer = function (id) {
        var link = this.urlToGetArticleItem + id;
        return this.http.get(link)
            .map(function (response) {
            var articleData = response.json();
            return new article_item_model_1.ArticleItem(articleData);
        })
            .catch(function (error) {
            return Observable_1.Observable.throw(error.toString());
        });
    };
    ArticleService.prototype.getArticleItem = function (index) {
        return this.articleItemList[index];
    };
    ArticleService.prototype.updateArticleItemInList = function (articleItem) {
        var index = this.articleItemList.indexOf(articleItem);
        this.articleItemList[index] = articleItem;
    };
    ArticleService.prototype.addArticleItemToList = function (articleItem) {
        this.articleItemList.push(articleItem);
    };
    ArticleService.prototype.updateArticle = function (articleItem) {
        return this.http
            .put(this.urlToUpdate, articleItem, { headers: this.headers })
            .map(function (response) {
            return response.json();
        })
            .catch(function (error) {
            return Observable_1.Observable.throw(error.statusText);
        });
    };
    ArticleService.prototype.addArticle = function (articleItem) {
        return this.http
            .post(this.urlToAdd, articleItem, { headers: this.headers })
            .map(function (response) {
            return response.json();
        })
            .catch(function (error) {
            return Observable_1.Observable.throw(error.statusText);
        });
    };
    ArticleService.prototype.deleteArticle = function (articleItem) {
        return this.http
            .delete(this.urlToDelete + '/' + articleItem.id)
            .map(function (response) {
            return response.json();
        })
            .catch(function (error) {
            return Observable_1.Observable.throw(error.statusText);
        });
    };
    ArticleService.prototype.getArticleById = function (id) {
        if (!this.articleItemList.length) {
            return null;
        }
        var filteredArticleList;
        filteredArticleList = this.articleItemList.filter(function (articleItem) {
            if (+articleItem.id === id) {
                return articleItem;
            }
        });
        return filteredArticleList[0];
    };
    ArticleService = __decorate([
        core_1.Injectable(),
        __metadata("design:paramtypes", [http_1.Http])
    ], ArticleService);
    return ArticleService;
}());
exports.ArticleService = ArticleService;
//# sourceMappingURL=article.service.js.map