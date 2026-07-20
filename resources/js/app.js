// resources/js/app.js — VERSI FINAL ARB Motor
import './bootstrap';
import Alpine from 'alpinejs';

// ── SweetAlert2 ──────────────────────────────────────────────
import Swal from 'sweetalert2';
window.Swal = Swal;

// ── Chart.js ─────────────────────────────────────────────────
import Chart from 'chart.js/auto';
window.Chart = Chart;

// ── Alpine.js ─────────────────────────────────────────────────
window.Alpine = Alpine;
Alpine.start();

// ── Global Utilities ─────────────────────────────────────────

/**
 * Format angka ke format Rupiah Indonesia
 * Contoh: formatRupiah(50000) → 'Rp 50.000'
 */
window.formatRupiah = (num) => {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
};

/**
 * Global toast notification
 * Cara pakai: showToast('Berhasil disimpan!', 'success')
 * Type: 'success' | 'error' | 'info' | 'warning'
 */
window.showToast = function(message, type = 'success') {
    let container = document.getElementById('toast-c') || document.getElementById('toast-container');

    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-c';
        container.style.cssText = 'position:fixed;top:1.25rem;right:1.25rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;';
        document.body.appendChild(container);
    }

    const colors  = { success: '#16A34A', error: '#DC2626', info: '#2563EB', warning: '#D97706' };
    const icons   = { success: '✓', error: '✕', info: 'ℹ', warning: '⚠' };

    const toast = document.createElement('div');
    toast.style.cssText = `
        padding:.75rem 1.1rem;border-radius:.75rem;color:#fff;
        font-size:.82rem;font-weight:600;
        box-shadow:0 8px 24px rgba(0,0,0,.2);
        display:flex;align-items:center;gap:.5rem;
        background:${colors[type] || colors.info};
        opacity:0;transform:translateX(16px);
        transition:all .3s ease;
        max-width:320px;
        font-family:'Plus Jakarta Sans',sans-serif;
    `;
    toast.innerHTML = `<span>${icons[type] || 'ℹ'}</span><span>${message}</span>`;
    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.style.opacity   = '1';
        toast.style.transform = 'translateX(0)';
    });

    setTimeout(() => {
        toast.style.opacity   = '0';
        toast.style.transform = 'translateX(16px)';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
};

/**
 * Global SweetAlert delete confirmation
 * Cara pakai: confirmDelete('form-id', 'Nama Item')
 */
window.confirmDelete = function(formId, itemName) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hapus data ini?',
            html: `<b>${itemName}</b> akan dihapus secara permanen dan tidak dapat dikembalikan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#94A3B8',
            customClass: {
                popup: 'swal-rounded',
                confirmButton: 'swal-btn-confirm',
                cancelButton: 'swal-btn-cancel',
            }
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    } else {
        if (confirm(`Hapus "${itemName}"? Tindakan ini tidak dapat dibatalkan.`)) {
            document.getElementById(formId).submit();
        }
    }
};

/**
 * Image preview sebelum upload
 * Cara pakai di blade: <input type="file" onchange="previewImage(this, 'preview-img-id')">
 */
window.previewImage = function(input, previewId) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        if (file.size > 2 * 1024 * 1024) {
            showToast('Ukuran file maksimal 2 MB', 'error');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById(previewId);
            if (img) img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};
