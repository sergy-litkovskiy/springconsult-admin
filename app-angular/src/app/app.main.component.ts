import {Component} from '@angular/core';

@Component({
    selector: 'adm-panel',
    template: `
        <aside class="main-sidebar">
            <section class="sidebar">
                <ul class="sidebar-menu">
                    <li>
                        <a routerLink="/menu-list" routerLinkActive="active">
                            <span>Site menu</span>
                        </a>
                    </li>
                    <li>
                        <a routerLink="/article-list">
                            <span>Articles</span>
                        </a>
                    </li>
                </ul>
            </section>
        </aside>
        <div class="content-wrapper">
            <section class="content">
                <div class="row">
                    <router-outlet></router-outlet>
                </div>
            </section>
        </div>
    `
})
export class AppMainComponent {
    loadedFeature = 'menu-list';

    onNavigate(feature: string) {
        this.loadedFeature = feature;
    }
}
