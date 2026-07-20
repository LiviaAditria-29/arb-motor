{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Dashboard') — ARB Motor Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
*{box-sizing:border-box;}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;margin:0;}
.font-display{font-family:'Space Grotesk',sans-serif;}
:root{--navy:#0F172A;--navy2:#1E293B;--orange:#F97316;}

/* SIDEBAR */
.sidebar{position:fixed;left:0;top:0;bottom:0;width:256px;background:var(--navy);overflow-y:auto;z-index:50;transition:transform .3s ease;display:flex;flex-direction:column;}
.sidebar-logo{padding:1.25rem 1.5rem;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:.75rem;flex-shrink:0;}
.sidebar-nav{padding:.75rem;flex:1;overflow-y:auto;}
.nav-section{font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#475569;padding:.5rem .75rem;margin-top:.75rem;margin-bottom:.25rem;}
.nav-link{display:flex;align-items:center;gap:.75rem;padding:.6rem .875rem;border-radius:.75rem;font-size:.845rem;font-weight:500;color:#94A3B8;transition:all .2s;margin-bottom:.1rem;text-decoration:none;}
.nav-link:hover{background:rgba(249,115,22,.1);color:#FB923C;}
.nav-link.active{background:rgba(249,115,22,.15);color:#F97316;}
.nav-link svg{width:1.1rem;height:1.1rem;flex-shrink:0;}
.nav-badge{margin-left:auto;background:rgba(249,115,22,.2);color:#FB923C;font-size:.65rem;font-weight:700;padding:.1rem .45rem;border-radius:9999px;}

/* TOPBAR */
.topbar{background:#fff;border-bottom:1px solid #E2E8F0;position:sticky;top:0;z-index:30;padding:.875rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;}

/* MAIN */
.main-content{margin-left:256px;min-height:100vh;display:flex;flex-direction:column;}
.page-content{padding:1.75rem;flex:1;}

/* TOAST */
#toast-c{position:fixed;top:1.25rem;right:1.25rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;}
.toast-msg{padding:.75rem 1.1rem;border-radius:.75rem;color:#fff;font-size:.8rem;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,.18);display:flex;align-items:center;gap:.5rem;animation:toastIn .35s ease;}
@keyframes toastIn{from{opacity:0;transform:translateX(16px)}to{opacity:1;transform:translateX(0)}}

/* CARDS */
.stat-card{background:#fff;border:1px solid #E2E8F0;border-radius:1rem;padding:1.5rem;}
.stat-card:hover{box-shadow:0 8px 24px rgba(15,23,42,.08);}

/* FORMS */
.f-label{display:block;font-size:.75rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.375rem;}
.f-input{width:100%;border:1.5px solid #E2E8F0;border-radius:.75rem;padding:.65rem .9rem;font-size:.875rem;outline:none;transition:border-color .2s;background:#fff;font-family:inherit;}
.f-input:focus{border-color:var(--orange);box-shadow:0 0 0 3px rgba(249,115,22,.1);}
.f-input.err{border-color:#EF4444;}
.f-error{color:#EF4444;font-size:.72rem;margin-top:.25rem;}
.f-select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 24 16'%3E%3Cpath d='M12 16L0 0h24z' fill='%2394A3B8'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right .875rem center;padding-right:2.5rem;}

/* BUTTONS */
.btn{display:inline-flex;align-items:center;gap:.4rem;font-weight:600;border-radius:.65rem;padding:.55rem 1.1rem;font-size:.845rem;transition:all .2s;cursor:pointer;border:none;text-decoration:none;}
.btn-primary{background:var(--orange);color:#fff;} .btn-primary:hover{background:#EA6C0A;transform:translateY(-1px);}
.btn-secondary{background:#F1F5F9;color:#475569;} .btn-secondary:hover{background:#E2E8F0;}
.btn-danger{background:#EF4444;color:#fff;} .btn-danger:hover{background:#DC2626;}
.btn-success{background:#16A34A;color:#fff;} .btn-success:hover{background:#15803D;}
.btn-outline{border:1.5px solid #E2E8F0;background:transparent;color:#64748B;} .btn-outline:hover{border-color:#94A3B8;background:#F8FAFC;}
.btn-sm{padding:.35rem .75rem;font-size:.775rem;}

/* TABLE */
.tbl{width:100%;border-collapse:collapse;}
.tbl th{text-align:left;padding:.7rem 1rem;font-size:.72rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.06em;background:#F8FAFC;border-bottom:1px solid #E2E8F0;}
.tbl td{padding:.8rem 1rem;font-size:.845rem;color:#334155;border-bottom:1px solid #F1F5F9;vertical-align:middle;}
.tbl tr:last-child td{border-bottom:none;}
.tbl tr:hover td{background:#FAFAFA;}

/* STATUS BADGES */
.badge{display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .65rem;border-radius:9999px;font-size:.7rem;font-weight:700;}
.badge-yellow{background:#FEF3C7;color:#B45309;}
.badge-blue{background:#DBEAFE;color:#1D4ED8;}
.badge-indigo{background:#EDE9FE;color:#6D28D9;}
.badge-green{background:#DCFCE7;color:#15803D;}
.badge-teal{background:#CCFBF1;color:#0D9488;}
.badge-red{background:#FEE2E2;color:#B91C1C;}
.badge-gray{background:#F1F5F9;color:#475569;}
.badge-orange{background:#FFF0E6;color:#C2410C;}

/* MOBILE SIDEBAR */
@media(max-width:1023px){
    .sidebar{transform:translateX(-100%);}
    .sidebar.open{transform:translateX(0);}
    .main-content{margin-left:0;}
    .sidebar-overlay{display:block;}
}
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:40;}
</style>
@stack('styles')
</head>
<body>

{{-- Sidebar Overlay (mobile) --}}
<div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div style="width:36px;height:36px;background:#F97316;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.8rem;flex-shrink:0;">ARB</div>
        <div>
            <p class="font-display" style="color:#fff;font-weight:700;font-size:.95rem;line-height:1;">Motor</p>
            <p style="color:#64748B;font-size:.7rem;">Admin Panel</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Utama</div>

        <!-- <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Dashboard
        </a> -->

        <div class="nav-section">Kelola</div>

        <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Booking
            @php $pending = \App\Models\Booking::where('status','pending')->count(); @endphp
            @if($pending > 0)<span class="nav-badge">{{ $pending }}</span>@endif
        </a>

        <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Layanan
        </a>

        <a href="{{ route('admin.spare-parts.index') }}" class="nav-link {{ request()->routeIs('admin.spare-parts*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            Spare Part
        </a>

        <!-- <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Pelanggan
        </a> -->

        <div class="nav-section">Laporan</div>

        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Rekap & PDF
        </a>

        <div class="nav-section" style="margin-top:1rem;">Akun</div>

        <a href="{{ route('home') }}" target="_blank" class="nav-link">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Lihat Website
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-full text-left" style="background:none;border:none;cursor:pointer;width:100%;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </form>
    </nav>
</aside>

{{-- MAIN --}}
<div class="main-content">

    {{-- TOPBAR --}}
    <header class="topbar">
        <div style="display:flex;align-items:center;gap:.875rem;">
            <button onclick="toggleSidebar()" style="background:none;border:none;cursor:pointer;padding:.35rem;border-radius:.5rem;color:#64748B;" class="lg:hidden">
                <svg style="width:1.25rem;height:1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="font-display" style="font-size:.95rem;font-weight:700;color:#0F172A;line-height:1.2;">@yield('page-title','Dashboard')</h1>
                <p style="font-size:.72rem;color:#94A3B8;margin-top:1px;">@yield('page-sub', date('l, d F Y'))</p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:.875rem;">
            {{-- Notif pending --}}
            @if(isset($pending) && $pending > 0)
            <a href="{{ route('admin.bookings.index', ['status'=>'pending']) }}" style="position:relative;display:flex;align-items:center;">
                <svg style="width:1.25rem;height:1.25rem;color:#64748B;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span style="position:absolute;top:-4px;right:-4px;background:#EF4444;color:#fff;font-size:.6rem;font-weight:700;width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;">{{ $pending }}</span>
            </a>
            @endif
            <div style="display:flex;align-items:center;gap:.625rem;">
                <div style="width:34px;height:34px;background:#F97316;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;">
                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                </div>
                <div style="display:none;" class="sm-show">
                    <p style="font-size:.82rem;font-weight:600;color:#0F172A;line-height:1.2;">{{ Auth::user()->name }}</p>
                    <p style="font-size:.7rem;color:#94A3B8;">Administrator</p>
                </div>
            </div>
        </div>
    </header>

    {{-- Toast --}}
    <div id="toast-c"></div>
    @if(session('success'))<script>document.addEventListener('DOMContentLoaded',()=>showToast('{{ addslashes(session('success')) }}','success'));</script>@endif
    @if(session('error'))<script>document.addEventListener('DOMContentLoaded',()=>showToast('{{ addslashes(session('error')) }}','error'));</script>@endif

    {{-- Breadcrumb --}}
    @hasSection('breadcrumb')
    <div style="padding:.5rem 1.75rem;border-bottom:1px solid #E2E8F0;background:#fff;">
        <nav style="font-size:.78rem;color:#94A3B8;display:flex;align-items:center;gap:.4rem;">
            <a href="{{ route('admin.dashboard') }}" style="color:#64748B;text-decoration:none;hover:color:#F97316;">Dashboard</a>
            @yield('breadcrumb')
        </nav>
    </div>
    @endif

    {{-- Page Content --}}
    <div class="page-content">
        @yield('content')
    </div>
</div>

<script>
function toggleSidebar(){
    const s=document.getElementById('sidebar');
    const o=document.getElementById('overlay');
    const open=s.classList.toggle('open');
    o.style.display=open?'block':'none';
}

function showToast(msg,type){
    const c=document.getElementById('toast-c');
    const colors={success:'#16A34A',error:'#DC2626',info:'#2563EB',warning:'#D97706'};
    const icons={success:'✓',error:'✕',info:'ℹ',warning:'⚠'};
    const t=document.createElement('div');
    t.className='toast-msg';
    t.style.background=colors[type]||colors.info;
    t.innerHTML=`<span>${icons[type]||'ℹ'}</span><span>${msg}</span>`;
    c.appendChild(t);
    setTimeout(()=>{t.style.opacity='0';t.style.transform='translateX(16px)';t.style.transition='all .3s';setTimeout(()=>t.remove(),300);},3500);
}
window.showToast=showToast;

function confirmDelete(formId, name){
    if(typeof Swal!=='undefined'){
        Swal.fire({title:'Hapus data ini?',html:`<strong>${name}</strong> akan dihapus permanen.`,icon:'warning',showCancelButton:true,confirmButtonText:'Hapus',cancelButtonText:'Batal',confirmButtonColor:'#EF4444',cancelButtonColor:'#94A3B8',customClass:{popup:'rounded-2xl',confirmButton:'rounded-xl',cancelButton:'rounded-xl'}}).then(r=>{if(r.isConfirmed)document.getElementById(formId).submit();});
    } else {
        if(confirm('Hapus '+name+'?')) document.getElementById(formId).submit();
    }
}
window.confirmDelete=confirmDelete;
</script>

@stack('scripts')
</body>
</html>
