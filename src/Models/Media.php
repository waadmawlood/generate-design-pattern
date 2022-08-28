<?php
namespace Waad\RepoMedia\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Waad\RepoMedia\Helpers\Utilities;

class Media extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'model_id',
        'model_type',
        'path',
        'index',
        'file_name',
        'buket',
        'mime_type',
        'file_size',
        'status',
        'user_id',
        'created_at',
        'updated_at'
    ];

    protected $appends = ['file_path'];

    protected function serializeDate(DateTimeInterface $date){
        return $date->format('Y-m-d h:i:s a');
    }

    protected $hidden = ['deleted_at','model_id','model_type'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $relations = ['model'];

    public function getFilePathAttribute(){
        return Utilities::domain() . '/' . $this->attributes['buket'] . '/' . $this->attributes['path'];
    }

    // morph relationship of all models
    public function model()
    {
        return $this->morphTo('model');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
