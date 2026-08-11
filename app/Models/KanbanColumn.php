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
    ];

    // Uma coluna tem várias tarefas (todos)
    public function todos()
    {
        return $this->hasMany(Todo::class, 'status', 'slug');
    }
}