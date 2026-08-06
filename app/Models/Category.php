<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
        'color',
        'icon',
    ];

    /**
     * Usuário dono da categoria.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Categoria "mãe" (quando este registro é uma subcategoria).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Subcategorias filhas desta categoria.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Tarefas vinculadas a esta categoria.
     */
  public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    /**
     * Categorias "raiz" (sem categoria pai) — usado pra montar a árvore no menu.
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }
}