<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'color',
        'order',
        'icon',
        'team_id'
    ];
    public function todos()
    {
        return $this->hasMany(Todo::class, 'status', 'slug');
    }
}