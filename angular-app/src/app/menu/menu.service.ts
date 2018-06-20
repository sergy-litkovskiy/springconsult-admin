import {Injectable} from '@angular/core';
import {MenuItem} from "./menu-item.model";
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs/Observable';
import 'rxjs/Rx';


@Injectable()
export class MenuService {
    private urlToGetList = '/menu/list';
    private menuItemList: any[] = [];

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

    getMenuItem(index: number) {
        return this.menuItemList[index];
    }
}
