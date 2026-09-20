<div class="group relative overflow-hidden rounded-2xl border border-dark-800 bg-dark-900 transition-all hover:-translate-y-0.5 hover:border-primary-500/50 hover:shadow-lg hover:shadow-primary-900/20">
    <div class="h-1.5 bg-gradient-to-r from-primary-600 to-primary-400"></div>
    <div class="p-5">
        <div class="flex items-center justify-between">
            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-400">
                <span class="h-2 w-2 rounded-full bg-emerald-400"></span> متاح
            </span>
            <span class="text-xs text-dark-500">{{ $number->country }}</span>
        </div>

        <p class="mt-3 font-mono text-2xl font-bold tracking-tight text-white">{{ $number->phone_number }}</p>

        <div class="mt-4 flex items-end justify-between">
            <div>
                <p class="text-[11px] uppercase tracking-wide text-dark-500">السعر</p>
                <p class="text-xl font-bold text-white">{{ number_format($number->price, 2) }} <span class="text-sm text-dark-400">{{ currency() }}</span></p>
            </div>
            <a href="{{ telegram_contact() }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-primary-700">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/></svg>
                شراء عبر تلغرام
            </a>
        </div>

        @if($number->expires_at)
            <p class="mt-3 text-xs text-dark-500">ينتهي: {{ $number->expires_at->format('M d, Y') }}</p>
        @endif
    </div>
</div>
