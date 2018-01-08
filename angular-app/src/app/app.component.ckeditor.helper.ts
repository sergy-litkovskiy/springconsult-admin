import {Injectable} from '@angular/core';

@Injectable()
export class AppComponentCkeditorHelper {

    public getCkeditorDefaultConfig() {
        return {
            filebrowserBrowseUrl: '/vendor/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl: '/vendor/ckfinder/ckfinder.html?Type=Images',
            filebrowserUploadUrl: '/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl: '/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        };
    }
}
