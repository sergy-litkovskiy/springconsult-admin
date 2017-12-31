import {Injectable} from '@angular/core';

import {MenuItem} from "./menu-item.model";
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs/Observable';
import "rxjs/add/operator/map";
import "rxjs/add/operator/catch";
import 'rxjs/add/observable/throw';


@Injectable()
export class MenuService {
    private urlToGetList = '/menu/list';
    private menuItemList: MenuItem[] = [];

    constructor(private http: HttpClient) {}

    getMenuItemList() {
        return this.menuItemList;
    }

    getMenuItemListFromService() {
        return this.http.get<MenuItem[]>(this.urlToGetList, {observe: 'body', responseType: 'json'})
            .map(
                (menuDataList) => {
                    for (let menuData of menuDataList) {
                        let menuItem = new MenuItem(menuData);
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

    getMenuItem(index: number) {
        return this.menuItemList[index];
    }
}
