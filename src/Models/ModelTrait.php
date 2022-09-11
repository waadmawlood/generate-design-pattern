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
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i:s a');
    }

    //relation with Media
    public function media()
    {
        return $this->morphMany(Media::class,'model')->orderByDesc('id');
    }

    // function to add media for model
    public function addMedia($file, $index = 1, $entity = null)
    {
        if (blank($file)) {
            return null;
        }

        if(is_array($file)){
            for($i = 0 ; $i < count($file) ; $i++){
                $media[] = $this->addFile($file[$i], $i, is_array($entity) ? $entity[$i] : $entity);
            }
            return $media;
        }else{
            $this->addFile($file, $index, $entity);
        }
    }

    // function to update media of model
    public function syncMedia($file , $soft_delete  = false)
    {
        if (blank($file)) {
            return null;
        }

        if($soft_delete){
            $this->media()->delete();
        }else{
            $this->destroyMedia();
            $this->media()->forceDelete();
        }
        return $this->addMedia($file , 0);
    }

    // function to Force delete of model media
    public function destroyMedia(){
        $medias = $this->media;
        if (blank($medias)) {
            return null;
        }
        if(is_array($medias)){
            foreach($medias as $media){
                Utilities::deleteFile($media);
            }
        }else{
            Utilities::deleteFile($medias);
        }

        $this->media()->forceDelete();
    }

    private function addFile($file , $index , $entity){
        $Image = Str::random(32) . time() . '.' .$file->extension();
        $destinationPath = 'public/upload';
        $file->storeAs($destinationPath, $Image);
        $mime = $file->getClientMimeType();
        $buket = Utilities::getBuket( str_replace('/' . basename($mime),'',$mime));
        return $this->media()->create([
            'path' => $Image,
            'index' => $index,
            'entity' => $entity,
            'file_name' => $file->getClientOriginalName(),
            'buket' => $buket,
            'mime_type' => basename($mime),
            'file_size' => filesize($file),
            'user_id' => Auth::check() ? Auth::id() : null,
        ]);
    }
}
