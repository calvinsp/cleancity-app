<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role_id'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function role(): BelongsTo {
        return $this->belongsTo(Role::class);
    }

    public function laporan(): HasMany {
        return $this->hasMany(Laporan::class);
    }

    public function pengumuman(): HasMany {
        return $this->hasMany(Pengumuman::class, 'admin_id');
    }

    public function isAdmin(): bool {
        return $this->role->role_name === 'admin';
    }

    public function isPetugas(): bool {
        return $this->role->role_name === 'petugas';
    }

    public function isUser(): bool {
        return $this->role->role_name === 'user';
    }
}
