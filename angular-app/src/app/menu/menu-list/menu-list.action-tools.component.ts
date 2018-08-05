import {Component, Input, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import 'rxjs/Rx';
import {MenuService} from "../menu.service";
import {MenuItem} from "../menu-item.model";


@Component({
    selector: 'action-tools-component',
    templateUrl: '../../common/action-tools.html'
})
export class MenuListActionToolsComponent implements OnInit {
    public params: any;
    private actionButtonClassName: string;

    @Input() menuItem: MenuItem;

    constructor(private serviceService: MenuService,
                private router: Router,
                private route: ActivatedRoute) {
    }

    ngOnInit(): void {
        this.actionButtonClassName = "btn btn-sm btn-";
    }

    getButtonClassForStatusOn(): string {
        let currentStatus = +this.menuItem.status == 1 ? "success disabled" : "default";
        return this.actionButtonClassName + currentStatus;
    }

    getButtonClassForStatusOff(): string {
        let currentStatus = +this.menuItem.status == 1 ? "default" : "success disabled";
        return this.actionButtonClassName + currentStatus;
    }

    onStatusChangeClick(): void {
        this.menuItem.status = !+this.menuItem.status;
console.log('onStatusChangeClick - this.menuItem', this.menuItem);
        this.serviceService.updateMenu(this.menuItem)
            .subscribe(
                (response: any) => console.log('response', response),
                (error) => {
                    this.serviceService.errorMessage.emit(error);

                    this.menuItem.status = !+this.menuItem.status;
                }
            );
    }

    onEditClick(): void {
        this.router.navigate(['/menu-edit', this.menuItem.id], {relativeTo: this.route});
    }

    onDeleteClick(): void {
        this.serviceService.deleteArticle(this.menuItem)
            .subscribe(
                (menuItem: MenuItem) => {
                    this.serviceService.menuItemDeleted.emit(menuItem);
console.log('onDeleteClick menuItem', menuItem);
                },
                (error) => {
                    this.serviceService.errorMessage.emit(error);
                }
        );
    }

    refresh(): boolean {
        return false;
    }
}
