<?php

namespace App\Notifications;

use App\Models\Laporan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LaporanSelesaiNotification extends Notification
{
    use Queueable;

    public function __construct(public Laporan $laporan)
    {
        //
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // Ambil locale dari user (kalau ada) atau pakai default app
        $locale = $notifiable->preferred_locale ?? config('app.locale');

        return [
            'laporan_id' => $this->laporan->id,
            'title'      => __('Permintaan selesai dari petugas', [], $locale),
            'message'    => __('Petugas meminta konfirmasi selesai untuk laporan #:id.', [
                'id' => $this->laporan->id,
            ], $locale),
            'url'        => route('laporan.show', [
                'locale'  => $locale,
                'laporan' => $this->laporan->id,
            ]),
        ];
    }
}
