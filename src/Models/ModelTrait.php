<?php
namespace Waad\Pattern\Models;

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
        return $this->morphMany(Media::class, 'media');
    }

    public function addMedia(string|\Symfony\Component\HttpFoundation\File\UploadedFile $file, $bio = null , $file_type = null){
        if($file){
            $Image = Str::random(32) . time() . '.' .$file->extension();
            $destinationPath = storage_path('app/public/upload');
            $file->move($destinationPath, $Image);
            return $this->media()->create([
                'path' => $Image,
                'bio' => $bio,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file_type,
                'mime_type' => $file->getMimeType(),
                'file_size' => filesize($file),
            ]);
            return $Image;
        }else{
            return null;
        }

    }



}
