import {Component, OnInit} from '@angular/core';
import {MenuService} from "../menu.service";
import {Subscription} from "rxjs/Subscription";
import {MatDialog} from "@angular/material";
import {ModalErrorMessageComponent} from "../../common/modal-error-message.component";
import 'rxjs/Rx';

@Component({
    selector: 'menu-list',
    templateUrl: './menu-list.component.html'
})
export class MenuListComponent implements OnInit {
    menuItemList: any[];
    nodes;

    private menuListSubscription: Subscription;

    constructor(private menuService: MenuService, public dialog: MatDialog) {
        this.menuService.errorMessage.subscribe(
            (error: string) => {
console.log('this.menuService.errorMessage.subscribe!!!!!!!');
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
        let dialogRef = this.dialog.open(ModalErrorMessageComponent, {
            width: '250px',
            data: { message: error }
        });

        dialogRef.afterClosed().subscribe(result => {
console.log('The dialog was closed - result', result);
        });
    }

    ngOnDestroy() {
console.log('menu LIST - ON DESTROY');
        if (this.menuListSubscription != undefined) {
            this.menuListSubscription.unsubscribe();
        }
    }
}
