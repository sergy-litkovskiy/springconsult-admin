import {EventEmitter, Injectable} from '@angular/core';
import {MenuItem} from "./menu-item.model";
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Observable} from 'rxjs/Observable';
import 'rxjs/Rx';


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
        return this.http.get<any[]>(this.urlToGetList, {observe: 'body', responseType: 'json'})
            .map(
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
            )
            .catch(
                (error) => {
                    return Observable.throw(error.toString());
                }
            );
    }

    getArticleItemByIdFromServer(id: number) {
        let link = this.urlToGetMenuItem + id;

        return this.http.get<MenuItem>(link, {observe: 'body', responseType: 'json'})
            .map(
                (menuData) => {
                    return new MenuItem(menuData);
                }
            )
            .catch(
                (error) => {
                    return Observable.throw(error.toString());
                }
            );
    }

    addArticle(menuItem: MenuItem) {
        return this.http
            .post(this.urlToAdd, menuItem, {headers: this.headers})
            .map(
                (response) => {
                    return response;
                }
            )
            .catch(
                (error) => {
                    return Observable.throw(error.statusText);
                }
            );
    }

    deleteArticle(menuItem: MenuItem) {
        return this.http
            .delete(this.urlToDelete + '/' + menuItem.id)
            .map(
                (response) => {
                    return menuItem;
                }
            )
            .catch(
                (error) => {
                    return Observable.throw(error.statusText);
                }
            );
    }

    updateMenu(menuItem: MenuItem) {
        return this.http
            .put(this.urlToUpdate, menuItem, {headers: this.headers})
            .map(
                (response) => {
                    return response;
                }
            )
            .catch(
                (error) => {
                    return Observable.throw(error.statusText);
                }
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
