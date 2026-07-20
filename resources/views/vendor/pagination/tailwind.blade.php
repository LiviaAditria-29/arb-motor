{{-- resources/views/vendor/pagination/tailwind.blade.php --}}
@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
    <p style="font-size:.78rem;color:#64748B;">
        Menampilkan <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
        dari <strong>{{ $paginator->total() }}</strong> data
    </p>
    <div style="display:flex;gap:.3rem;flex-wrap:wrap;">
        @if ($paginator->onFirstPage())
            <span style="padding:.35rem .7rem;border-radius:.5rem;border:1.5px solid #E2E8F0;font-size:.78rem;color:#CBD5E1;cursor:not-allowed;">←</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="padding:.35rem .7rem;border-radius:.5rem;border:1.5px solid #E2E8F0;font-size:.78rem;color:#64748B;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='#F97316';this.style.color='#F97316';" onmouseout="this.style.borderColor='#E2E8F0';this.style.color='#64748B';">←</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="padding:.35rem .5rem;font-size:.78rem;color:#94A3B8;">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding:.35rem .7rem;border-radius:.5rem;background:#F97316;border:1.5px solid #F97316;font-size:.78rem;color:#fff;font-weight:700;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding:.35rem .7rem;border-radius:.5rem;border:1.5px solid #E2E8F0;font-size:.78rem;color:#64748B;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='#F97316';this.style.color='#F97316';" onmouseout="this.style.borderColor='#E2E8F0';this.style.color='#64748B';">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="padding:.35rem .7rem;border-radius:.5rem;border:1.5px solid #E2E8F0;font-size:.78rem;color:#64748B;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='#F97316';this.style.color='#F97316';" onmouseout="this.style.borderColor='#E2E8F0';this.style.color='#64748B';">→</a>
        @else
            <span style="padding:.35rem .7rem;border-radius:.5rem;border:1.5px solid #E2E8F0;font-size:.78rem;color:#CBD5E1;cursor:not-allowed;">→</span>
        @endif
    </div>
</nav>
@endif
