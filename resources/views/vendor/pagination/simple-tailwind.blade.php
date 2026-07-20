{{-- resources/views/vendor/pagination/simple-tailwind.blade.php --}}
@if ($paginator->hasPages())
<nav style="display:flex;justify-content:space-between;align-items:center;gap:.75rem;">
    <p style="font-size:.78rem;color:#64748B;">
        Halaman <strong>{{ $paginator->currentPage() }}</strong>
    </p>
    <div style="display:flex;gap:.4rem;">
        @if ($paginator->onFirstPage())
            <span style="padding:.35rem .85rem;border-radius:.5rem;border:1.5px solid #E2E8F0;font-size:.78rem;color:#CBD5E1;cursor:not-allowed;">← Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="padding:.35rem .85rem;border-radius:.5rem;border:1.5px solid #E2E8F0;font-size:.78rem;color:#64748B;text-decoration:none;" onmouseover="this.style.borderColor='#F97316';this.style.color='#F97316';" onmouseout="this.style.borderColor='#E2E8F0';this.style.color='#64748B';">← Prev</a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="padding:.35rem .85rem;border-radius:.5rem;border:1.5px solid #E2E8F0;font-size:.78rem;color:#64748B;text-decoration:none;" onmouseover="this.style.borderColor='#F97316';this.style.color='#F97316';" onmouseout="this.style.borderColor='#E2E8F0';this.style.color='#64748B';">Next →</a>
        @else
            <span style="padding:.35rem .85rem;border-radius:.5rem;border:1.5px solid #E2E8F0;font-size:.78rem;color:#CBD5E1;cursor:not-allowed;">Next →</span>
        @endif
    </div>
</nav>
@endif
