import {EventEmitter, Injectable} from '@angular/core';

import {MenuItem} from "./menu-item.model";

@Injectable()
export class MenuService {
    menuItemSelected = new EventEmitter<MenuItem>();

    private menuItemList: MenuItem[] = [
        new MenuItem(
            'Tasty Schnitzel',
            'A super-tasty Schnitzel - just awesome!',
            'https://upload.wikimedia.org/wikipedia/commons/7/72/Schnitzel.JPG'),
        new MenuItem('Big Fat Burger',
            'What else you need to say?',
            'https://upload.wikimedia.org/wikipedia/commons/b/be/Burger_King_Angus_Bacon_%26_Cheese_Steak_Burger.jpg')
    ];

    constructor() {
    }

    getMenuItemList() {
        return this.menuItemList.slice();
    }

    getMenuItem(index: number) {
        return this.menuItemList[index];
    }
}
