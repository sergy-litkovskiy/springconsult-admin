import {EventEmitter, Injectable} from '@angular/core';
import {MenuItem} from "./menu-item.model";
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Observable} from 'rxjs/Observable';
import {throwError} from 'rxjs';
import {map, catchError} from 'rxjs/operators';


@Injectable()
export class MenuService {
    private urlToGetList = '/menu/list';
    private urlToGetMenuItem = '/menu/';
    private urlToUpdate = '/menu/update';
    private urlToAdd = '/menu/add';
    private urlToDelete = '/menu/delete';
    private headers = new HttpHeaders({'Content-Type': 'application/json'});
    private menuItemList: any[] = [];

    menuItemDeleted = new EventEmitter<MenuItem>();
    errorMessage = new EventEmitter<string>();

    constructor(private http: HttpClient) {}

    getMenuItemList() {
        return this.menuItemList;
    }

    makeMenuChildList(menuChildList) {
        let childList: MenuItem[] = [];

        if (menuChildList == undefined || !menuChildList.length) {
            return [];
        }

        for (let menuData of menuChildList) {
            childList.push(new MenuItem(menuData));
        }

        return childList;
    }

    getMenuItemListFromServer() {
        return this.http
            .get<any[]>(this.urlToGetList, {observe: 'body', responseType: 'json'})
            .pipe(
                map(
                    (menuDataList) => {
                        for (let menuData of menuDataList) {
                            let menuItem = {
                                parent: new MenuItem(menuData['parent']),
                                childList: this.makeMenuChildList(menuData['childList'])
                            };

                            this.menuItemList.push(menuItem);
                        }

                        return this.menuItemList;
                    }
                ),
                catchError(
                    (error) => {
                        return Observable.throw(error.toString());
                    }
                )
            );
    }

    getArticleItemByIdFromServer(id: number) {
        let link = this.urlToGetMenuItem + id;

        return this.http.get<MenuItem>(link, {observe: 'body', responseType: 'json'})
            .pipe(
                map(
                    (menuData) => {
                        return new MenuItem(menuData);
                    }
                ),
                catchError(
                    (error) => {
                        return throwError(error.toString());
                    }
                )
        );
    }

    addArticle(menuItem: MenuItem) {
        return this.http
            .post(this.urlToAdd, menuItem, {headers: this.headers})
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

    deleteArticle(menuItem: MenuItem) {
        return this.http
            .delete(this.urlToDelete + '/' + menuItem.id)
            .pipe(
                map(
                    (response) => {
                        return menuItem;
                    }
                ),
                catchError(
                    (error) => {
                        return throwError(error.statusText);
                    }
                )
            );
    }

    updateMenu(menuItem: MenuItem) {
        return this.http
            .put(this.urlToUpdate, menuItem, {headers: this.headers})
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

    updateMenuItemInList(menuItem: MenuItem) {
        let index = this.menuItemList.indexOf(menuItem);
        this.menuItemList[index] = menuItem;
    }

    addMenuItemToList(menuItem: MenuItem) {
        this.menuItemList.push(menuItem);
    }

    getMenuItem(index: number) {
        return this.menuItemList[index];
    }
}
