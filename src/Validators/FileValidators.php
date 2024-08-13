<?php

namespace App\Validators;

class FileValidators {
    public static function validImage($file) {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $maxFileSize = 5 * 1024 * 1024; // 5 MB em bytes

        if(!in_array($file['type'], $allowedTypes)) {
            return ["invalid" => UNSUPPORTED_IMG];
        }

        if($file['size'] > $maxFileSize) {
            return ["invalid" => LARGE_FILE];
        }

        if($file['error'] !== UPLOAD_ERR_OK) {
            return ["invalid" => UPLOAD_ERROR];
        }

        $mimeType = explode("/" , $file["type"])[1];

        return [
            "fileName"  => $file["name"],
            "size"      => $file["size"],
            "mimeType"  => $mimeType
        ];
    }

    public static function validIcon($file) {
        $allowedTypes = ['image/vnd.microsoft.icon', 'image/x-icon', 'image/jpeg', 'image/jpg', 'image/png'];
        $maxFileSize = 5 * 1024 * 1024;
    
        if(!in_array($file['type'], $allowedTypes)) {
            return ["invalid" => UNSUPPORTED_ICO];
        }
    
        if($file['size'] > $maxFileSize) {
            return ["invalid" => LARGE_FILE];
        }
    
        if($file['error'] !== UPLOAD_ERR_OK) {
            return ["invalid" => UPLOAD_ERROR];
        }
    
        $mimeType = explode("/" , $file["type"])[1];

        if($mimeType === 'vnd.microsoft.icon') {
            $mimeType = "ico";
        }
    
        return [
            "fileName"  => $file["name"],
            "size"      => $file["size"],
            "mimeType"  => $mimeType
        ];
    }    
}