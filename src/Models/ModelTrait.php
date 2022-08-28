<?php
namespace Waad\RepoMedia\Models;

use DateTimeInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Waad\RepoMedia\Helpers\Utilities;

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
            return $this->addFile($file, $index);
        }else{
            return null;
        }
    }

    public function addMediaArray($files){
        if($files){
            for($i = 0 ; $i < count($files) ; $i++){
                $media[] = $this->addFile($files[$i], $i);
            }
            return $media;
        }else{
            return null;
        }
    }

    private function addFile($file , $index){
        $Image = Str::random(32) . time() . '.' .$file->extension();
        $destinationPath = Utilities::uploadDir();
        $movePath = $file->move($destinationPath, $Image);
        $mime = $file->getClientMimeType();
        $buket = Utilities::getBuket( str_replace('/' . basename($mime),'',$mime));
        return $this->media()->create([
            'path' => $Image,
            'index' => $index,
            'file_name' => $file->getClientOriginalName(),
            'buket' => $buket,
            'mime_type' => basename($mime),
            'file_size' => filesize($movePath),
            'user_id' => Auth::check() ? Auth::id() : null,
        ]);
    }

    public function destroyMedia($id){
        $media = $this->media()->findOrFail($id);
        Utilities::deleteFile($media);
        $media->forceDelete();
    }

    public function destroyMediaArray($ids){
        foreach($ids as $id){
            $media = $this->media()->findOrFail($id);
            Utilities::deleteFile($media);
        }
    }
}
