export class MenuItem {
    public id: number;
    public title: string;
    public text: string;
    public description: string;
    public slug: string;
    public status: boolean;
    public metaDescription: string;
    public metaKeywords: string;
    public numSequence: boolean;
    public articleId: number;
    public colorClass: string;
    public iconClass: string;
    public parent: number;
    public isChecked: boolean;


    constructor(menuData: object) {
        this.id = this.defineValue(menuData['id']);
        this.title = this.defineValue(menuData['title']);
        this.metaDescription = this.defineValue(menuData['meta_description']);
        this.metaKeywords = this.defineValue(menuData['meta_keywords']);
        this.text = this.defineValue(menuData['text']);
        this.description = this.defineValue(menuData['description']);
        this.slug = this.defineValue(menuData['slug']);
        this.status = this.defineValue(menuData['status']);
        this.numSequence = this.defineValue(menuData['num_sequence']);
        this.articleId = this.defineValue(menuData['article_id']);
        this.colorClass = this.defineValue(menuData['color_class']);
        this.iconClass = this.defineValue(menuData['icon_class']);
        this.parent = this.defineValue(menuData['parent']);
        this.isChecked = false;
    }

    defineValue(value = null) {
        return value;
    }
}
