import {Component, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {FormArray, FormControl, FormGroup, Validators} from "@angular/forms";

@Component({
    selector: 'article-item',
    template: `
        <div class="row box-primary">
            <div class="col-xs-12">
                <form [formGroup]="articleForm" (ngSubmit)="onSubmit()">
                    <div class="row">
                        <div class="col-xs-12">
                            <button
                                    type="submit"
                                    class="btn btn-success"
                                    [disabled]="!articleForm.valid">Save</button>
                            <button type="button" class="btn btn-danger" (click)="onCancel()">Cancel</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input
                                    type="text"
                                    id="title"
                                    formControlName="title"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input
                                    type="text"
                                    id="slug"
                                    formControlName="slug"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input
                                    type="text"
                                    id="date"
                                    formControlName="date"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
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
                        <div class="col-xs-12">
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
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
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
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="text">Text</label>
                                <textarea
                                    type="text"
                                    id="text"
                                    class="form-control"
                                    formControlName="text"
                                    rows="6"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="image">Image for preview</label>
                                <input
                                    type="file"
                                    id="image"
                                    formControlName="image"
                                    class="form-control"
                                    #image>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <!--<img [src]="image.value" class="img-responsive">-->
                        </div>
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
                    <div class="row">
                        <!--<div class="col-xs-12" formArrayName="ingredients">-->
                            <!--<div-->
                                    <!--class="row"-->
                                    <!--*ngFor="let ingredientCtrl of recipeForm.get('ingredients').controls; let i = index"-->
                                    <!--[formGroupName]="i"-->
                                    <!--style="margin-top: 10px;">-->
                                <!--<div class="col-xs-8">-->
                                    <!--<input-->
                                            <!--type="text"-->
                                            <!--class="form-control"-->
                                            <!--formControlName="name">-->
                                <!--</div>-->
                                <!--<div class="col-xs-2">-->
                                    <!--<input-->
                                            <!--type="number"-->
                                            <!--class="form-control"-->
                                            <!--formControlName="amount">-->
                                <!--</div>-->
                                <!--<div class="col-xs-2">-->
                                    <!--<button-->
                                            <!--type="button"-->
                                            <!--class="btn btn-danger"-->
                                            <!--(click)="onDeleteIngredient(i)">X</button>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<hr>-->
                            <!--<div class="row">-->
                                <!--<div class="col-xs-12">-->
                                    <!--<button-->
                                            <!--type="button"-->
                                            <!--class="btn btn-success"-->
                                            <!--(click)="onAddIngredient()">Add Ingredient</button>-->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</div>-->
                    </div>
                </form>
            </div>
        </div>
    `,
    providers: [ArticleService]
})

export class AppArticleItemComponent implements OnInit {
    articleItemList: ArticleItem[] = [];
    articleItem: ArticleItem;
    editMode = false;
    articleId: number;
    articleForm: FormGroup;

    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(
        private articleService: ArticleService,
        private router: Router,
        private route: ActivatedRoute
    ) {}

    ngOnInit() {
console.log('ngOnInit for item editing');
        this.route.params
            .subscribe(
                (params: Params) => {
                    this.articleId = +params['id'];
                    this.editMode = params['id'] != null;
                    this.initForm();
                }
            );
    }

    onSubmit() {
        this.articleItem.title = this.articleForm.value['title'];
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
        this.router.navigate(['../'], {relativeTo: this.route});
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

        if (this.editMode) {
            this.articleItem = this.articleService.getArticleById(this.articleId);

            if (!this.articleItem) {
                this.showErrorPopup('Article item with ID '+this.articleId+' was not found');

                return false;
            }

            if (this.articleItem.assignedMenuList.size > 0) {
                for (let assignedMenu of this.articleItem.assignedMenuList) {
                    assignedMenuList.push(
                        new FormGroup({
                            // 'name': new FormControl(assignedMenu.name, Validators.required)
                        })
                    );
                }
            }
        } else {
            this.articleItem = new ArticleItem({});
        }

        this.articleForm = new FormGroup({
            'id': new FormControl(this.articleItem.id, Validators.required),
            'date': new FormControl(this.articleItem.date, Validators.required),
            'time': new FormControl(this.articleItem.time, Validators.required),
            'title': new FormControl(this.articleItem.title, Validators.required),
            'description': new FormControl(this.articleItem.description),
            'text': new FormControl(this.articleItem.text),
            'metaDescription': new FormControl(this.articleItem.metaDescription),
            'metaKeywords': new FormControl(this.articleItem.metaKeywords),
            'image': new FormControl(this.articleItem.image),
            'slug': new FormControl(this.articleItem.slug, Validators.required),
            'status': new FormControl(this.articleItem.status, Validators.required),
            'numSequence': new FormControl(this.articleItem.numSequence),
            'assignedMenuList': assignedMenuList
        });
    }
}
