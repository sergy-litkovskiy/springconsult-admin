import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {FormsModule} from '@angular/forms';
import {HttpModule} from '@angular/http';

import {AppMainComponent} from './app.main.component';
import {AppRoutingModule} from "./app.routing.module";
import {AppMenuComponent} from "./menu/menu-list/menu-list.component";
import {MenuService} from "./menu/menu.service";

import {APP_BASE_HREF} from '@angular/common';

@NgModule({
    declarations: [
        AppMainComponent,
        AppMenuComponent
    ],
    imports: [
        BrowserModule,
        FormsModule,
        HttpModule,
        AppRoutingModule
    ],
    providers: [MenuService, {provide: APP_BASE_HREF, useValue : '/' }],
    bootstrap: [AppMainComponent]
})
export class AppModule {
}
