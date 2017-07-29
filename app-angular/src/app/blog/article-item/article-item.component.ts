import {Component, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {FormArray, FormControl, FormGroup, Validators} from "@angular/forms";
import { DateTimePickerModule } from 'ng-pick-datetime';
import { FileUploader } from 'ng2-file-upload';

@Component({
    selector: 'article-item',
    styles: ['.form-control.ckeditor { padding: 0; height: auto!important; }'],
    template: `
        <div class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-success">
                        <form [formGroup]="articleForm" (ngSubmit)="onSubmit()">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input
                                            type="text"
                                            id="title"
                                            formControlName="title"
                                            class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label for="slug">Slug</label>
                                            <input
                                                    type="text"
                                                    id="slug"
                                                    formControlName="slug"
                                                    class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label for="createdAt">Date</label>
                                            <input
                                                    type="text"
                                                    id="createdAt"
                                                    formControlName="createdAt"
                                                    class="form-control"
                                                    dateTimePicker
                                                    [returnObject]="'string'"
                                                    [viewFormat]="'YYYY-MM-DD HH:mm'"
                                                    [value]="createdAt | date: 'y-MM-dd HH:mm'"
                                                    [mode]="'dropdown'"
                                                    [autoClose]="true"
                                                    (onChange)="onDatePickerChange($event)"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label for="image">Image for preview (165 x 165)</label>
                                            <input
                                                    type="file"
                                                    id="image"
                                                    formControlName="image"
                                                    class="form-control"
                                                    (change)="imageUpload($event)"
                                                    #image>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <img [src]="imagePath" class="img-responsive">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea
                                            type="text"
                                            id="description"
                                            class="form-control"
                                            formControlName="description"
                                            rows="6"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="metaDescription">Meta Description</label>
                                    <textarea
                                            type="text"
                                            id="metaDescription"
                                            class="form-control"
                                            formControlName="metaDescription"
                                            rows="6"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="metaKeywords">Meta Keywords</label>
                                    <textarea
                                            type="text"
                                            id="metaKeywords"
                                            class="form-control"
                                            formControlName="metaKeywords"
                                            rows="6"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="text">Text</label>
                                    <ckeditor
                                            id="ckeditorContent"
                                            class="form-control ckeditor"
                                            formControlName="ckeditorContent"
                                            [readonly]="false"
                                            debounce="500"
                                    >
                                    </ckeditor>
                                </div>
                                <div class="form-group">
                                    <input
                                            type="hidden"
                                            id="status"
                                            formControlName="status"
                                            class="form-control">
                                    <input
                                            type="hidden"
                                            id="isSentMail"
                                            formControlName="isSentMail"
                                            class="form-control">
                                    <input
                                            type="hidden"
                                            id="numSequence"
                                            formControlName="numSequence"
                                            class="form-control">
                                </div>
                            </div>
                            <div class="box-footer">
                                <button
                                        type="submit"
                                        class="btn btn-success"
                                        [disabled]="!articleForm.valid">Save
                                </button>
                                <button
                                        type="button"
                                        class="btn btn-danger"
                                        (click)="onCancel()">Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `
})

export class AppArticleItemComponent implements OnInit {
    articleItem: ArticleItem;
    editMode = false;
    articleId: number;
    articleForm: FormGroup;
    ckeditorContent: string;
    file: File;
    imagePath: string;

    dateTimePicker: DateTimePickerModule;
    createdAt: any;

    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(
        private articleService: ArticleService,
        private router: Router,
        private route: ActivatedRoute
    ) {
        this.dateTimePicker = new DateTimePickerModule();
    }

    ngOnInit() {
        this.route.params
            .subscribe(
                (params: Params) => {
                    this.articleId = +params['id'];
                    this.editMode = params['id'] != null;
                    this.initForm();
                }
            );
    }

    imageUpload(event: EventTarget) {
        let eventObj: MSInputMethodContext = <MSInputMethodContext> event;
        let target: HTMLInputElement = <HTMLInputElement> eventObj.target;
        let files: FileList = target.files;
        this.file = files[0];

        let reader = new FileReader();
        reader.onloadend = () => {
            //Assign the result to variable for setting the src of image element
            this.imagePath = reader.result;
        }

        reader.readAsDataURL(this.file);
    }

    onSubmit() {
console.log('onSubmit - this.articleForm.value', this.articleForm.value);
console.log('onSubmit - this.articleForm.value', this.articleForm);
        this.articleItem.id = this.articleForm.value.id;
        this.articleItem.title = this.articleForm.value.title;
        if (this.editMode) {
            // this.articleService.updateArticle(this.articleId, this.recipeForm.value);
        } else {
            // this.articleService.addArticle(this.recipeForm.value);
        }
        this.onCancel();
    }

    // onAddIngredient() {
    //     (<FormArray>this.articleForm.get('ingredients')).push(
    //         new FormGroup({
    //             'name': new FormControl(null, Validators.required),
    //             'amount': new FormControl(null, [
    //                 Validators.required,
    //                 Validators.pattern(/^[1-9]+[0-9]*$/)
    //             ])
    //         })
    //     );
    // }

    // onDeleteIngredient(index: number) {
    //     (<FormArray>this.articleForm.get('ingredients')).removeAt(index);
    // }

    onCancel() {
        this.router.navigate(['/article-edit', this.articleItem.id], {relativeTo: this.route});
    }

    public onDatePickerChange(moment: any): any {
        this.createdAt = moment;
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

    private initForm() {
        let assignedMenuList = new FormArray([]);
console.log('initForm - this.editMode', this.editMode);

        this.articleItem = new ArticleItem({});

        if (this.editMode) {
            this.articleItem = this.articleService.getArticleById(this.articleId);
console.log('initForm - this.articleItem', this.articleItem);

            if (!this.articleItem) {
                this.showErrorPopup('Article item with ID '+this.articleId+' was not found');

                return false;
            }

            // if (this.articleItem.assignedMenuList.size > 0) {
            //     for (let assignedMenu of this.articleItem.assignedMenuList) {
            //         assignedMenuList.push(
            //             new FormGroup({
            //                 // 'name': new FormControl(assignedMenu.name, Validators.required)
            //             })
            //         );
            //     }
            // }
        }

        this.createdAt = this.articleItem.date || Date.now();
        this.ckeditorContent = this.articleItem.text;
        this.imagePath = '/img/blog/' + this.articleItem.image;

        this.articleForm = new FormGroup({
            'id': new FormControl(this.articleItem.id, Validators.required),
            'createdAt': new FormControl(this.articleItem.date, Validators.required),
            'title': new FormControl(this.articleItem.title, Validators.required),
            'description': new FormControl(this.articleItem.description),
            'ckeditorContent': new FormControl(this.articleItem.text),
            'metaDescription': new FormControl(this.articleItem.metaDescription),
            'metaKeywords': new FormControl(this.articleItem.metaKeywords),
            'image': new FormControl(null),
            'slug': new FormControl(this.articleItem.slug, Validators.required),
            'status': new FormControl(this.articleItem.status, Validators.required),
            'isSentMail': new FormControl(this.articleItem.isSentMail),
            'numSequence': new FormControl(this.articleItem.numSequence),
            'assignedMenuList': assignedMenuList
        });
    }
}
