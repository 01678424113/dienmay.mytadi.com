<?php

namespace App\Helpers;

use Image;

class ImageUpload {

    public static function image($file, $name) {
        try {
            $image = Image::make($file);
            $path = static::path('uploads/') . $name . static::extension($image->mime());
            if ($image->save($path)) {
                return $path;
            }
        } catch (\Exception $ex) {
            
        }
        return "images/default-image.png";
    }

    public static function path($parent_dir) {
        $path = "";
        $list_dir = explode('/', $parent_dir);
        foreach ($list_dir as $dir) {
            if ($dir != "") {
                $path .= $dir . "/";
                if (!is_dir($path)) {
                    mkdir($path);
                }
            }
        }
        $date_dir = [date("Y"), date("m"), date('d')];
        foreach ($date_dir as $dir) {
            $path .= $dir . "/";
            if (!is_dir($path)) {
                mkdir($path);
            }
        }
        return $path;
    }

    private static function extension($mime) {
        switch ($mime) {
            case 'image/gif':
                return '.gif';
            case 'image/jpeg':
                return '.jpg';
            case 'image/png':
                return '.png';
            case 'image/svg+xml':
                return '.svg';
            case 'image/tiff':
                return '.tiff';
            case 'image/webp':
                return '.webp';
            case 'image/x-icon':
                return '.ico';
            default :
                return '.jpg';
        }
    }

}
