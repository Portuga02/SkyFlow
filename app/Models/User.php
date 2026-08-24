<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = array(
        'name',
        'email',
        'password',
        'team_id',
        'avatar_path',
        'role',
        'theme_color',
        'view_mode',
        'theme_color',
    );

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = array(
        'password',
        'remember_token',
    );

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = array(
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    );
    public function categories()
    {
        return $this->hasMany(\App\Models\Category::class);
    }
    public function notes()
    {
        return $this->hasMany(\App\Models\Note::class);
    }

    public function todos()
    {
        return $this->hasMany(\App\Models\Todo::class);
    }
    public function kanbanColumns()
    {
        return $this->hasMany(KanbanColumn::class, 'user_id');
    }
    public function getAvatarUrlAttribute(): string
    {

        if (!empty($this->avatar)) {
            return Storage::url($this->avatar);
        }

        if (!empty($this->profile_photo_path)) {
            return Storage::url($this->profile_photo_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF&bold=true';
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
