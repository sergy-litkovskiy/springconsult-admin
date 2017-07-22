import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {HttpModule} from '@angular/http';
import {NgxDatatableModule} from '@swimlane/ngx-datatable';
import {AppRoutingModule} from './app.routing.module';

import {AppMainComponent} from './app.main.component';
import {AppMenuComponent} from './menu/menu-list/menu-list.component';
import {MenuService} from './menu/menu.service';
import {AppArticleListComponent} from './blog/article-list/article-list.component';
import {AppArticleItemComponent} from './blog/article-item/article-item.component';
import {NguiPopupModule} from '@ngui/popup';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {DateTimePickerModule} from 'ng-pick-datetime';

import {APP_BASE_HREF} from '@angular/common';
import {ArticleService} from "./blog/article.service";

@NgModule({
    declarations: [
        AppMainComponent,
        AppMenuComponent,
        AppArticleListComponent,
        AppArticleItemComponent
    ],
    imports: [
        BrowserModule,
        FormsModule,
        HttpModule,
        AppRoutingModule,
        NgxDatatableModule,
        NguiPopupModule,
        FormsModule,
        ReactiveFormsModule,
        DateTimePickerModule
    ],
    providers: [
        MenuService,
        ArticleService,
        {
            provide: APP_BASE_HREF,
            useValue: '/'
        }
    ],
    bootstrap: [AppMainComponent]
})
export class AppModule {
}
