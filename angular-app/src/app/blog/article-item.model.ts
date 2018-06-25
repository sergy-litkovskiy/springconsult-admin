import {MenuItem} from "../menu/menu-item.model";

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
    public imageData: string;

    constructor(articleData: object) {
        this.id = this.defineValue(articleData['id']);
        this.title = this.defineValue(articleData['title']);
        this.image = this.defineValue(articleData['image']);
        this.metaDescription = this.defineValue(articleData['meta_description']);
        this.metaKeywords = this.defineValue(articleData['meta_keywords']);
        this.text = this.defineValue(articleData['text']);
        this.description = this.defineValue(articleData['description']);
        this.slug = this.defineValue(articleData['slug']);
        this.status = this.defineValue(articleData['status']);
        this.isSentMail = this.defineValue(articleData['is_sent_mail']);
        this.numSequence = this.defineValue(articleData['num_sequence']);
        this.date = this.defineValue(articleData['date']);
        this.assignedMenuList = articleData['assignedMenuList'] !== undefined ?
            this.makeAssignedMenuList(articleData['assignedMenuList']) :
            [];
        this.imageData = null;//use only for new uploaded image to pass base64 data into server
    }

    makeAssignedMenuList(dataList: any[]) {
        return dataList.map(
            (data: any[]) => {
                let menuItem = new MenuItem(data);
                // let menuItem = {};
                // menuItem.isChecked = true;

                return menuItem;
            }
        );
    }

    isActive() {
        return (this.status == true);
    }

    defineValue(value = null) {
        return value
    }
}
