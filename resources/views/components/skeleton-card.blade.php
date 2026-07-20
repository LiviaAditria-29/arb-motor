{{-- resources/views/components/skeleton-card.blade.php
     Komponen skeleton loading untuk grid spare part / service
     Cara pakai: <x-skeleton-card :count="8" type="part" />
     type: 'part' | 'service' --}}

@props(['count' => 4, 'type' => 'part'])

@for($i = 0; $i < $count; $i++)
    @if($type === 'part')
    <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden">
        <div class="skeleton w-full aspect-[4/3]"></div>
        <div class="p-4 space-y-3">
            <div class="skeleton h-3 w-20 rounded-full"></div>
            <div class="skeleton h-4 w-full rounded"></div>
            <div class="skeleton h-3 w-3/4 rounded"></div>
            <div class="skeleton h-5 w-28 rounded mt-2"></div>
            <div class="skeleton h-9 w-full rounded-xl mt-3"></div>
        </div>
    </div>
    @else
    <div class="bg-white border border-slate-100 rounded-2xl p-6">
        <div class="skeleton w-14 h-14 rounded-2xl mb-4"></div>
        <div class="skeleton h-3 w-20 rounded-full mb-3"></div>
        <div class="skeleton h-5 w-3/4 rounded mb-2"></div>
        <div class="skeleton h-3 w-full rounded mb-1"></div>
        <div class="skeleton h-3 w-5/6 rounded mb-4"></div>
        <div class="flex gap-3">
            <div class="skeleton h-16 flex-1 rounded-xl"></div>
            <div class="skeleton h-16 flex-1 rounded-xl"></div>
        </div>
        <div class="skeleton h-10 w-full rounded-xl mt-4"></div>
    </div>
    @endif
@endfor

<style>
.skeleton{background:linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%);background-size:200% 100%;animation:skel 1.5s infinite;}
@keyframes skel{0%{background-position:200% 0}100%{background-position:-200% 0}}
</style>
