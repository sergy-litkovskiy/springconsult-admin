import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {AppComponent} from './app.component';
import {APP_BASE_HREF} from "@angular/common";
import {HttpClientModule} from '@angular/common/http';
import {AppMainRoutingModule} from './app.routing.module';
import {MenuModule} from "./menu/menu.module";
import {BlogModule} from "./blog/blog.module";


@NgModule({
    declarations: [
        AppComponent
    ],
    imports: [
        BrowserModule,
        HttpClientModule,
        AppMainRoutingModule,
        BlogModule,
        MenuModule
    ],
    providers: [
        {
            provide: APP_BASE_HREF,
            useValue: '/'
        }
    ],
    bootstrap: [AppComponent]
})
export class AppModule {}
