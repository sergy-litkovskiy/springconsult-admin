import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders, HttpResponse} from '@angular/common/http';
import {Observable} from 'rxjs/Observable';
import "rxjs/add/operator/map";
import "rxjs/add/operator/catch";
import 'rxjs/add/observable/throw';

import {ArticleItem} from "./article-item.model";

@Injectable()
export class ArticleService {
    private urlToGetList = '/article/list';
    private urlToGetArticleItem = '/article/';
    private urlToUpdate = '/article/update';
    private urlToAdd = '/article/add';
    private urlToDelete = '/article/delete';
    private headers = new HttpHeaders({'Content-Type': 'application/json'});

    private articleItemList: ArticleItem[] = [];

    constructor(private http: HttpClient) {}

    getArticleItemList() {
console.log('getArticleItemList', this.articleItemList);
        return this.articleItemList;
    }

    getArticleItemListFromServer() {
        return this.http.get<ArticleItem[]>(this.urlToGetList, {observe: 'body', responseType: 'json'})
            .map(
                (articleDataList) => {
                    for (let articleData of articleDataList) {
                        let articleItem = new ArticleItem(articleData);
                        this.articleItemList.push(articleItem);
                    }
                    return this.articleItemList;
                }
            )
            .catch(
                (error: any) => {
                    return Observable.throw(error.toString());
                }
            );
    }

    getArticleItemByIdFromServer(id: number) {
        let link = this.urlToGetArticleItem + id;

        return this.http.get(link)
            .map(
                (response: any) => {
                    let articleData = response.json();

                    return new ArticleItem(articleData);
                }
            )
            .catch(
                (error: any) => {
                    return Observable.throw(error.toString());
                }
            );
    }

    getArticleItem(index: number) {
        return this.articleItemList[index];
    }

    updateArticleItemInList(articleItem: ArticleItem) {
        let index = this.articleItemList.indexOf(articleItem);
        this.articleItemList[index] = articleItem;
    }

    addArticleItemToList(articleItem: ArticleItem) {
        this.articleItemList.push(articleItem);
    }

    updateArticle(articleItem: ArticleItem) {
        return this.http
            .put(this.urlToUpdate, articleItem, {headers: this.headers})
                .map(
                    (response: any) => {
                        return response.json();
                    }
                )
                .catch(
                    (error: any) => {
                        return Observable.throw(error.statusText);
                    }
                );
    }

    addArticle(articleItem: ArticleItem) {
        return this.http
            .post(this.urlToAdd, articleItem, {headers: this.headers})
                .map(
                    (response: any) => {
                        return response.json();
                    }
                )
                .catch(
                    (error: any) => {
                        return Observable.throw(error.statusText);
                    }
                );
    }

    deleteArticle(articleItem: ArticleItem) {
        return this.http
            .delete(this.urlToDelete + '/' + articleItem.id)
                .map(
                    (response: any) => {
                        return response.json();
                    }
                )
                .catch(
                    (error: any) => {
                        return Observable.throw(error.statusText);
                    }
                );
    }

    getArticleById(id: number): ArticleItem|null {
        if (!this.articleItemList.length) {
            return null;
        }

        let filteredArticleList: ArticleItem[];

        filteredArticleList = this.articleItemList.filter(function (articleItem: ArticleItem) {
            if(+articleItem.id === id) {
                return articleItem;
            }
        });

        return filteredArticleList[0];
    }

}
