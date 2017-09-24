"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var menu_item_model_1 = require("../menu/menu-item.model");
var ArticleItem = (function () {
    function ArticleItem(articleData) {
        this.id = articleData['id'] !== undefined ? articleData['id'] : null;
        this.title = articleData['title'] !== undefined ? articleData['title'] : null;
        this.image = articleData['image'] !== undefined ? articleData['image'] : null;
        this.metaDescription = articleData['meta_description'] !== undefined ? articleData['meta_description'] : null;
        this.metaKeywords = articleData['meta_keywords'] !== undefined ? articleData['meta_keywords'] : null;
        this.text = articleData['text'] !== undefined ? articleData['text'] : null;
        this.description = articleData['description'] !== undefined ? articleData['description'] : null;
        this.slug = articleData['slug'] !== undefined ? articleData['slug'] : null;
        this.status = (articleData['status'] == '1') !== undefined ? articleData['status'] : null;
        this.isSentMail = articleData['is_sent_mail'] !== undefined ? articleData['is_sent_mail'] : null;
        this.numSequence = articleData['num_sequence'] !== undefined ? articleData['num_sequence'] : null;
        this.date = articleData['date'] !== undefined ? articleData['date'] : null;
        this.assignedMenuList = articleData['assignedMenuList'] !== undefined ? this.makeAssignedMenuList(articleData['assignedMenuList']) : [];
        this.imageData = null; //use only for new uploaded image to pass base64 data into server
    }
    ArticleItem.prototype.makeAssignedMenuList = function (dataList) {
        return dataList.map(function (data) {
            var menuItem = new menu_item_model_1.MenuItem(data);
            menuItem.isChecked = true;
            return menuItem;
        });
    };
    return ArticleItem;
}());
exports.ArticleItem = ArticleItem;
//# sourceMappingURL=article-item.model.js.map