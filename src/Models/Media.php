<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Waad\Pattern\Models\ModelTrait;
use Illuminate\Support\Facades\Auth;

class Media extends Model
{
    use ModelTrait,SoftDeletes;

    protected $fillable = [
        'id',
        'model_id',
        'model_type',
        'path',
        'bio',
        'file_name',
        'file_type',
        'mime_type',
        'file_size',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $relations = [];
    protected $hidden = ['deleted_at'];
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
        return $this->morphTo('media');
    }


}
