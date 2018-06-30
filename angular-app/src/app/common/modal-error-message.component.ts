import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA} from '@angular/material';

@Component({
    selector: 'modal-error-message',
    template: `
        <div tabindex="-1">
            <h2 mat-dialog-title>Error message</h2>
            <mat-dialog-content>{{ data.message }}</mat-dialog-content>
            <mat-dialog-actions>
                <button mat-button mat-dialog-close>Close</button>
                <!-- The mat-dialog-close directive optionally accepts a value as a result for the dialog. -->
                <!--<button mat-button [mat-dialog-close]="true">Yes</button>-->
            </mat-dialog-actions>
        </div>
    `
})
export class ModalErrorMessageComponent implements OnInit {
    constructor(@Inject(MAT_DIALOG_DATA) public data: any) {}

    ngOnInit() {}
}
