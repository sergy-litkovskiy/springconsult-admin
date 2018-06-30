import {EventEmitter, Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {throwError} from 'rxjs';
import {map, catchError} from 'rxjs/operators';
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

    articleItemDeleted = new EventEmitter<ArticleItem>();
    errorMessage = new EventEmitter<string>();

    constructor(private http: HttpClient) {}

    getArticleItemList() {
        return this.articleItemList;
    }

    getArticleItemListFromServer() {
        return this.http
            .get<ArticleItem[]>(this.urlToGetList, {observe: 'body', responseType: 'json'})
            .pipe(
                map(
                    (articleDataList) => {
                        for (let articleData of articleDataList) {
                            let articleItem = new ArticleItem(articleData);
                            this.articleItemList.push(articleItem);
                        }
                        return this.articleItemList;
                    }
                ),
                catchError(
                    (error) => {
                        return throwError(error.toString());
                    }
                )
            );
    }

    getArticleItemByIdFromServer(id: number) {
        let link = this.urlToGetArticleItem + id;

        return this.http
            .get<ArticleItem>(link, {observe: 'body', responseType: 'json'})
            .pipe(
                map(
                    (articleData) => {
                        return new ArticleItem(articleData);
                    }
                ),
                catchError(
                    (error) => {
                        return throwError(error.toString());
                    }
                )
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
            .pipe(
                map(
                    (response) => {
                        return response;
                    }
                ),
                catchError(
                    (error) => {
                        return throwError(error.statusText);
                    }
                )
            );
    }

    addArticle(articleItem: ArticleItem) {
        return this.http
            .post(this.urlToAdd, articleItem, {headers: this.headers})
            .pipe(
                map(
                    (response) => {
                        return response;
                    }
                ),
                catchError(
                    (error) => {
                        return throwError(error.statusText);
                    }
                )
            );
    }

    deleteArticle(articleItem: ArticleItem) {
        return this.http
            .delete(this.urlToDelete + '/' + articleItem.id)
            .pipe(
                map(
                    (response) => {
                        return articleItem;
                    }
                ),
                catchError(
                    (error) => {
                        return throwError(error.statusText);
                    }
                )
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

    removeArticleItemFromList(articleItem: ArticleItem) {
        this.articleItemDeleted.emit(articleItem);
    }
}
