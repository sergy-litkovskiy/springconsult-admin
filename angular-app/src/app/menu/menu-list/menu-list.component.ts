import {Component, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MenuItem} from "../menu-item.model";
import {MenuService} from "../menu.service";
import 'rxjs/Rx';
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {Subscribable} from "rxjs/Observable";
import {Subscription} from "rxjs/Subscription";


@Component({
    selector: 'menu-list',
    templateUrl: './menu-list-parent.component.html'
})
export class MenuListComponent implements OnInit {
    menuItemList: any[];
    tempItemList: any[] = [];
    nodes;

    private menuListSubscription: Subscription;

    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(private menuService: MenuService,
                private router: Router,
                private route: ActivatedRoute) {
    }

    ngOnInit() {
        this.menuItemList = this.menuService.getMenuItemList();

        if (!this.menuItemList.length) {
            this.menuListSubscription = this.menuService.getMenuItemListFromServer()
                .subscribe(
                    (menuDataList: any[]) => {
                        this.menuItemList = menuDataList;
                        this.tempItemList = [...menuDataList];

                        this.renderTree();
                    },
                    (error) => {
                        this.showErrorPopup(error);
                    }
                );
        } else {
            this.renderTree();
        }
    }

    private renderTree()
    {
console.log('renderTree - this.menuItemList', this.menuItemList);
    }

    showErrorPopup(error: string) {
        this.popup.open(NguiMessagePopupComponent, {
            classNames: 'small',
            title: 'ERROR',
            message: error,
            buttons: {
                CLOSE: () => {
                    this.popup.close();
                }
            }
        });
    }

    ngOnDestroy() {
console.log('menu LIST - ON DESTROY');
        if (this.menuListSubscription != undefined) {
            this.menuListSubscription.unsubscribe();
        }
    }
}
