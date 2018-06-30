import {NgModule} from '@angular/core';
import {PopoverModule} from "ngx-popover";
import {ArticleService} from "./article.service";
import {ArticleListComponent} from "./article-list/article-list.component";
import {ArticleItemComponent} from "./article-item/article-item.component";
import {AgGridModule, BaseComponentFactory} from "ag-grid-angular";
import {BlogRoutingModule} from "./blog.routing.module";
import {NguiPopupModule} from "@ngui/popup";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {DateTimePickerModule} from "ng-pick-datetime";
import {CKEditorComponent} from "ng2-ckeditor/lib/src/ckeditor.component";
import {AppComponentCkeditorHelper} from "../app.component.ckeditor.helper";
import {MenuService} from "../menu/menu.service";
import {CommonModule} from "@angular/common";
import {ArticleListActionToolsComponent} from "./article-list/article-list.action-tools.component";


@NgModule({
    declarations: [
        ArticleListComponent,
        ArticleItemComponent,
        ArticleListActionToolsComponent,
        CKEditorComponent
    ],
    imports: [
        CommonModule,
        BlogRoutingModule,
        NguiPopupModule,//TODO: remove
        FormsModule,
        PopoverModule,
        ReactiveFormsModule,
        DateTimePickerModule,
        AgGridModule.withComponents([ArticleListActionToolsComponent])
    ],
    providers: [
        BaseComponentFactory,
        AppComponentCkeditorHelper,
        ArticleService,
        MenuService
    ]
})
export class BlogModule {}
