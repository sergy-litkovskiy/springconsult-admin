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
import {ArticleListComponent} from "./blog/article-list/article-list.component";
import {ArticleItemComponent} from "./blog/article-item/article-item.component";
import {MenuService} from "./menu/menu.service";
import {MenuListComponent} from "./menu/menu-list/menu-list.component";
import {CKEditorComponent} from "ng2-ckeditor";
import {AppComponentCkeditorHelper} from "./app.component.ckeditor.helper";
import {ArticleListActionToolRendererComponent} from "./blog/article-list/article-list.action-tool-renderer.component";


@NgModule({
    declarations: [
        AppComponent,
        ArticleListComponent,
        ArticleItemComponent,
        MenuListComponent,
        CKEditorComponent,
        ArticleListActionToolRendererComponent
    ],
    exports: [
        CKEditorComponent
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
        AgGridModule.withComponents([ArticleListActionToolRendererComponent])
    ],
    providers: [
        BaseComponentFactory,
        ArticleService,
        MenuService,
        {
            provide: APP_BASE_HREF,
            useValue: '/'
        },
        AppComponentCkeditorHelper
    ],
    bootstrap: [AppComponent]
})
export class AppModule {
}
