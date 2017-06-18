import {Component, OnInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MenuItem} from "../menu-item.model";
import {MenuService} from "../menu.service";

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
    rows = [
        { name: 'Austin', gender: 'Male', company: 'Swimlane' },
        { name: 'Dany', gender: 'Male', company: 'KFC' },
        { name: 'Molly', gender: 'Female', company: 'Burger King' },
    ];
    columns = [
        { prop: 'name' },
        { name: 'Gender' },
        { name: 'Company' }
    ];

    constructor(
        private menuService: MenuService,
        private router: Router,
        private route: ActivatedRoute
    ) {
    }

    ngOnInit() {
        this.menuItemList = this.menuService.getMenuItemList();
    }

    onNewMenuItem() {
        this.router.navigate(['menu-new'], {relativeTo: this.route});
    }
}
