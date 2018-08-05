import {NgModule} from '@angular/core';
import {MenuService} from "./menu.service";
import {MenuListComponent} from "./menu-list/menu-list.component";
import {MenuRoutingModule} from "./menu.routing.module";
import {CommonModule} from "@angular/common";
import {PopoverModule} from "ngx-popover";
import {NguiPopupModule} from "@ngui/popup";
import {MenuListActionToolsComponent} from "./menu-list/menu-list.action-tools.component";
import {OverlayModule} from "@angular/cdk/overlay";


@NgModule({
    declarations: [
        MenuListComponent,
        MenuListActionToolsComponent
    ],
    imports: [
        CommonModule,
        MenuRoutingModule,
        PopoverModule,
        NguiPopupModule,
        OverlayModule
    ],
    providers: [
        MenuService
    ]
})
export class MenuModule {}
