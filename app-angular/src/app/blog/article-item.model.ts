export class ArticleItem {
    public id: number;
    public title: string;
    public image: string;
    public metaDescription: string;
    public metaKeywords: string;
    public text: string;
    public description: string;
    public slug: string;
    public status: boolean;
    public isSentMail: boolean;
    public numSequence: boolean;
    public date: string;
    public time: string;
    public assignedMenuList: any;

    constructor(articleData: object) {
console.log('articleData', articleData);
        this.id = articleData['id'] !== undefined ? articleData['id'] : null;
        this.title = articleData['title'] !== undefined ? articleData['title'] : null;
        this.image = articleData['image'] !== undefined ? articleData['image'] : null;
        this.metaDescription = articleData['meta_description'] !== undefined ? articleData['meta_description'] : null;
        this.metaKeywords = articleData['meta_keywords'] !== undefined ? articleData['meta_keywords'] : null;
        this.text = articleData['text'] !== undefined ? articleData['text'] : null;
        this.description = articleData['description'] !== undefined ? articleData['description'] : null;
        this.slug = articleData['slug'] !== undefined ? articleData['slug'] : null;
        this.status = (articleData['status'] == 1) !== undefined ? articleData['status'] : null;
        this.isSentMail = articleData['is_sent_mail'] !== undefined ? articleData['is_sent_mail'] : null;
        this.numSequence = articleData['num_sequence'] !== undefined ? articleData['num_sequence'] : null;
        this.date = articleData['date'] !== undefined ? articleData['date'] : null;
        this.time = articleData['time'] !== undefined ? articleData['time'] : null;
        this.assignedMenuList = articleData['assignedMenuList'] !== undefined ? articleData['assignedMenuList'] : [];
    }
}
