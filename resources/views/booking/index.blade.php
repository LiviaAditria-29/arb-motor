{{-- resources/views/booking/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Booking Servis')

@push('styles')
<style>
.page-hero{background:linear-gradient(135deg,#0F172A 0%,#1E293B 100%);padding:7rem 0 3.5rem;margin-top:-1px;}
.form-card{background:#fff;border:1px solid #E2E8F0;border-radius:1.5rem;padding:2rem;}
.form-label{display:block;font-size:.8rem;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem;}
.form-input{width:100%;border:1.5px solid #E2E8F0;border-radius:.875rem;padding:.75rem 1rem;font-size:.9rem;outline:none;transition:border-color .2s;background:#fff;}
.form-input:focus{border-color:#F97316;box-shadow:0 0 0 3px rgba(249,115,22,.1);}
.form-error{color:#EF4444;font-size:.75rem;margin-top:.3rem;}
.step-dot{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;flex-shrink:0;transition:all .3s;}
.step-dot.active{background:#F97316;color:#fff;box-shadow:0 4px 12px rgba(249,115,22,.4);}
.step-dot.done{background:#16A34A;color:#fff;}
.step-dot.pending{background:#F1F5F9;color:#94A3B8;}
.time-slot{border:1.5px solid #E2E8F0;border-radius:.75rem;padding:.65rem 1rem;font-size:.875rem;font-weight:500;color:#475569;cursor:pointer;transition:all .2s;text-align:center;}
.time-slot:hover{border-color:#F97316;color:#F97316;}
.time-slot.selected{border-color:#F97316;background:#FFF0E6;color:#F97316;font-weight:700;}
.time-slot.disabled{opacity:.4;cursor:not-allowed;pointer-events:none;}
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
        <span class="inline-flex items-center gap-2 bg-orange-500/15 text-orange-400 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">📅 Booking Servis</span>
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-3">Jadwalkan Servis Mobil</h1>
        <p class="text-slate-400 text-lg">Pilih layanan, tentukan jadwal, dan kami siap melayani</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Steps --}}
    <div class="flex items-center gap-0 mb-10" id="steps">
        @php $steps = ['Pilih Layanan','Isi Data','Konfirmasi']; @endphp
        @foreach($steps as $i => $step)
        <div class="flex items-center {{ $i < count($steps)-1 ? 'flex-1' : '' }}">
            <div class="flex flex-col items-center gap-1">
                <div class="step-dot {{ $i===0 ? 'active' : 'pending' }}" id="step-dot-{{ $i }}">{{ $i+1 }}</div>
                <span class="text-xs font-medium {{ $i===0 ? 'text-orange-500' : 'text-slate-400' }}" id="step-label-{{ $i }}">{{ $step }}</span>
            </div>
            @if($i < count($steps)-1)
            <div class="flex-1 h-px bg-slate-200 mb-4 mx-2" id="step-line-{{ $i }}"></div>
            @endif
        </div>
        @endforeach
    </div>

    <form method="POST" action="#" id="booking-form">
        @csrf

        {{-- STEP 1: Pilih Layanan --}}
        <div id="step-1" class="form-card">
            <h2 class="font-display font-bold text-xl text-slate-900 mb-6">Pilih Layanan</h2>

            <div class="space-y-3" id="service-list">
                @foreach($services ?? [] as $service)
                <label class="flex items-start gap-4 p-4 border-1.5 border-slate-200 rounded-xl cursor-pointer hover:border-orange-400 hover:bg-orange-50/50 transition-all group has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                    <input type="radio" name="service_id" value="{{ $service->id }}" class="mt-1 accent-orange-500">
                    <div class="text-2xl">{{ $service->icon_emoji }}</div>
                    <div class="flex-1">
                        <p class="font-semibold text-slate-900">{{ $service->name }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ Str::limit($service->description, 80) }}</p>
                        <div class="flex gap-4 mt-2">
                            <span class="text-orange-500 font-bold text-sm">{{ $service->formatted_price }}</span>
                            <span class="text-slate-400 text-xs">⏱ {{ $service->duration_minutes }} menit</span>
                        </div>
                    </div>
                </label>
                @endforeach

                {{-- Fallback jika tidak ada services --}}
                @if(empty($services) || count($services) === 0)
                @foreach([
                    ['name'=>'Ganti Oli','desc'=>'Penggantian oli mesin kendaraan','price'=>'Rp 50.000','duration'=>'30','emoji'=>'🛢️'],
                    ['name'=>'Servis Ringan','desc'=>'Pengecekan dan perawatan ringan','price'=>'Rp 100.000','duration'=>'60','emoji'=>'🔧'],
                    ['name'=>'Servis Berat','desc'=>'Perbaikan menyeluruh kendaraan','price'=>'Rp 300.000','duration'=>'120','emoji'=>'⚙️'],
                ] as $s)
                <label class="flex items-start gap-4 p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-orange-400 hover:bg-orange-50/50 transition-all">
                    <input type="radio" name="service_name" value="{{ $s['name'] }}" class="mt-1 accent-orange-500">
                    <div class="text-2xl">{{ $s['emoji'] }}</div>
                    <div class="flex-1">
                        <p class="font-semibold text-slate-900">{{ $s['name'] }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $s['desc'] }}</p>
                        <div class="flex gap-4 mt-2">
                            <span class="text-orange-500 font-bold text-sm">{{ $s['price'] }}</span>
                            <span class="text-slate-400 text-xs">⏱ {{ $s['duration'] }} menit</span>
                        </div>
                    </div>
                </label>
                @endforeach
                @endif
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" onclick="nextStep(2)" class="btn-orange px-8 py-3">
                    Lanjut → Isi Data
                </button>
            </div>
        </div>

        {{-- STEP 2: Isi Data Diri --}}
        <div id="step-2" class="form-card hidden">
            <h2 class="font-display font-bold text-xl text-slate-900 mb-6">Data Diri & Kendaraan</h2>
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" class="form-input" placeholder="Nama lengkap Anda" required>
                    </div>
                    <div>
                        <label class="form-label">No. HP / WhatsApp <span class="text-red-500">*</span></label>
                        <input type="tel" name="customer_phone" class="form-input" placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">Nama / Jenis Kendaraan <span class="text-red-500">*</span></label>
                    <input type="text" name="vehicle_name" class="form-input" placeholder="Cth: Toyota Avanza 2020" required>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tanggal Booking <span class="text-red-500">*</span></label>
                        <input type="date" name="booking_date" class="form-input" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    <div>
                        <label class="form-label">Pilih Jam</label>
                        <div class="grid grid-cols-3 gap-2 mt-1" id="time-slots">
                            @foreach(['08:00','09:00','10:00','11:00','13:00','14:00','15:00','16:00'] as $t)
                            <button type="button" class="time-slot" data-time="{{ $t }}" onclick="selectTime(this, '{{ $t }}')">{{ $t }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="time_slot" id="time_slot_input">
                    </div>
                </div>
                <div>
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="notes" rows="3" class="form-input resize-none" placeholder="Keluhan atau informasi tambahan..."></textarea>
                </div>
            </div>
            <div class="flex justify-between mt-6">
                <button type="button" onclick="prevStep(1)" class="btn-outline px-6 py-2.5 text-sm">← Kembali</button>
                <button type="button" onclick="nextStep(3)" class="btn-orange px-8 py-3">Lanjut → Konfirmasi</button>
            </div>
        </div>

        {{-- STEP 3: Konfirmasi --}}
        <div id="step-3" class="form-card hidden">
            <h2 class="font-display font-bold text-xl text-slate-900 mb-6">Konfirmasi Booking</h2>

            <div class="bg-orange-50 border border-orange-100 rounded-2xl p-5 mb-6">
                <h3 class="font-semibold text-orange-800 mb-4 text-sm">Ringkasan Booking</h3>
                <div class="space-y-2 text-sm" id="summary">
                    <div class="flex justify-between"><span class="text-slate-600">Layanan</span><span class="font-semibold text-slate-900" id="sum-service">-</span></div>
                    <div class="flex justify-between"><span class="text-slate-600">Pelanggan</span><span class="font-semibold text-slate-900" id="sum-name">-</span></div>
                    <div class="flex justify-between"><span class="text-slate-600">Kendaraan</span><span class="font-semibold text-slate-900" id="sum-vehicle">-</span></div>
                    <div class="flex justify-between"><span class="text-slate-600">Tanggal</span><span class="font-semibold text-slate-900" id="sum-date">-</span></div>
                    <div class="flex justify-between"><span class="text-slate-600">Jam</span><span class="font-semibold text-slate-900" id="sum-time">-</span></div>
                </div>
            </div>

            <p class="text-xs text-slate-500 mb-5">Setelah submit, kami akan menghubungi Anda via WhatsApp untuk konfirmasi jadwal.</p>

            <div class="flex justify-between">
                <button type="button" onclick="prevStep(2)" class="btn-outline px-6 py-2.5 text-sm">← Kembali</button>
                <button type="submit" class="btn-orange px-8 py-3">
                    ✅ Kirim Booking
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let currentStep = 1;

function nextStep(step) {
    document.getElementById('step-' + currentStep).classList.add('hidden');
    document.getElementById('step-' + step).classList.remove('hidden');

    // Update dots
    document.getElementById('step-dot-' + (currentStep-1)).className = 'step-dot done';
    document.getElementById('step-label-' + (currentStep-1)).className = 'text-xs font-medium text-green-600';
    document.getElementById('step-dot-' + (step-1)).className = 'step-dot active';
    document.getElementById('step-label-' + (step-1)).className = 'text-xs font-medium text-orange-500';

    if (step === 3) updateSummary();
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(step) {
    document.getElementById('step-' + currentStep).classList.add('hidden');
    document.getElementById('step-' + step).classList.remove('hidden');
    document.getElementById('step-dot-' + (currentStep-1)).className = 'step-dot pending';
    document.getElementById('step-label-' + (currentStep-1)).className = 'text-xs font-medium text-slate-400';
    document.getElementById('step-dot-' + (step-1)).className = 'step-dot active';
    document.getElementById('step-label-' + (step-1)).className = 'text-xs font-medium text-orange-500';
    currentStep = step;
}

function selectTime(btn, time) {
    document.querySelectorAll('.time-slot').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('time_slot_input').value = time;
}

function updateSummary() {
    const r = document.querySelector('input[name="service_name"]:checked') || document.querySelector('input[name="service_id"]:checked');
    document.getElementById('sum-service').textContent = r ? (r.closest('label').querySelector('.font-semibold').textContent) : '-';
    document.getElementById('sum-name').textContent    = document.querySelector('[name="customer_name"]').value || '-';
    document.getElementById('sum-vehicle').textContent = document.querySelector('[name="vehicle_name"]').value || '-';
    document.getElementById('sum-date').textContent    = document.querySelector('[name="booking_date"]').value || '-';
    document.getElementById('sum-time').textContent    = document.getElementById('time_slot_input').value || '-';
}
</script>
@endpush
