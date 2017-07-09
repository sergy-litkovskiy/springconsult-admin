"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var ArticleItem = (function () {
    function ArticleItem(articleData) {
        this.id = articleData['id'];
        this.title = articleData['title'];
        this.metaDescription = articleData['meta_description'];
        this.metaKeywords = articleData['meta_keywords'];
        this.status = articleData['status'];
        this.assignedMenuList = articleData['assignedMenuList'];
    }
    return ArticleItem;
}());
exports.ArticleItem = ArticleItem;
//# sourceMappingURL=article-item.model.js.map