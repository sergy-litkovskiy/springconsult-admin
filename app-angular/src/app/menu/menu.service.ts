import {Injectable} from '@angular/core';

import {MenuItemOld} from "./menu-item.model";
import {Http, Response, Headers} from '@angular/http';
import {Observable} from 'rxjs/Observable';
import "rxjs/add/operator/map";
import "rxjs/add/operator/catch";
import 'rxjs/add/observable/throw';


@Injectable()
export class MenuServiceOld {
    private urlToGetList = '/menu/list';
    private menuItemList: MenuItemOld[] = [];

    constructor(private http: Http) {}

    getMenuItemList() {
        return this.menuItemList;
    }

    getMenuItemListFromService() {
        return this.http.get(this.urlToGetList)
            .map(
                (response: Response) => {
                    let menuDataList = response.json();

                    for (let menuData of menuDataList) {
                        let menuItem = new MenuItemOld(menuData);
                        this.menuItemList.push(menuItem);
                    }

                    return this.menuItemList;
                }
            )
            .catch(
                (error: Response) => {
                    return Observable.throw(error.toString());
                }
            );
    }

    getMenuItem(index: number) {
        return this.menuItemList[index];
    }
}
