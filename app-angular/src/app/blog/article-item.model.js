"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var ArticleItem = (function () {
    function ArticleItem(articleData) {
        this.id = articleData['id'];
        this.title = articleData['title'];
        this.image = articleData['image'];
        this.metaDescription = articleData['meta_description'];
        this.metaKeywords = articleData['meta_keywords'];
        this.text = articleData['text'];
        this.description = articleData['description'];
        this.slug = articleData['slug'];
        this.status = (articleData['status'] == 1);
        this.isSentMail = articleData['is_sent_mail'];
        this.numSequence = articleData['num_sequence'];
        this.date = articleData['date'];
        this.time = articleData['time'];
        this.assignedMenuList = articleData['assignedMenuList'];
    }
    return ArticleItem;
}());
exports.ArticleItem = ArticleItem;
//# sourceMappingURL=article-item.model.js.map