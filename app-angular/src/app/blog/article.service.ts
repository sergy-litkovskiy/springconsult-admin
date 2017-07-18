import {EventEmitter, Injectable} from '@angular/core';
import {Http, Response, Headers} from '@angular/http';
import {Observable} from 'rxjs/Observable';
import "rxjs/add/operator/map";
import "rxjs/add/operator/catch";
import 'rxjs/add/observable/throw';

import {ArticleItem} from "./article-item.model";

@Injectable()
export class ArticleService {
    private urlToGetList = '/article/list';
    private urlToUpdate = '/article/update';
    private urlToDelete = '/article/delete';

    articleItemSelected = new EventEmitter<ArticleItem>();

    private articleItemList: ArticleItem[] = [];

    constructor(private http: Http) {}

    getArticleItemList() {
        return this.http.get(this.urlToGetList)
            .map(
                (response: Response) => {
                    let articleDataList = response.json();

                    for (let articleData of articleDataList) {
                        let articleItem = new ArticleItem(articleData);
                        this.articleItemList.push(articleItem);
                    }

                    return this.articleItemList;
                }
            )
            .catch(
                (error: Response) => {
                    return Observable.throw(error.toString());
                }
            );
    }

    getArticleItem(index: number) {
        return this.articleItemList[index];
    }

    updateArticle(articleItem: ArticleItem) {
        const headers = new Headers({'Content-Type': 'application/json'});

        return this.http
            .put(this.urlToUpdate, articleItem, {headers: headers})
            .map(
                (response: Response) => {
                    return response.json();
                }
            )
            .catch(
                (error: Response) => {
                    return Observable.throw(error.statusText);
                }
            );
    }

    deleteArticle(articleItem: ArticleItem) {
        return this.http
            .delete(this.urlToDelete + '/' + articleItem.id)
            .map(
                (response: Response) => {
                    return response.json();
                }
            )
            .catch(
                (error: Response) => {
                    return Observable.throw(error.statusText);
                }
            );
    }

    getArticleById(id: number): ArticleItem|null {
        if (this.articleItemList.length < 1) {
            this.getArticleItemList();
        }

        let filteredArticleList: ArticleItem[];

        filteredArticleList = this.articleItemList.filter(function (articleItem: ArticleItem) {
console.log('getArticleById - articleItem', articleItem);
            if(articleItem.id === id) {
                return articleItem;
            }
        });

        return filteredArticleList[0];
    }
}