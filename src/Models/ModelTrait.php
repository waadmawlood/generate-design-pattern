<?php
namespace Waad\RepoMedia\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

trait ModelTrait
{
    use SoftDeletes;
    protected function serializeDate(DateTimeInterface $date){
        return $date->format('Y-m-d h:i:s a');
    }

    //relation with Media
    public function media(){
        return $this->morphMany(Media::class,'model');
    }

    public function addMedia($file, $index = 0){
        if($file){
            $Image = Str::random(32) . time() . '.' .$file->extension();
            $destinationPath = storage_path('app/public/upload');
            $movePath = $file->move($destinationPath, $Image);
            $mime = $file->getClientMimeType();
            $fileType = str_replace('/' . basename($mime),'',$mime);
            return $this->media()->create([
                'path' => $Image,
                'index' => $index,
                'file_name' => $file->getClientOriginalName(),
                'buket' => ucfirst($fileType),
                'mime_type' => basename($mime),
                'file_size' => filesize($movePath),
            ]);
        }else{
            return null;
        }
    }

    public function addMediaArray($files){
        if($files){
            for($i = 0 ; $i < count($files) ; $i++){
                $file = $files[$i];
                $Image = Str::random(32) . time() . '.' .$file->extension();
                $destinationPath = storage_path('app/public/upload');
                $movePath = $file->move($destinationPath, $Image);
                $mime = $file->getClientMimeType();
                $fileType = str_replace('/' . basename($mime),'',$mime);
                $media[] =  $this->media()->create([
                    'path' => $Image,
                    'index' => $i,
                    'file_name' => $file->getClientOriginalName(),
                    'buket' => ucfirst($fileType),
                    'mime_type' => basename($mime),
                    'file_size' => filesize($movePath),
                ]);
            }
            return $media;
        }else{
            return null;
        }
    }


    public function deleteMedia($id){
        $this->media()->destroy($id);
    }

    public function destroyMedia($id){
        $this->media()->findOrFail($id)->forceDelete();
    }


}
