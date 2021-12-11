<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Todo extends Model
{
    use HasFactory;
    protected $fillable = ['content', 'user_id','completion_time'];

    protected static function booted()
    {
        if (!app()->runningInConsole() && auth()->check()) {
            static::creating(function ($model) {
                $model->user_id = auth()->id();
            });
        }
    }


    public function isCompleted()
    {
        return $this->completion_time != null;
    }

    public function isPending()
    {
        return $this->completion_time == null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function isOwner(User $user)
    {
        return (int)$this->user_id === (int)$user->id;
    }
}
