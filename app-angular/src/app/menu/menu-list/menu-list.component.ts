import {Component, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MenuItemOld} from "../menu-item.model";
import {MenuServiceOld} from "../menu.service";
import "rxjs/add/operator/map";
import "rxjs/add/operator/catch";
import 'rxjs/add/observable/throw';
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {Subscribable} from "rxjs/Observable";
import {Subscription} from "rxjs/Subscription";

@Component({
    selector: 'menu-list',
    // templateUrl: './menu-list.component.html',
    template: `
        <div>
            <ngx-datatable
                class="material ngx-datatable fixed-header fixed-row scroll-vertical scroll-horz"
                [rows]="rows"
                [columns]="columns"
                [columnMode]="'force'"
                [headerHeight]="50"
                [footerHeight]="50"
                [rowHeight]="'auto'"
                [sortType]="'multi'"
                [limit]="10">
            </ngx-datatable>
        </div>
    `
})

export class AppMenuComponent implements OnInit {
    menuItemList: MenuItemOld[];
    rows: any[];
    columns: any[];
    private menuListSubscription: Subscription;

    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(
        private menuService: MenuServiceOld,
        private router: Router,
        private route: ActivatedRoute
    ) {
    }

    ngOnInit() {
        this.menuItemList = this.menuService.getMenuItemList();

        if (!this.menuItemList.length) {
            this.menuListSubscription = this.menuService.getMenuItemListFromService()
                .subscribe(
                    (menuItems: MenuItemOld[]) => {
                        this.menuItemList = menuItems;
                    },
                    (error) => {
                        // this.showErrorPopup(error);
                    }
                );
        }
    }

    ngOnDestroy() {
console.log('menu LIST - ON DESTROY');

        if (this.menuListSubscription != undefined) {
            this.menuListSubscription.unsubscribe();
        }
    }

    onNewMenuItem() {
        this.router.navigate(['menu-new'], {relativeTo: this.route});
    }
}
