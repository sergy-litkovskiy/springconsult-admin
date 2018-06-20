import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {MenuListComponent} from "./menu-list/menu-list.component";

const menuRoutes: Routes = [
    {path: 'menu-list', component: MenuListComponent},
];

@NgModule({
    imports: [RouterModule.forChild(menuRoutes)],
    exports: [RouterModule]
})
export class MenuRoutingModule {}
