import {NgModule}             from '@angular/core';
import {RouterModule, Routes} from '@angular/router';


const routes: Routes = [
    {path: '', redirectTo: '/menu-list', pathMatch: 'full'}
];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})
export class AppMainRoutingModule {}
