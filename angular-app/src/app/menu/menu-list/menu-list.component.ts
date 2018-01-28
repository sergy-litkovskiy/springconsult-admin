import {Component, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MenuItem} from "../menu-item.model";
import {MenuService} from "../menu.service";
import "rxjs/add/operator/map";
import "rxjs/add/operator/catch";
import 'rxjs/add/observable/throw';
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {Subscribable} from "rxjs/Observable";
import {Subscription} from "rxjs/Subscription";
import {ITreeOptions, ITreeState} from "angular-tree-component";
import {ITreeNode} from "angular-tree-component/dist/defs/api";



@Component({
    selector: 'menu-list',
    templateUrl: './menu-list.component.html'
})

export class MenuListComponent implements OnInit {
    menuItemList: MenuItem[];
    tempItemList: MenuItem[] = [];
    options: ITreeOptions;
    state: ITreeState;
    nodes;

    private menuListSubscription: Subscription;


    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(private menuService: MenuService,
                private router: Router,
                private route: ActivatedRoute) {
        // this.frameworkComponents = {
        //     articleListActionToolRenderer: ArticleListActionToolRendererComponent
        // };


        // this.articleService.articleItemListDeleted.subscribe(
        //     (articleItem: ArticleItem) => {
        //         this.articleItemList = this.articleItemList.filter(obj => obj !== articleItem);
        //         this.renderGrid();
        //     }
        // );
        //
        // this.articleService.errorMessage.subscribe(
        //     (error: string) => {
        //         this.showErrorPopup(error);
        //     }
        // );
        // this.state = {
        //     expandedNodeIds: {},
        //     hiddenNodeIds: {},
        //     activeNodeIds: {}
        // };
    }

    ngOnInit() {
        this.menuItemList = this.menuService.getMenuItemList();

        if (!this.menuItemList.length) {
            this.menuListSubscription = this.menuService.getMenuItemListFromServer()
                .subscribe(
                    (menuList: MenuItem[]) => {
                        this.menuItemList = menuList;
                        this.tempItemList = [...menuList];

                        this.renderTree();
                    },
                    (error) => {
                        this.showErrorPopup(error);
                    }
                );
        } else {
            this.renderTree();
        }
    }

    private renderTree()
    {
        this.nodes = [
            {
                id: 1,
                name: 'root1',
                description: 'some description',
                children: [
                    { name: 'child1-1', id: 11, description: 'some description' },
                    { name: 'child1-2', id: 22, description: 'some description' }
                ]
            },
            {
                id: 2,
                name: 'root2',
                description: 'some description',
                children: [
                    { name: 'child2-1', id: 111, description: 'some description' },
                    { name: 'child2-2', id: 222, description: 'some description' }
                ]
            }
        ];

        this.options = {
            allowDrag: (node: ITreeNode) => node.isLeaf
        };
    }

    onMoveNode($event) {
        console.log('parent-children', $event.to.parent.children);
        console.log('node', $event.node);

    }

    showErrorPopup(error: string) {
        this.popup.open(NguiMessagePopupComponent, {
            classNames: 'small',
            title: 'ERROR',
            message: error,
            buttons: {
                CLOSE: () => {
                    this.popup.close();
                }
            }
        });
    }

    ngOnDestroy() {
        console.log('menu LIST - ON DESTROY');
        if (this.menuListSubscription != undefined) {
            this.menuListSubscription.unsubscribe();
        }
    }
}
