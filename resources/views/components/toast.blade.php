{{-- resources/views/components/toast.blade.php
     Komponen toast notifikasi standalone (opsional, sudah include di layout)
     Cara pakai: <x-toast /> di bagian akhir body --}}

<div id="toast-container"
     style="position:fixed;top:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;pointer-events:none;">
</div>

@once
@push('scripts')
<script>
(function(){
    function showToast(message, type){
        type = type || 'success';
        var c = document.getElementById('toast-container');
        var icons = {success:'✓', error:'✕', info:'ℹ', warning:'⚠'};
        var colors = {success:'#16A34A', error:'#DC2626', info:'#2563EB', warning:'#D97706'};
        var t = document.createElement('div');
        t.style.cssText = 'padding:.875rem 1.25rem;border-radius:.75rem;color:#fff;font-size:.875rem;font-weight:500;box-shadow:0 10px 25px rgba(0,0,0,.25);display:flex;align-items:center;gap:.5rem;pointer-events:all;background:'+colors[type]+';opacity:0;transform:translateX(20px);transition:all .35s ease;';
        t.innerHTML = '<span style="font-size:1rem">'+icons[type]+'</span><span>'+message+'</span>';
        c.appendChild(t);
        requestAnimationFrame(function(){ t.style.opacity='1'; t.style.transform='translateX(0)'; });
        setTimeout(function(){
            t.style.opacity='0'; t.style.transform='translateX(20px)';
            setTimeout(function(){ t.remove(); }, 350);
        }, 3500);
    }
    window.showToast = showToast;

    // Auto-trigger jika ada session flash dari blade variable
    document.addEventListener('DOMContentLoaded', function(){
        @if(session('success'))
            showToast('{{ addslashes(session('success')) }}', 'success');
        @endif
        @if(session('error'))
            showToast('{{ addslashes(session('error')) }}', 'error');
        @endif
        @if(session('info'))
            showToast('{{ addslashes(session('info')) }}', 'info');
        @endif
        @if(session('warning'))
            showToast('{{ addslashes(session('warning')) }}', 'warning');
        @endif
    });
})();
</script>
@endpush
@endonce
