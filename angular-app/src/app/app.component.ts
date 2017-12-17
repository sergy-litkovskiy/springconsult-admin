import { Component } from '@angular/core';

@Component({
  selector: 'adm-panel',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
    // loadedFeature = 'menu-list';
    loadedFeature = 'article-list';

    onNavigate(feature: string) {
        this.loadedFeature = feature;
    }
}
