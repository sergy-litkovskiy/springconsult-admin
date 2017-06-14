import {NgModule}             from '@angular/core';
import {RouterModule, Routes} from '@angular/router';

import {AppMenuComponent} from "./menu/menu-list/menu-list.component";

const routes: Routes = [
    {path: '', redirectTo: '/menu-list', pathMatch: 'full'},
    {path: 'menu-list', component: AppMenuComponent},
];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})

export class AppRoutingModule {
}