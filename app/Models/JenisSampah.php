<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSampah extends Model
{
    protected $table = 'jenis_sampah';

    protected $fillable = ['nama', 'deskripsi'];

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class);
    }

    // Label yang bisa diterjemahkan
    public function getTranslatedName(): string
    {
        return __($this->nama);
    }
}
