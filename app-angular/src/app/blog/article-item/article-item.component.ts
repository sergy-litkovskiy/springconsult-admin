import {Component, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {FormControl, FormGroup, Validators} from "@angular/forms";
import {DateTimePickerModule} from 'ng-pick-datetime';
import {Subscription} from "rxjs/Subscription";
import {MenuItem} from "../../menu/menu-item.model";
import {MenuService} from "../../menu/menu.service";
import {CKEditorModule} from "ng2-ckeditor";
import {AppMainCkeditorHelper} from "../../app.main.ckeditor.helper";

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
                        <h2 *ngIf="articleItem.isActive()" class="label bg-green">Active</h2>
                        <h2 *ngIf="!articleItem.isActive()" class="label bg-red">Inactive</h2>
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
                                        id="ckeditorContainer"
                                        class="form-control ckeditor"
                                        formControlName="ckeditorContent"
                                        [readonly]="false"
                                        debounce="500"
                                        [config]="ckeditorConfig"
                                        >
                                    </ckeditor>
                                </div>
                                <div class="form-group">
                                    <label for="assignedMenuList">Assigned menu list: </label>
                                    <span *ngFor="let menuItem of availableMenuList;">
                                        <input
                                                type="checkbox"
                                                [checked]="menuItem.isChecked"
                                                (change)="onChangeAssignment(menuItem)"
                                        > {{ menuItem.title }} |
                                    </span>
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
        <ngui-popup #popup></ngui-popup>
    `
})

export class AppArticleItemComponent implements OnInit {
    articleItem: ArticleItem;
    editMode = false;
    articleId: number;
    articleForm: FormGroup;
    ckeditorContent: string;
    ckeditorConfig: any;
    file: File;
    imagePath: string;
    originalImageName: string;
    dateTimePicker: DateTimePickerModule;
    createdAt: any;
    availableMenuList: MenuItem[];
    assignedMenuItemList: MenuItem[];

    private articleItemSubscription: Subscription;
    private articleDataSubscription: Subscription;
    private menuListSubscription: Subscription;

    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;
    @ViewChild(CKEditorModule) ckeditorContainer: CKEditorModule;

    constructor(
        private articleService: ArticleService,
        private menuService: MenuService,
        private router: Router,
        private route: ActivatedRoute,
        private appMainCkeditorHelper: AppMainCkeditorHelper
    ) {
        this.dateTimePicker = new DateTimePickerModule();
        this.ckeditorConfig = appMainCkeditorHelper.getCkeditorDefaultConfig();
    }

    ngOnInit() {
        this.articleItemSubscription = this.route.params
            .subscribe(
                (params: Params) => {
                    this.articleId = +params['id'];
                    this.editMode = params['id'] != null;
                    this.initEmptyForm();
                    this.articleItem = new ArticleItem({});

                    this.setAvailableMenuList();

                    if (this.editMode) {
                        this.initArticleItem();
                    } else {
                        this.initForm();
                    }
                }
            );
    }

    private initEmptyForm() {
        this.articleForm = new FormGroup({
            'id': new FormControl(null),
            'createdAt': new FormControl(null),
            'title': new FormControl(null),
            'description': new FormControl(null),
            'ckeditorContent': new FormControl(null),
            'metaDescription': new FormControl(null),
            'metaKeywords': new FormControl(null),
            'image': new FormControl(null),
            'slug': new FormControl(null),
            'status': new FormControl(null),
            'isSentMail': new FormControl(null),
            'numSequence': new FormControl(null)
        });
    }

    private initArticleItem() {
        let article = this.articleService.getArticleById(this.articleId);
        //try to fill article list after page was manually reloaded
        if (article) {
            this.articleItem = article;
            this.initForm();
            return;
        }

        this.articleDataSubscription = this.articleService.getArticleItemByIdFromServer(this.articleId)
            .subscribe(
                (articleItem: ArticleItem) => {
                    this.articleItem = articleItem;
                    this.initForm();
                },
                (error) => {
                    this.showErrorPopup(error);
                }
            );
    }

    private setAvailableMenuList() {
        this.availableMenuList = this.menuService.getMenuItemList();

        if (!this.availableMenuList.length) {
            this.menuListSubscription = this.menuService.getMenuItemListFromService()
                .subscribe(
                    (menuItems: MenuItem[]) => {
                        this.availableMenuList = menuItems;
                    },
                    (error) => {
                        this.showErrorPopup(error);
                    }
                );
        }
    }

    private initForm() {
        this.assignedMenuItemList = this.articleItem.assignedMenuList;

        this.availableMenuList = this.availableMenuList.map(
            (menuItem: MenuItem) => {
                if (
                    this.assignedMenuItemList
                        .find((assignedMenuItem: MenuItem) => assignedMenuItem.id == menuItem.id)
                ) {
                    menuItem.isChecked = true;
                }

                return menuItem;
            }
        );

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
            this.articleService.addArticle(this.articleItem)
                .subscribe(
                    (response: any) => {
                        this.articleItem.imageData = null;
                        this.articleService.addArticleItemToList(this.articleItem);
                        this.redirectToArticleList();
                    },
                    (error) => {
                        this.showErrorPopup(error);
                    }
                );
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
        this.articleItem.assignedMenuList = this.assignedMenuItemList;
    }

    onCancel() {
        this.redirectToArticleList();
    }

    redirectToArticleList() {
        this.router.navigate(['/article-list'], {relativeTo: this.route});
    }

    onDatePickerChange(moment: any): any {
        this.createdAt = moment;
    }

    onChangeAssignment(menuItem: MenuItem) {
        let previousState = menuItem.isChecked;

        if (previousState) {
            this.assignedMenuItemList = this.assignedMenuItemList
                .filter((assignedMenuItem: MenuItem) => assignedMenuItem.id !== menuItem.id);
        } else {
            this.assignedMenuItemList.push(menuItem)
        }

        menuItem.isChecked = !previousState;
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
console.log('article ITEM - ON DESTROY');

        if (this.articleItemSubscription != undefined) {
            this.articleItemSubscription.unsubscribe();
        }

        if (this.articleDataSubscription != undefined) {
            this.articleDataSubscription.unsubscribe();
        }
    }
}
