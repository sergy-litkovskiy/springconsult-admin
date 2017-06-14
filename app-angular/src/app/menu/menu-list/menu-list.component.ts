import {Component, OnInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MenuItem} from "../menu-item.model";
import {MenuService} from "../menu.service";

@Component({
    selector: 'menu-list',
    templateUrl: '/app-angular/src/app/menu/menu-list.component.html'
    // styleUrls: ['app.menu.css']
})

export class AppMenuComponent implements OnInit {
    menuItemList: MenuItem[];

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
