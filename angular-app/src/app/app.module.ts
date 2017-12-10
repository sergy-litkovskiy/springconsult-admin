import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';

import {AppComponent} from './app.component';
import {APP_BASE_HREF} from "@angular/common";
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {HttpModule} from '@angular/http';
import {DateTimePickerModule} from 'ng-pick-datetime';
import {NguiPopupModule} from '@ngui/popup';
import {AppMainRoutingModule} from './app.routing.module';
// import {DataTableModule} from 'angular-4-data-table';


@NgModule({
    declarations: [
        AppComponent
    ],
    imports: [
        BrowserModule,
        FormsModule,
        HttpModule,
        AppMainRoutingModule,
        // DataTableModule,
        NguiPopupModule,
        FormsModule,
        ReactiveFormsModule,
        DateTimePickerModule
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
