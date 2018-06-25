import {NgModule} from '@angular/core';
import {MenuService} from "./menu.service";
import {MenuListComponent} from "./menu-list/menu-list.component";
import {MenuRoutingModule} from "./menu.routing.module";
import {CommonModule} from "@angular/common";
import {ActionToolsComponent} from "./menu-list/action-tools.component";
import {PopoverModule} from "ngx-popover";
import {NguiPopupModule} from "@ngui/popup";


@NgModule({
    declarations: [
        MenuListComponent,
        ActionToolsComponent
    ],
    imports: [
        CommonModule,
        MenuRoutingModule,
        PopoverModule,
        NguiPopupModule,
    ],
    providers: [
        MenuService
    ]
})
export class MenuModule {}
