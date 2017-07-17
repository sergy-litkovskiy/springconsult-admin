import {Component, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute, Params} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import {FormArray, FormControl, FormGroup, Validators} from "@angular/forms";

@Component({
    selector: 'menu-item',
    template: `
        <div class="row box-primary">
            <div class="col-xs-12">
                <form [formGroup]="articleForm" (ngSubmit)="onSubmit()">
                    <div class="row">
                        <div class="col-xs-12">
                            <button
                                    type="submit"
                                    class="btn btn-success"
                                    [disabled]="!recipeForm.valid">Save</button>
                            <button type="button" class="btn btn-danger" (click)="onCancel()">Cancel</button>
                        </div>
                    </div>
                    <!--<div class="row">-->
                        <!--<div class="col-xs-12">-->
                            <!--<div class="form-group">-->
                                <!--<label for="name">Name</label>-->
                                <!--<input-->
                                        <!--type="text"-->
                                        <!--id="name"-->
                                        <!--formControlName="name"-->
                                        <!--class="form-control">-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--<div class="row">-->
                        <!--<div class="col-xs-12">-->
                            <!--<div class="form-group">-->
                                <!--<label for="imagePath">Image URL</label>-->
                                <!--<input-->
                                        <!--type="text"-->
                                        <!--id="imagePath"-->
                                        <!--formControlName="imagePath"-->
                                        <!--class="form-control"-->
                                        <!--#imagePath>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--<div class="row">-->
                        <!--<div class="col-xs-12">-->
                            <!--<img [src]="imagePath.value" class="img-responsive">-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--<div class="row">-->
                        <!--<div class="col-xs-12">-->
                            <!--<div class="form-group">-->
                                <!--<label for="description">Description</label>-->
                                <!--<textarea-->
                                        <!--type="text"-->
                                        <!--id="description"-->
                                        <!--class="form-control"-->
                                        <!--formControlName="description"-->
                                        <!--rows="6"></textarea>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
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
        // const newRecipe = new Recipe(
        //   this.recipeForm.value['name'],
        //   this.recipeForm.value['description'],
        //   this.recipeForm.value['imagePath'],
        //   this.recipeForm.value['ingredients']);
        if (this.editMode) {
            // this.articleService.updateArticle(this.id, this.recipeForm.value);
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
        let recipeIngredients = new FormArray([]);
        let articleItem: ArticleItem;

        if (this.editMode) {
            articleItem = this.articleService.getArticleById(this.articleId);

            if (!articleItem) {
                this.showErrorPopup('Article item with ID '+this.articleId+' was not found');

                return false;
            }

        //     if (recipe['ingredients']) {
        //         for (let ingredient of recipe.ingredients) {
        //             recipeIngredients.push(
        //                 new FormGroup({
        //                     'name': new FormControl(ingredient.name, Validators.required),
        //                     'amount': new FormControl(ingredient.amount, [
        //                         Validators.required,
        //                         Validators.pattern(/^[1-9]+[0-9]*$/)
        //                     ])
        //                 })
        //             );
        //         }
        //     }
        }

        this.articleForm = new FormGroup({
            'id': new FormControl(articleItem.id, Validators.required),
            'date': new FormControl(articleItem.date, Validators.required),
            'time': new FormControl(articleItem.time, Validators.required),
            'title': new FormControl(articleItem.title, Validators.required),
            'description': new FormControl(articleItem.description, Validators.required),
            'metaDescription': new FormControl(articleItem.metaDescription),
            'metaKeywords': new FormControl(articleItem.metaKeywords),
            'ingredients': recipeIngredients
        });
    }
}
