<?php
namespace Waad\RepoMedia\Models;

use App\Models\Media;
use DateTimeInterface;
use Illuminate\Support\Str;

trait ModelTrait
{
    protected function serializeDate(DateTimeInterface $date){
        return $date->format('Y-m-d h:i:s a');
    }

    //relation with Media
    public function media(){
        return $this->morphMany(Media::class,'model');
    }

    public function addMedia($file, $index = null){
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



}
