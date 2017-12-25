import {Component, OnInit, Output, ViewChild, EventEmitter, Input} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {ArticleItem} from "../article-item.model";
import {ArticleService} from "../article.service";
import {NguiMessagePopupComponent, NguiPopupComponent} from "@ngui/popup";
import { Subscription } from 'rxjs/Subscription';
import {isNullOrUndefined} from "util";
import {ColumnApi, GridApi, GridOptions} from "ag-grid";

import ArticleListDataMock from "./article-list-mock";


@Component({
    selector: 'article-list',
    styleUrls: [
        "../../../../node_modules/ag-grid/dist/styles/ag-theme-bootstrap.css"
    ],
    templateUrl: './article-list.component.html'
})

export class AppArticleListComponent implements OnInit {
    articleItemList: ArticleItem[];
    actionButtonClassName: string;
    tempArticleList: ArticleItem[] = [];
    private articleListSubscription: Subscription;

    private gridOptions: GridOptions;
    private icons: any;
    public rowData: any[];
    public columnDefs: any[];
    public rowCount: string;

    private api: GridApi;
    private columnApi: ColumnApi;

    // @ViewChild(DatatableComponent) articleListTable: DatatableComponent;
    @ViewChild(NguiPopupComponent) popup: NguiPopupComponent;

    constructor(
        private articleService: ArticleService,
        private router: Router,
        private route: ActivatedRoute
    ) {
        this.gridOptions = <GridOptions>{};
        this.rowData = this.createRowData();
        this.columnDefs = this.createColumnDefs();
    }

    private onReady(params) {
        this.api = params.api;
        this.columnApi = params.columnApi;
    }

    ngOnInit() {
        this.articleItemList = this.articleService.getArticleItemList();

        if (!this.articleItemList.length) {
            this.articleListSubscription = this.articleService.getArticleItemListFromServer()
                .subscribe(
                    (articleList: ArticleItem[]) => {
                        this.articleItemList = articleList;
                        this.tempArticleList = [...articleList];
                    },
                    (error) => {
                        // this.showErrorPopup(error);
                    }
                );
        }

        this.actionButtonClassName = "btn btn-";
    }

    // onNewMenuItem() {
    //     this.router.navigate(['menu-new'], {relativeTo: this.route});
    // }

    getButtonClassForStatusOn(row: any): string {
        let currentStatus = +row.status == 1 ? "success disabled" : "default";
        return this.actionButtonClassName + currentStatus;
    }

    getButtonClassForStatusOff(row: any): string {
        let currentStatus = +row.status == 1 ? "default" : "success disabled";
        return this.actionButtonClassName + currentStatus;
    }

    onStatusChangeClick(row: any): void {
        row.status = !row.status;

        this.articleService.updateArticle(row)
            .subscribe(
                (response: any) => console.log('response', response),
                (error) => {
                    // this.showErrorPopup(error);

                    row.status = !row.status;
                }
            );
    }

    onEditClick(articleItem: ArticleItem): void {
        this.router.navigate(['/article-edit', articleItem.id], {relativeTo: this.route});
    }

    onDeleteClick(articleItem: ArticleItem): void {
        this.popup.open(NguiMessagePopupComponent, {
            classNames: 'small',
            title: articleItem.title,
            message: 'Are you sure you want to DELETE the article?',
            buttons: {
                OK: () => {
                    this.popup.close();

                    this.articleService.deleteArticle(articleItem)
                        .subscribe(
                            (response: any) => {
                                this.articleItemList = this.articleItemList.filter(obj => obj !== articleItem);
                            },
                            (error) => {
                                // this.showErrorPopup(error);
                            }
                        );
                },
                CANCEL: () => {
                    this.popup.close();
                }
            }
        });
    }

    searchArticle(event: any) {
        const val = event.target.value.toLowerCase();

        // filter our data
        this.articleItemList = this.tempArticleList.filter(function (articleItem) {
            return articleItem.title.toLowerCase().indexOf(val) !== -1 || !val;
        });

        // Whenever the filter changes, always go back to the first page
        // this.articleListTable.offset = 0;
    }

    showErrorPopup(error: string) {
        this.popup.open(NguiMessagePopupComponent, {
            classNames: 'small',
            title: 'ERROR',
            message: error,
            buttons: {
                CLOSE: () => {
                    this.popup.close();
                }
            }
        });
    }

    ngOnDestroy() {
console.log('article LIST - ON DESTROY');
        if (this.articleListSubscription != undefined) {
            this.articleListSubscription.unsubscribe();
        }
    }

    private createRowData() {
        const rowData: any[] = [];

        for (let i = 0; i < 200; i++) {
            const countryData = ArticleListDataMock.countries[i % ArticleListDataMock.countries.length];
            rowData.push({
                name: ArticleListDataMock.firstNames[i % ArticleListDataMock.firstNames.length] + ' ' + ArticleListDataMock.lastNames[i % ArticleListDataMock.lastNames.length],
                skills: {
                    android: Math.random() < 0.4,
                    html5: Math.random() < 0.4,
                    mac: Math.random() < 0.4,
                    windows: Math.random() < 0.4,
                    css: Math.random() < 0.4
                },
                dob: ArticleListDataMock.DOBs[i % ArticleListDataMock.DOBs.length],
                address: ArticleListDataMock.addresses[i % ArticleListDataMock.addresses.length],
                years: Math.round(Math.random() * 100),
                proficiency: Math.round(Math.random() * 100),
                country: countryData.country,
                continent: countryData.continent,
                language: countryData.language,
                mobile: 123,
                landline: 111111111
            });
        }

        return rowData;
    }

    private createColumnDefs() {
        const columnDefs = [
            {
                headerName: '#',
                width: 30,
                checkboxSelection: true,
                suppressSorting: true,
                suppressMenu: true,
                pinned: true
            },
            {
                headerName: 'Employee',
                children: [
                    {
                        headerName: "Name",
                        field: "name",
                        width: 150,
                        pinned: true
                    },
                    {
                        headerName: "Country",
                        field: "country",
                        width: 150,
                        // an example of using a non-React cell renderer
                        cellRenderer: countryCellRenderer,
                        pinned: true,
                        filter: 'set',
                        filterParams: {
                            cellRenderer: countryCellRenderer,
                            cellHeight: 20
                        },
                        cellEditor: 'agRichSelect',
                        cellEditorParams: {
                            values: ["Argentina", "Brazil", "Colombia", "France", "Germany", "Greece", "Iceland", "Ireland",
                                "Italy", "Malta", "Portugal", "Norway", "Peru", "Spain", "Sweden", "United Kingdom",
                                "Uruguay", "Venezuela", "Belgium", "Luxembourg"],
                            cellRenderer: countryCellRenderer,
                        },
                        editable: true
                    },
                    {
                        headerName: "Date of Birth",
                        field: "dob",
                        width: 110,
                        pinned: true,
                        cellRenderer: function (params) {
                            return pad(params.value.getDate(), 2) + '/' +
                                pad(params.value.getMonth() + 1, 2) + '/' +
                                params.value.getFullYear();
                        },
                        filter: 'date',
                        columnGroupShow: 'open'
                    }
                ]
            },
            {
                headerName: "Proficiency",
                field: "proficiency",
                width: 135
                // ,
                // supply an angular component
                // cellRendererFramework: ProficiencyCellRenderer
            },
            {
                headerName: 'Contact',
                children: [
                    {headerName: "Mobile", field: "mobile", width: 150, filter: 'text'},
                    {headerName: "Landline", field: "landline", width: 150, filter: 'text'},
                    {headerName: "Address", field: "address", width: 500, filter: 'text'}
                ]
            }
        ];

        return columnDefs;
    }
}

function countryCellRenderer(params) {
    const flag = "<img border='0' width='15' height='10' style='margin-bottom: 2px' src='https://www.ag-grid.com/images/flags/" + ArticleListDataMock.COUNTRY_CODES[params.value] + ".png'>";
    return flag + " " + params.value;
}

//Utility function used to pad the date formatting.
function pad(num, totalStringSize) {
    let asString = num + "";
    while (asString.length < totalStringSize) asString = "0" + asString;
    return asString;
}

