<?php

namespace App\Services;

use Illuminate\Session\DatabaseSessionHandler;

class CustomDatabaseSessionHandler extends DatabaseSessionHandler
{
    /**
     * Memperbarui data session di database termasuk kolom kustom tambahan.
     */
    protected function performInsert($id, $payload)
    {
        $payload['last_active_at'] = now(); // Mengisi waktu saat ini (Y-m-d H:i:s)

        return parent::performInsert($id, $payload);
    }

    protected function performUpdate($id, $payload)
    {
        $payload['last_active_at'] = now(); // Memperbarui waktu detail saat user aktif kembali

        return parent::performUpdate($id, $payload);
    }
}