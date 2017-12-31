"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var MenuItemOld = (function () {
    function MenuItemOld(menuData) {
        this.id = menuData['id'] !== undefined ? menuData['id'] : null;
        this.title = menuData['title'] !== undefined ? menuData['title'] : null;
        this.metaDescription = menuData['meta_description'] !== undefined ? menuData['meta_description'] : null;
        this.metaKeywords = menuData['meta_keywords'] !== undefined ? menuData['meta_keywords'] : null;
        this.text = menuData['text'] !== undefined ? menuData['text'] : null;
        this.description = menuData['description'] !== undefined ? menuData['description'] : null;
        this.slug = menuData['slug'] !== undefined ? menuData['slug'] : null;
        this.status = (menuData['status'] == 1) !== undefined ? menuData['status'] : null;
        this.numSequence = menuData['num_sequence'] !== undefined ? menuData['num_sequence'] : null;
        this.articleId = menuData['article_id'] !== undefined ? menuData['article_id'] : null;
        this.colorClass = menuData['color_class'] !== undefined ? menuData['color_class'] : null;
        this.iconClass = menuData['icon_class'] !== undefined ? menuData['icon_class'] : null;
        this.parent = menuData['parent'] !== undefined ? menuData['parent'] : null;
        this.isChecked = false;
    }
    return MenuItemOld;
}());
exports.MenuItemOld = MenuItemOld;
//# sourceMappingURL=menu-item.model.js.map