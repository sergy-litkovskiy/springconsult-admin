<?php

/**
 * @author Litkovskiy
 * @copyright 2010
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FileLoader {

    public static function loadFile($file, $uploadPath, $fileTmp)
    {
        $filename       = strtolower($file);
        $extArr         = explode('.', $filename);
        $format         = $extArr[1];
        $allowedFormat  = explode(',', ALLOWED_FORMAT);
        
        $isInArray = in_array($format, $allowedFormat);
        Common::assertTrue($isInArray, 'You try upload not allowed file type - '.$format);

        $isAllowedSize = (filesize($fileTmp) > ALLOWED_UPLOAD_SIZE) ? false : true;
        Common::assertTrue($isAllowedSize, 'The file '.$filename.' is grater than max allowed size');
 
        $isWritableDir = is_writable($uploadPath);
        Common::assertTrue($isWritableDir, 'It is not possible to download file '.$filename.' to dir '.$uploadPath);
       
        $isMoveUploadedFile = move_uploaded_file($fileTmp, $uploadPath.$filename);
        Common::assertTrue($isMoveUploadedFile, 'The file '.$filename.' was not download.<br>Please, try again');
 
        return $filename;
    }

    public static function uploadFileInBase64($fileName, $uploadPath, $fileInBase64)
    {
        $imageData = explode(',', $fileInBase64);
        $content = base64_decode(ArrayHelper::arrayGet($imageData, 1));

        $filePath = sprintf("%s/../..%s/%s", __DIR__, $uploadPath, $fileName);

        return file_put_contents($filePath, $content);
    }
}