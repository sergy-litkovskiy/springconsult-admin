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

    constructor(articleData: any[]) {
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
}
