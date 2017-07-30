import {NgModule}             from '@angular/core';
import {RouterModule, Routes} from '@angular/router';

import {AppMenuComponent} from "./menu/menu-list/menu-list.component";
import {AppArticleListComponent} from "./blog/article-list/article-list.component";
import {AppArticleItemComponent} from "./blog/article-item/article-item.component";

const routes: Routes = [
    {path: '', redirectTo: '/menu-list', pathMatch: 'full'},
    {path: 'menu-list', component: AppMenuComponent},
    {path: 'article-list', component: AppArticleListComponent},
    {path: 'article-edit/:id', component: AppArticleItemComponent},
    {path: 'article-edit', component: AppArticleItemComponent}
];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})

export class AppRoutingModule {
}