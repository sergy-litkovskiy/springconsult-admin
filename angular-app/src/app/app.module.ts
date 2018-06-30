import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {AppComponent} from './app.component';
import {APP_BASE_HREF} from "@angular/common";
import {HttpClientModule} from '@angular/common/http';
import {AppMainRoutingModule} from './app.routing.module';
import {MenuModule} from "./menu/menu.module";
import {BlogModule} from "./blog/blog.module";
import {MatDialogModule} from "@angular/material";
import {ModalErrorMessageComponent} from "./common/modal-error-message.component";
import {BrowserAnimationsModule} from "@angular/platform-browser/animations";

@NgModule({
    declarations: [
        AppComponent,
        ModalErrorMessageComponent
    ],
    imports: [
        BrowserModule,
        HttpClientModule,
        MatDialogModule,
        AppMainRoutingModule,
        BlogModule,
        MenuModule,
        MatDialogModule,
        BrowserAnimationsModule
    ],
    entryComponents: [
        ModalErrorMessageComponent
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
