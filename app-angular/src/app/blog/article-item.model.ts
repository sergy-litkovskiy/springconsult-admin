export class ArticleItem {
    constructor(
        public id: number,
        public title: string,
        public metaDescription: string,
        public metaKeywords: string,
        public status: boolean,
        public assignedMenuList: any
    ) {}
}
