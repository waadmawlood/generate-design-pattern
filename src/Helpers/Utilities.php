<?php
namespace Waad\RepoMedia\Helpers;

class Utilities {
    public static $imageSize = '?h=600';
    public static function uploadDir(){
        return storage_path('app/public/upload/');
    }
    public static function domain()
    {
        return request()->get('host');
    }
    public static function deleteFile($model){
        set_time_limit(0);
        if($model->path){
            realpath(Self::uploadDir(). $model->path);
            $pathDelete = realpath(Self::uploadDir(). $model->path);
            if(file_exists($pathDelete)){
                unlink($pathDelete);
                return true;
            }
        }
        return false;
    }
    public static function getBuket($buket){
        switch ($buket) {
            case "image":
                $buket_temp = "images"; break;
            case "video":
                $buket_temp = "videos"; break;
            case "audio":
                $buket_temp = "audios"; break;
            default:
                $buket_temp = "files";
        }
        return $buket_temp;
    }
    // check if a string starts with another string
    public static function strStartWith($strSource,$start){
        if (substr($strSource, 0, strlen($start)) === $start) {
            return true;
        }
        return false;
    }


}
