<?php
// ============================================================
// FILE: app/Http/Controllers/ChatbotController.php
// ============================================================

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }

    /**
     * Handle AJAX chat request (opsional – jika ingin integrasi API)
     * Saat ini menggunakan JS-based responses di frontend
     */
    public function reply(Request $request)
    {
        $message = strtolower(trim($request->input('message', '')));

        $reply = $this->getReply($message);

        return response()->json(['reply' => $reply]);
    }

    private function getReply(string $msg): string
    {
        if (str_contains($msg, 'oli'))                            return 'Layanan ganti oli mulai Rp 50.000, estimasi 30 menit.';
        if (str_contains($msg, 'harga') || str_contains($msg, 'biaya'))  return 'Ganti Oli: Rp 50.000 | Servis Ringan: Rp 100.000 | Servis Berat: Rp 300.000';
        if (str_contains($msg, 'booking') || str_contains($msg, 'jadwal')) return 'Silakan kunjungi halaman Booking atau hubungi WhatsApp kami.';
        if (str_contains($msg, 'spare') || str_contains($msg, 'part'))    return 'Kami sediakan oli, filter, kampas rem, busi, aki, dan lainnya.';
        if (str_contains($msg, 'jam') || str_contains($msg, 'buka'))      return 'Senin–Jumat 08:00–17:00, Sabtu 08:00–14:00, Minggu tutup.';
        if (str_contains($msg, 'lokasi') || str_contains($msg, 'alamat')) return 'Jl. Raya Motor No.123, Jakarta.';

        return 'Maaf, saya belum mengerti. Coba tanya soal harga, booking, layanan, spare part, jam buka, atau lokasi.';
    }
}
