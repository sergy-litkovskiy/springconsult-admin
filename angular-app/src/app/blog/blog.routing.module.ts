import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ArticleListComponent} from "./article-list/article-list.component";
import {ArticleItemComponent} from "./article-item/article-item.component";


const menuRoutes: Routes = [
    {path: 'article-list', component: ArticleListComponent},
    {path: 'article-edit/:id', component: ArticleItemComponent},
    {path: 'article-edit', component: ArticleItemComponent}
];

@NgModule({
    imports: [RouterModule.forChild(menuRoutes)],
    exports: [RouterModule]
})
export class BlogRoutingModule {}
