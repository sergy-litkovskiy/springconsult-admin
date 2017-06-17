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
                    [rows]="rows"
                    [columns]="columns">
            </ngx-datatable>
        </div>
    `,
    styleUrls: ['/app-angular/src/style.css'],
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
