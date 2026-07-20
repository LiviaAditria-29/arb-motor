{{-- resources/views/chatbot/index.blade.php
     Halaman chatbot - integrasi Flowise AI atau fallback UI --}}
@extends('layouts.app')
@section('title', 'Chatbot ARB Motor')

@push('styles')
<style>
.page-hero{background:linear-gradient(135deg,#0F172A 0%,#1E293B 100%);padding:7rem 0 3.5rem;margin-top:-1px;}
.chat-container{max-width:800px;margin:0 auto;background:#fff;border:1px solid #E2E8F0;border-radius:1.5rem;overflow:hidden;box-shadow:0 20px 60px rgba(15,23,42,.08);}
.chat-header{background:linear-gradient(135deg,#0F172A,#1E293B);padding:1.25rem 1.5rem;display:flex;align-items:center;gap:1rem;}
.chat-body{height:500px;overflow-y:auto;padding:1.5rem;display:flex;flex-direction:column;gap:1rem;background:#F8FAFC;}
.chat-input-area{padding:1rem 1.25rem;border-top:1px solid #E2E8F0;background:#fff;display:flex;gap:.75rem;}
.chat-input{flex:1;border:1.5px solid #E2E8F0;border-radius:.875rem;padding:.7rem 1rem;font-size:.9rem;outline:none;transition:border-color .2s;font-family:inherit;}
.chat-input:focus{border-color:#F97316;box-shadow:0 0 0 3px rgba(249,115,22,.1);}
.msg{max-width:75%;padding:.75rem 1rem;border-radius:1rem;font-size:.9rem;line-height:1.5;animation:msgIn .3s ease;}
.msg-user{background:#F97316;color:#fff;align-self:flex-end;border-bottom-right-radius:.25rem;}
.msg-bot{background:#fff;color:#1E293B;align-self:flex-start;border:1px solid #E2E8F0;border-bottom-left-radius:.25rem;box-shadow:0 2px 8px rgba(0,0,0,.05);}
.msg-time{font-size:.7rem;opacity:.6;margin-top:.3rem;}
.typing-dot{display:inline-block;width:8px;height:8px;border-radius:50%;background:#94A3B8;animation:bounce 1.2s infinite;}
.typing-dot:nth-child(2){animation-delay:.2s;}
.typing-dot:nth-child(3){animation-delay:.4s;}
@keyframes bounce{0%,60%,100%{transform:translateY(0)}30%{transform:translateY(-8px)}}
@keyframes msgIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
.quick-btn{background:#fff;border:1.5px solid #E2E8F0;border-radius:9999px;padding:.4rem .9rem;font-size:.8rem;font-weight:600;color:#475569;cursor:pointer;transition:all .2s;white-space:nowrap;}
.quick-btn:hover{border-color:#F97316;color:#F97316;background:#FFF0E6;}
</style>
@endpush

@section('content')

<div class="page-hero">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <span class="inline-flex items-center gap-2 bg-orange-500/15 text-orange-400 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">🤖 Chatbot AI</span>
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-3">Asisten Otomotif Pintar</h1>
        <p class="text-slate-400 text-lg max-w-lg mx-auto">Tanya apa saja seputar perawatan mobil, spare part, atau booking servis — kami siap membantu 24/7</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Chat UI --}}
    <div class="chat-container">

        {{-- Header --}}
        <div class="chat-header">
            <div class="w-11 h-11 bg-orange-500 rounded-full flex items-center justify-center text-white text-xl">🤖</div>
            <div>
                <p class="font-semibold text-white text-sm">ARB Motor Assistant</p>
                <div class="flex items-center gap-1.5">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <p class="text-slate-400 text-xs">Online – Siap membantu</p>
                </div>
            </div>
        </div>

        {{-- Messages --}}
        <div class="chat-body" id="chat-body">
            <div class="msg msg-bot">
                <p>Halo! Saya asisten ARB Motor 👋</p>
                <p class="mt-1">Saya bisa membantu kamu dengan:</p>
                <ul class="mt-2 space-y-1">
                    <li>🔧 Informasi layanan servis</li>
                    <li>⚙️ Cek spare part & harga</li>
                    <li>📅 Booking jadwal servis</li>
                    <li>💡 Tips perawatan kendaraan</li>
                </ul>
                <p class="mt-2">Silakan ketik pertanyaan kamu!</p>
                <p class="msg-time">{{ now()->format('H:i') }}</p>
            </div>
        </div>

        {{-- Quick Replies --}}
        <div class="px-4 py-3 border-t border-slate-100 flex flex-wrap gap-2" id="quick-replies">
            <button class="quick-btn" onclick="sendQuick(this)">💰 Harga ganti oli</button>
            <button class="quick-btn" onclick="sendQuick(this)">📅 Cara booking servis</button>
            <button class="quick-btn" onclick="sendQuick(this)">⚙️ Cek spare part</button>
            <button class="quick-btn" onclick="sendQuick(this)">🕐 Jam operasional</button>
            <button class="quick-btn" onclick="sendQuick(this)">📍 Lokasi bengkel</button>
        </div>

        {{-- Input --}}
        <div class="chat-input-area">
            <input type="text" id="chat-input" class="chat-input" placeholder="Ketik pesan...">
            <button onclick="sendMessage()" id="send-btn"
                    class="btn-orange px-5 py-2.5 rounded-xl flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8">
        @foreach([
            ['icon'=>'🕐', 'title'=>'Respon Cepat', 'desc'=>'Bot merespons dalam hitungan detik'],
            ['icon'=>'📅', 'title'=>'Booking Mudah', 'desc'=>'Langsung booking jadwal via chat'],
            ['icon'=>'💬', 'title'=>'Atau via WhatsApp', 'desc'=>'+62 812-3456-7890', 'link'=>true],
        ] as $info)
        <div class="bg-white border border-slate-100 rounded-xl p-5 text-center shadow-sm">
            <div class="text-3xl mb-2">{{ $info['icon'] }}</div>
            <p class="font-semibold text-slate-900 text-sm mb-1">{{ $info['title'] }}</p>
            @if(isset($info['link']))
                <a href="https://wa.me/628123456789" target="_blank" class="text-xs text-orange-500 hover:underline">{{ $info['desc'] }}</a>
            @else
                <p class="text-xs text-slate-500">{{ $info['desc'] }}</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Simpel chat responses (dapat diganti dengan Flowise API) ──
const responses = {
    'oli':       'Layanan ganti oli tersedia mulai <strong>Rp 50.000</strong> dengan estimasi waktu 30 menit. Kami menggunakan oli original berkualitas tinggi. Mau langsung booking? <a href="/services" class="text-orange-500 underline">Lihat Layanan →</a>',
    'harga':     'Harga layanan kami: Ganti Oli <strong>Rp 50.000</strong>, Servis Ringan <strong>Rp 100.000</strong>, Servis Berat <strong>Rp 300.000</strong>. <a href="/services" class="text-orange-500 underline">Lihat semua layanan →</a>',
    'booking':   'Cara booking: 1) Pilih layanan di halaman Layanan, 2) Klik "Booking Sekarang", 3) Konfirmasi via WhatsApp. Atau langsung <a href="https://wa.me/628123456789" class="text-orange-500 underline" target="_blank">chat WhatsApp</a>!',
    'spare':     'Kami menyediakan berbagai spare part original seperti oli mesin, filter, kampas rem, busi, aki, dan lainnya. <a href="/spare-parts" class="text-orange-500 underline">Lihat katalog →</a>',
    'jam':       'Jam operasional kami: <br>Senin–Jumat: <strong>08:00–17:00</strong><br>Sabtu: <strong>08:00–14:00</strong><br>Minggu: <strong>Tutup</strong>',
    'lokasi':    'Kami berlokasi di <strong>Jl. Raya Motor No.123, Jakarta</strong>. Mudah dijangkau dengan kendaraan pribadi maupun transportasi umum.',
    'servis':    'Kami menyediakan berbagai layanan: Ganti Oli, Servis Ringan, Servis Berat, Tune Up, dan lainnya. Semua dikerjakan teknisi bersertifikat. <a href="/services" class="text-orange-500 underline">Lihat detail →</a>',
    'default':   'Maaf, saya belum mengerti pertanyaan itu. Coba tanyakan tentang: <strong>harga, booking, layanan, spare part, jam buka, atau lokasi bengkel</strong>. Atau hubungi kami langsung via <a href="https://wa.me/628123456789" class="text-orange-500 underline" target="_blank">WhatsApp</a>.',
};

function getResponse(text) {
    text = text.toLowerCase();
    if (text.includes('oli')) return responses.oli;
    if (text.includes('harga') || text.includes('biaya') || text.includes('tarif')) return responses.harga;
    if (text.includes('booking') || text.includes('daftar') || text.includes('jadwal')) return responses.booking;
    if (text.includes('spare') || text.includes('part') || text.includes('onderdil')) return responses.spare;
    if (text.includes('jam') || text.includes('buka') || text.includes('tutup') || text.includes('operasional')) return responses.jam;
    if (text.includes('lokasi') || text.includes('alamat') || text.includes('dimana')) return responses.lokasi;
    if (text.includes('servis') || text.includes('layanan') || text.includes('ganti')) return responses.servis;
    return responses.default;
}

function appendMsg(content, type) {
    const body = document.getElementById('chat-body');
    const div = document.createElement('div');
    div.className = 'msg msg-' + type;
    div.innerHTML = content + '<p class="msg-time">' + new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'}) + '</p>';
    body.appendChild(div);
    body.scrollTo({ top: body.scrollHeight, behavior: 'smooth' });
    return div;
}

function showTyping() {
    return appendMsg('<span class="flex gap-1 py-1"><span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></span>', 'bot');
}

async function sendMessage() {
    const input = document.getElementById('chat-input');
    const text = input.value.trim();
    if (!text) return;
    input.value = '';
    document.getElementById('quick-replies').style.display = 'none';

    appendMsg(text, 'user');
    const typing = showTyping();

    await new Promise(r => setTimeout(r, 900 + Math.random() * 600));
    typing.remove();
    appendMsg(getResponse(text), 'bot');
}

document.getElementById('chat-input').addEventListener('keypress', e => {
    if (e.key === 'Enter') sendMessage();
});

function sendQuick(btn) {
    document.getElementById('chat-input').value = btn.textContent.trim().replace(/^[^\w\s]+\s*/, '');
    sendMessage();
}
</script>
@endpush
