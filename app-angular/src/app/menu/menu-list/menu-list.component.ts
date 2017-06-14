import {Component, OnInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MenuItem} from "../menu-item.model";
import {MenuService} from "../menu.service";

@Component({
    selector: 'menu-list',
    // templateUrl: '/app-angular/src/app/menu/menu-list.component.html'
    template: `
        <div class="content-wrapper" style="width: 100%; height: 50px; border: solid 1px red">
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-success" (click)="onNewMenuItem()">New Item</button>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-12">
                    <!--<app-menu-item-->
                            <!--*ngFor="let menuItem of menuItemList; let i = index"-->
                            <!--[menuItem]="menuItem"-->
                            <!--[index]="i"></app-menu-item>-->
                </div>
            </div>
        </div>`
    // ,
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
