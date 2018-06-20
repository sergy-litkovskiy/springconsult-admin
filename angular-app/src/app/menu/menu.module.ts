import {NgModule} from '@angular/core';
import {MenuService} from "./menu.service";
import {MenuListComponent} from "./menu-list/menu-list.component";
import {MenuRoutingModule} from "./menu.routing.module";
import {CommonModule} from "@angular/common";


@NgModule({
    declarations: [
        MenuListComponent
    ],
    imports: [
        CommonModule,
        MenuRoutingModule
    ],
    providers: [
        MenuService
    ]
})
export class MenuModule {}
