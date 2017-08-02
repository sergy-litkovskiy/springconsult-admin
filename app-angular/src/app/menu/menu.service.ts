import {EventEmitter, Injectable} from '@angular/core';

import {MenuItem} from "./menu-item.model";
import {Observable} from "rxjs/Observable";
import {Http} from "@angular/http";
import "rxjs/add/operator/map";
import "rxjs/add/operator/catch";
import 'rxjs/add/observable/throw';

@Injectable()
export class MenuService {
    menuItemSelected = new EventEmitter<MenuItem>();

    private urlToGetList = '/menu/list';
    private menuItemList: MenuItem[] = [];

    constructor(private http: Http) {}

    getMenuItemList() {
        return this.http.get(this.urlToGetList)
            .map(
                (response: Response) => {
                    let menuDataList = response.json();

                    for (let menuData of menuDataList) {
                        let menuItem = new MenuItem(menuData);
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
