import {Component, OnInit} from '@angular/core';
import {MenuService} from "../menu.service";
import {Subscription} from "rxjs/Subscription";
import 'rxjs/Rx';
import {MatDialog, MatDialogRef} from "@angular/material";
import {OverlayInfoComponent} from "../../common/overlay.info.component";

@Component({
    selector: 'menu-list',
    templateUrl: './menu-list.component.html'
})
export class MenuListComponent implements OnInit {
    menuItemList: any[];
    nodes;

    private menuListSubscription: Subscription;
    dialogRef: MatDialogRef<OverlayInfoComponent>;

    constructor(private menuService: MenuService, private dialog: MatDialog) {
        this.menuService.errorMessage.subscribe(
            (error: string) => {
                this.showErrorPopup(error);
            }
        );
    }

    ngOnInit() {
        this.menuItemList = this.menuService.getMenuItemList();

        if (!this.menuItemList.length) {
            this.menuListSubscription = this.menuService.getMenuItemListFromServer()
                .subscribe(
                    (menuDataList: any[]) => {
                        this.menuItemList = [...menuDataList];
                    },
                    (error) => {
                        this.showErrorPopup(error);
                    }
                );
        }
    }

    private showErrorPopup(error: string) {
        this.dialogRef = this.dialog.open(OverlayInfoComponent, {
            width: '400px',
            data: { message: error }
        });
    }

    ngOnDestroy() {
console.log('menu LIST - ON DESTROY');
        if (this.menuListSubscription != undefined) {
            this.menuListSubscription.unsubscribe();
        }
    }
}
