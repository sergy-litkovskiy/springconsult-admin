import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';

import {AppComponent} from './app.component';
import {APP_BASE_HREF} from "@angular/common";
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {HttpClientModule} from '@angular/common/http';
import {DateTimePickerModule} from 'ng-pick-datetime';
import {NguiPopupModule} from '@ngui/popup';
import {AppMainRoutingModule} from './app.routing.module';
import {AgGridModule, BaseComponentFactory} from 'ag-grid-angular/main';
import {ArticleService} from "./blog/article.service";
import {AppArticleListComponent} from "./blog/article-list/article-list.component";


@NgModule({
    declarations: [
        AppComponent,
        AppArticleListComponent
    ],
    imports: [
        BrowserModule,
        FormsModule,
        HttpClientModule,
        AppMainRoutingModule,
        NguiPopupModule,
        FormsModule,
        ReactiveFormsModule,
        DateTimePickerModule,
        AgGridModule
    ],
    providers: [
        BaseComponentFactory,
        ArticleService,
        {
            provide: APP_BASE_HREF,
            useValue: '/'
        }
    ],
    bootstrap: [AppComponent]
})
export class AppModule {
}
