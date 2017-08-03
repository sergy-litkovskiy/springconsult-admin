import {Component, OnInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MenuItem} from "../menu-item.model";
import {MenuService} from "../menu.service";
import {Subscription} from "rxjs/Subscription";
import "rxjs/add/operator/map";
import "rxjs/add/operator/catch";
import 'rxjs/add/observable/throw';

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
    `,
    providers: [MenuService]
})

export class AppMenuComponent implements OnInit {
    menuItemList: MenuItem[];
    tempMenuItemList: MenuItem[] = [];
    private menuListSubscription: Subscription;

    constructor(
        private menuService: MenuService,
        private router: Router,
        private route: ActivatedRoute
    ) {
    }

    ngOnInit() {
        this.menuListSubscription = this.menuService.getMenuItemList()
            .subscribe(
                (menuItems: MenuItem[]) => {
                    this.menuItemList = menuItems;
                    this.tempMenuItemList = [...menuItems];
                },
                (error) => {
                    // this.showErrorPopup(error);
                }
            );
    }

    ngOnDestroy() {
console.log('menu LIST - ON DESTROY');
        this.menuListSubscription.unsubscribe();
    }

    onNewMenuItem() {
        this.router.navigate(['menu-new'], {relativeTo: this.route});
    }
}
