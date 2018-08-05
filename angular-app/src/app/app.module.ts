import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {AppComponent} from './app.component';
import {APP_BASE_HREF} from "@angular/common";
import {HttpClientModule} from '@angular/common/http';
import {AppMainRoutingModule} from './app.routing.module';
import {MenuModule} from "./menu/menu.module";
import {BlogModule} from "./blog/blog.module";
import {MatDialogModule} from "@angular/material";
import {BrowserAnimationsModule} from "@angular/platform-browser/animations";
import {OverlayModule} from "@angular/cdk/overlay";
import {OverlayInfoComponent} from "./common/overlay.info.component";

@NgModule({
    declarations: [
        AppComponent,
        OverlayInfoComponent
    ],
    imports: [
        BrowserModule,
        HttpClientModule,
        AppMainRoutingModule,
        BlogModule,
        MenuModule,
        MatDialogModule,
        OverlayModule,
        BrowserAnimationsModule
    ],
    entryComponents: [
        OverlayInfoComponent
    ],
    providers: [
        {
            provide: APP_BASE_HREF,
            useValue: '/'
        }
    ],
    bootstrap: [AppComponent]
})
export class AppModule {
}
