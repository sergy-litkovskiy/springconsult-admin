import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {HttpModule} from '@angular/http';
import {NgxDatatableModule} from '@swimlane/ngx-datatable';
import {AppRoutingModule} from './app.routing.module';

import {AppMainComponent} from './app.main.component';
import {AppMenuComponent} from './menu/menu-list/menu-list.component';
import {MenuServiceOld} from './menu/menu.service';
import {AppArticleListComponent} from './blog/article-list/article-list.component';
import {AppArticleItemComponent} from './blog/article-item/article-item.component';
import {NguiPopupModule} from '@ngui/popup';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {DateTimePickerModule} from 'ng-pick-datetime';
import { CKEditorModule } from 'ng2-ckeditor';
// import { FileSelectDirective, FileDropDirective } from 'ng2-file-upload';

import {APP_BASE_HREF} from '@angular/common';
import {ArticleServiceOld} from "./blog/article.service";
import {AppMainCkeditorHelper} from "./app.main.ckeditor.helper";

@NgModule({
    declarations: [
        AppMainComponent,
        AppMenuComponent,
        AppArticleListComponent,
        AppArticleItemComponent
        // ,
        // FileSelectDirective,
        // FileDropDirective
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
        DateTimePickerModule,
        CKEditorModule,
    ],
    providers: [
        MenuServiceOld,
        ArticleServiceOld,
        {
            provide: APP_BASE_HREF,
            useValue: '/'
        },
        AppMainCkeditorHelper
    ],
    bootstrap: [AppMainComponent]
})
export class AppModule {
}
