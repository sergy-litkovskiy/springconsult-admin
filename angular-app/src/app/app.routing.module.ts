import {NgModule}             from '@angular/core';
import {RouterModule, Routes} from '@angular/router';

import {MenuListComponent} from "./menu/menu-list/menu-list.component";
import {ArticleListComponent} from "./blog/article-list/article-list.component";
import {ArticleItemComponent} from "./blog/article-item/article-item.component";

const routes: Routes = [
    {path: '', redirectTo: '/article-list', pathMatch: 'full'},
    {path: 'menu-list', component: MenuListComponent},
    {path: 'article-list', component: ArticleListComponent},
    {path: 'article-edit/:id', component: ArticleItemComponent},
    {path: 'article-edit', component: ArticleItemComponent}
];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})

export class AppMainRoutingModule {
}