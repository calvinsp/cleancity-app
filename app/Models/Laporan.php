<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'user_id',
        'jenis_sampah_id',
        'lokasi_id',
        'foto',
        'deskripsi',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jenisSampah(): BelongsTo
    {
        return $this->belongsTo(JenisSampah::class, 'jenis_sampah_id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(LokasiTps::class, 'lokasi_id');
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'pending'          => 'badge bg-warning',
            'diproses'         => 'badge bg-info',
            'selesai'          => 'badge bg-success',
            'request_selesai'  => 'badge bg-info',
            default            => 'badge bg-secondary',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending'          => __('Menunggu'),
            'diproses'         => __('Sedang Diproses'),
            'selesai'          => __('Selesai'),
            'request_selesai'  => __('Menunggu Konfirmasi'),
            default            => __('Tidak Diketahui'),
        };
    }
}
