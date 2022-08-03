<?php
namespace Waad\RepoMedia\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;

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
        'created_at',
        'updated_at'
    ];

    protected function serializeDate(DateTimeInterface $date){
        return $date->format('Y-m-d h:i:s a');
    }

    protected $hidden = ['deleted_at','model_id','model_type'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function setUserIdAtttribute($value){
        $this->atttributes['user_id'] = Auth::check() ? auth()->user()->id : null;
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
