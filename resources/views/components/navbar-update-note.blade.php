{{-- ============================================================
     CATATAN: Ini adalah POTONGAN kode untuk update navbar di
     resources/views/layouts/app.blade.php

     Ganti bagian $navLinks dengan versi di bawah ini
     agar navbar juga include Booking dan Chatbot
     ============================================================ --}}

{{--
@php
    $navLinks = [
        ['href' => route('home'),              'label' => 'Beranda',    'name' => 'home'],
        ['href' => route('services.index'),    'label' => 'Layanan',    'name' => 'services*'],
        ['href' => route('spare-parts.index'), 'label' => 'Spare Part', 'name' => 'spare-parts*'],
        ['href' => route('chatbot.index'),     'label' => 'Chatbot',    'name' => 'chatbot*'],
    ];
@endphp
--}}

{{-- Dan di mobile menu, tambahkan: --}}
{{--
<a href="{{ route('chatbot.index') }}" class="block text-slate-300 hover:text-white py-2 text-sm font-medium">Chatbot</a>
<a href="{{ route('booking.index') }}" class="block text-slate-300 hover:text-white py-2 text-sm font-medium">Booking</a>
--}}

{{-- ============================================================
     CATATAN: Untuk mengganti CTA button "Booking Sekarang" di
     navbar menjadi link ke halaman booking, update:

     Dari: <a href="{{ route('services.index') }}" class="btn-orange text-sm">
     Ke:   <a href="{{ route('booking.index') }}" class="btn-orange text-sm">
     ============================================================ --}}
