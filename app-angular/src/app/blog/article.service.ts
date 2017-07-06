import {EventEmitter, Injectable} from '@angular/core';

import {ArticleItem} from "./article-item.model";

@Injectable()
export class ArticleService {
    articleItemSelected = new EventEmitter<ArticleItem>();

    private articleItemList: ArticleItem[] = [
        new ArticleItem(
            1,
            'Article1',
            'Some metadescription for article1',
            'Some metakeywords for article1',
            true,
            ['Blog']
        ),
        new ArticleItem(
            2,
            'Article2',
            'Some metadescription for article2',
            'Some metakeywords for article2',
            false,
            ['Blog', 'Books', 'Job searching']
        )
    ];

    constructor() {
    }

    getArticleItemList() {
        return this.articleItemList.slice();
    }

    getArticleItem(index: number) {
        return this.articleItemList[index];
    }
}
