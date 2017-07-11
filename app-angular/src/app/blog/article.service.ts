import {EventEmitter, Injectable} from '@angular/core';
import {Http, Response, Headers} from '@angular/http';
import {Observable} from 'rxjs/Observable';
import "rxjs/add/operator/map";
import "rxjs/add/operator/catch";
import 'rxjs/add/observable/throw';

import {ArticleItem} from "./article-item.model";

@Injectable()
export class ArticleService {
    private articleListUrl = '/article/list';  // URL to web API
    private articleUpdateUrl = '/article/update';  // URL to web API

    articleItemSelected = new EventEmitter<ArticleItem>();

    private articleItemList: ArticleItem[] = [];

    constructor(private http: Http) {}

    getArticleItemList() {
        return this.http.get(this.articleListUrl)
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
            .put(this.articleUpdateUrl, articleItem, {headers: headers})
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
}
