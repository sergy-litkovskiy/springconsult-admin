export class ArticleItem {
    public id: number;
    public title: string;
    public metaDescription: string;
    public metaKeywords: string;
    public status: boolean;
    public assignedMenuList: any;

    constructor(articleData: any[]) {
        this.id = articleData['id'];
        this.title = articleData['title'];
        this.metaDescription = articleData['meta_description'];
        this.metaKeywords = articleData['meta_keywords'];
        this.status = articleData['status'];
        this.assignedMenuList = articleData['assignedMenuList'];
    }
}
