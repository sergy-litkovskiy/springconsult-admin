import {Component, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {FormControl, FormGroup, Validators} from "@angular/forms";
import {DateTimePickerModule} from 'ng-pick-datetime';
import {Subscription} from "rxjs/Subscription";

import {CKEditorModule} from "ng2-ckeditor";
import {MenuService} from "../../menu/menu.service";
import {AppComponentCkeditorHelper} from "../../app.component.ckeditor.helper";
import {MenuItem} from "../../menu/menu-item.model";

@Component({
    selector: 'article-edit',
    styles: [
        '.form-control.ckeditor { padding: 0; height: auto!important; }',
        '.preview-image-container {padding-top: 6px}'
    ],
    templateUrl: './article-item.component.html'
})

export class ArticleItemComponent implements OnInit {
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
        private appComponentCkeditorHelper: AppComponentCkeditorHelper
    ) {
        this.dateTimePicker = new DateTimePickerModule();
        this.ckeditorConfig = appComponentCkeditorHelper.getCkeditorDefaultConfig();
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
            this.menuListSubscription = this.menuService.getMenuItemListFromServer()
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
                    (response) => {
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
                    (response) => {
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
