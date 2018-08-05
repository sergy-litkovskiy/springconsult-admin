import {Component, Inject, OnInit} from "@angular/core";
import {MatDialogRef} from "@angular/material";
import {MAT_DIALOG_DATA} from '@angular/material';

@Component({
    template: `
        <mat-dialog-content>
          {{ data.message }}
        </mat-dialog-content>
        <mat-dialog-actions>
            <button class="btn btn-default pull-right" (click)="onClose()">Close</button>
        </mat-dialog-actions>
    `,
    styleUrls: ['./overlay.component.css']
})
export class OverlayInfoComponent implements OnInit {
    constructor(
        private dialogRef: MatDialogRef<OverlayInfoComponent>,
        @Inject(MAT_DIALOG_DATA) public data: string
    ) {}

    ngOnInit() {}

    onClose() {
        this.dialogRef.close('Close');
    }
}