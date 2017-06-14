import {Component} from '@angular/core';

@Component({
    selector: 'adm-panel',
    templateUrl: '/app-angular/src/app/app.main.component.html'
    // ,
    // styleUrls: ['app.menu.css']
})
export class AppMainComponent {
    loadedFeature = 'menu-list';

    onNavigate(feature: string) {
        this.loadedFeature = feature;
    }
}
