import {Component, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {FormArray, FormControl, FormGroup, Validators} from "@angular/forms";
import {DateTimePickerModule} from 'ng-pick-datetime';
import {Subscription} from "rxjs/Subscription";
import {MenuItem} from "../../menu/menu-item.model";
import {MenuService} from "../../menu/menu.service";

@Component({
    selector: 'article-edit',
    styles: [
        '.form-control.ckeditor { padding: 0; height: auto!important; }',
        '.preview-image-container {padding-top: 6px}'
    ],
    template: `
        <div class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-success">
                        <h2 *ngIf="articleItem.status == 1" class="label bg-green">Active</h2>
                        <h2 *ngIf="articleItem.status == 0" class="label bg-red">Inactive</h2>
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
                                            <label for="image">Image for preview (165 x 165)</label>
                                            <input
                                                    type="file"
                                                    id="image"
                                                    formControlName="image"
                                                    class="form-control"
                                                    (change)="imageUpload($event)"
                                                    #image>
                                        </div>
                                        <div class="form-group">
                                            <label for="slug">Slug</label>
                                            <input
                                                    type="text"
                                                    id="slug"
                                                    formControlName="slug"
                                                    class="form-control">
                                        </div>
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
                                        <div class="form-group preview-image-container">
                                            <img [src]="imagePath" class="img-responsive">
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea
                                                    type="text"
                                                    id="description"
                                                    class="form-control"
                                                    formControlName="description"
                                                    rows="6"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label for="metaDescription">Meta Description</label>
                                            <textarea
                                                    type="text"
                                                    id="metaDescription"
                                                    class="form-control"
                                                    formControlName="metaDescription"
                                                    rows="6"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label for="metaKeywords">Meta Keywords</label>
                                            <textarea
                                                    type="text"
                                                    id="metaKeywords"
                                                    class="form-control"
                                                    formControlName="metaKeywords"
                                                    rows="6"></textarea>
                                        </div>
                                    </div>
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
                                    <label for="assignedMenuList">Assigned menu list</label>
                                    <input 
                                            *ngFor="let menuItem of availableMenuList;"
                                            type="checkbox" 
                                            [checked]="menuItem.isChecked" 
                                            (change)="onChangeAssignment()"
                                    >
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
    originalImageName: string;
    dateTimePicker: DateTimePickerModule;
    createdAt: any;
    availableMenuList: MenuItem[];
    assignedMenuIdList: number[];

    private articleItemSubscription: Subscription;

    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(
        private articleService: ArticleService,
        private menuService: MenuService,
        private router: Router,
        private route: ActivatedRoute
    ) {
        this.dateTimePicker = new DateTimePickerModule();
        this.availableMenuList = this.menuService.getMenuItemList();
    }

    ngOnInit() {
        this.articleItemSubscription = this.route.params
            .subscribe(
                (params: Params) => {
                    this.articleId = +params['id'];
                    this.editMode = params['id'] != null;
                    this.initForm();
                }
            );
    }

    ngOnDestroy() {
console.log('article ITEM - ON DESTROY');
        this.articleItemSubscription.unsubscribe();
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
        };

        this.originalImageName = this.file.name;

        reader.readAsDataURL(this.file);
    }

    onSubmit() {
        this.fillArticleItem();

        if (this.editMode) {
            this.articleService.updateArticle(this.articleItem)
                .subscribe(
                    (response: any) => {
                        this.articleItem.imageData = null;
                        this.articleService.updateArticleItemInList(this.articleItem);
                        this.redirectToArticleList();
                    },
                    (error) => {
                        this.showErrorPopup(error);
                    }
                );
        } else {
            // this.articleService.addArticle(this.articleItem);
        }
    }

    private fillArticleItem() {
        this.articleItem.title = this.articleForm.value.title;
        this.articleItem.imageData = this.file != null ? this.imagePath : null;
        this.articleItem.metaDescription = this.articleForm.value.metaDescription;
        this.articleItem.metaKeywords = this.articleForm.value.metaKeywords;
        this.articleItem.text = this.articleForm.value.ckeditorContent;
        this.articleItem.description = this.articleForm.value.description;
        this.articleItem.slug = this.articleForm.value.slug;
        this.articleItem.date = this.articleForm.value.createdAt;
        this.articleItem.image = this.file != null ? this.originalImageName : this.articleItem.image;
        // this.articleItem.assignedMenuList = articleData['assignedMenuList'] !== undefined ? articleData['assignedMenuList'] : [];
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
        this.router.navigate(['/article-edit/:id', this.articleItem.id], {relativeTo: this.route});
    }

    redirectToArticleList() {
        this.router.navigate(['/article-list'], {relativeTo: this.route});
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
        this.articleItem = new ArticleItem({});

        if (this.editMode) {
            this.articleItem = this.articleService.getArticleById(this.articleId);

            if (!this.articleItem) {
                this.showErrorPopup('Article item with ID '+this.articleId+' was not found');

                return false;
            }

            this.assignedMenuIdList = this.articleItem.assignedMenuList.map(
                (menuItem: MenuItem) => {
                    return menuItem.id;
                }
            );

console.log('INIT this.assignedMenuIdList', this.assignedMenuIdList);

            this.availableMenuList = this.availableMenuList.map(
                (menuItem: MenuItem) => {
                    if (this.assignedMenuIdList.indexOf(menuItem.id)) {
                        menuItem.isChecked = true;
                    }

                    return menuItem;
                }
            );
        }

        this.createdAt = this.articleItem.date || Date.now();
        //set text for ckeditor replacement
        this.ckeditorContent = this.articleItem.text;
        //define image path for preview
        this.imagePath = this.articleItem.image ? '/img/blog/' + this.articleItem.image : '';

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
            'numSequence': new FormControl(this.articleItem.numSequence)
        });
    }

    onChangeAssignment() {
        console.log('onChangeAssignment', arguments);
    }
}
