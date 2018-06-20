import {NgModule} from '@angular/core';
import {PopoverModule} from "ngx-popover";
import {ArticleListActionToolRendererComponent} from "./article-list/article-list.action-tool-renderer.component";
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


@NgModule({
    declarations: [
        ArticleListComponent,
        ArticleItemComponent,
        ArticleListActionToolRendererComponent,
        CKEditorComponent
    ],
    imports: [
        CommonModule,
        BlogRoutingModule,
        NguiPopupModule,
        FormsModule,
        PopoverModule,
        ReactiveFormsModule,
        DateTimePickerModule,
        AgGridModule.withComponents([ArticleListActionToolRendererComponent])
    ],
    providers: [
        BaseComponentFactory,
        AppComponentCkeditorHelper,
        ArticleService,
        MenuService
    ]
})
export class BlogModule {}
