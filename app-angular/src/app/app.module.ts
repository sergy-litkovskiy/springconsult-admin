import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {FormsModule} from '@angular/forms';
import {HttpModule} from '@angular/http';
import {NgxDatatableModule} from '@swimlane/ngx-datatable';
import {AppRoutingModule} from './app.routing.module';

import {AppMainComponent} from './app.main.component';
import {AppMenuComponent} from './menu/menu-list/menu-list.component';
import {MenuService} from './menu/menu.service';
import {AppArticleComponent} from './blog/article-list/article-list.component';
import { NguiPopupModule } from '@ngui/popup';
// import {ConfirmDialogModule} from 'primeng/components/confirmdialog/confirmdialog';
// import { ModalModule } from 'angular2-modal';
// import { BootstrapModalModule } from 'angular2-modal/plugins/bootstrap';

import {APP_BASE_HREF} from '@angular/common';

@NgModule({
    declarations: [
        AppMainComponent,
        AppMenuComponent,
        AppArticleComponent
    ],
    imports: [
        BrowserModule,
        FormsModule,
        HttpModule,
        AppRoutingModule,
        NgxDatatableModule,
        NguiPopupModule
        // ,
        // ConfirmDialogModule
        // ModalModule.forRoot(),
        // BootstrapModalModule
    ],
    providers: [MenuService, {provide: APP_BASE_HREF, useValue: '/'}],
    bootstrap: [AppMainComponent]
})
export class AppModule {
}
