<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LokasiTps extends Model
{
    protected $table = 'lokasi_tps';

    protected $fillable = ['nama', 'alamat', 'kapasitas', 'jam_operasional'];

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'lokasi_id');
    }

    // Label yang bisa diterjemahkan
    public function getTranslatedName(): string
    {
        return __($this->nama);
    }
}
