@extends('layouts.app', ['title' => 'Service Result - IQAB'])

@php
    $sensitive = ['token', 'secret', 'password', 'api_key', 'apikey', 'private'];
    $maskValue = function ($key, $value) use ($sensitive) {
        foreach ($sensitive as $s) {
            if (stripos((string) $key, $s) !== false && is_string($value) && $value !== '') {
                return '••••' . substr($value, -4);
            }
        }
        return $value;
    };
    $prettyKey = fn ($key) => ucwords(str_replace(['_', '-'], ' ', (string) $key));
    $renderRows = function ($data, $depth = 0) use (&$renderRows, $maskValue, $prettyKey) {
        $html = '';
        foreach ((array) $data as $key => $value) {
            $label = $prettyKey($key);
            if (is_array($value)) {
                $html .= '<div class="rounded-lg bg-dark-800/60 border border-dark-800 px-4 py-3' . ($depth > 0 ? ' mt-2' : '') . '">'
                    . '<p class="text-xs font-semibold text-primary-400 uppercase tracking-wide mb-2">' . e($label) . '</p>'
                    . $renderRows($value, $depth + 1) . '</div>';
            } else {
                $display = $maskValue($key, $value);
                if (is_bool($display)) $display = $display ? 'Yes' : 'No';
                if ($display === null || $display === '') $display = '—';
                $html .= '<div class="flex items-start justify-between gap-4 py-2 border-b border-dark-800/60 last:border-0">'
                    . '<span class="text-xs text-dark-500 shrink-0 pt-0.5">' . e($label) . '</span>'
                    . '<span class="text-sm text-dark-100 text-end break-all font-mono">' . e((string) $display) . '</span></div>';
            }
        }
        return $html;
    };
@endphp

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('telegram.index') }}" class="inline-flex items-center gap-1 text-sm text-dark-400 hover:text-white mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Services
    </a>

    <div class="rounded-2xl bg-dark-900 border border-dark-800 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5 pb-5 border-b border-dark-800">
            <div>
                <h1 class="text-lg font-semibold text-white">{{ $request->telegramService?->name }}</h1>
                <p class="text-sm text-dark-400 mt-1">Target: <span class="font-mono text-dark-200">{{ $request->target_identifier }}</span></p>
            </div>
            <x-status-pill :status="$request->status" :label="$request->status->label()" class="px-3 py-1 text-sm" />
        </div>

        <div class="grid grid-cols-3 gap-3 mb-5">
            <div class="rounded-xl bg-dark-800/70 p-3 text-center">
                <p class="text-[11px] uppercase tracking-wide text-dark-500">Price Paid</p>
                <p class="text-sm font-bold text-white mt-1">{{ number_format($request->price, 2) }} {{ currency() }}</p>
            </div>
            <div class="rounded-xl bg-dark-800/70 p-3 text-center">
                <p class="text-[11px] uppercase tracking-wide text-dark-500">Date</p>
                <p class="text-sm font-bold text-white mt-1">{{ $request->created_at->format('M d, Y') }}</p>
            </div>
            <div class="rounded-xl bg-dark-800/70 p-3 text-center">
                <p class="text-[11px] uppercase tracking-wide text-dark-500">Time</p>
                <p class="text-sm font-bold text-white mt-1">{{ $request->created_at->format('H:i') }}</p>
            </div>
        </div>

        @if($request->status->value === 'completed' && $request->result)
            <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/5 px-4 py-3">
                <h3 class="text-sm font-semibold text-emerald-300 mb-1">Report ready</h3>
                {!! $renderRows($request->result) !!}
            </div>
            <details class="mt-4 text-xs">
                <summary class="cursor-pointer text-dark-500 hover:text-dark-300 transition-colors">Technical details (raw data)</summary>
                <pre class="mt-2 text-[11px] text-dark-500 bg-dark-800 rounded-lg p-4 overflow-x-auto">{{ json_encode($request->result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </details>
        @elseif($request->status->value === 'processing' || $request->status->value === 'pending')
            <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-5 text-center">
                <div class="mx-auto mb-3 h-8 w-8 rounded-full border-2 border-dark-700 border-t-amber-400 animate-spin"></div>
                <p class="text-sm font-semibold text-amber-300">Still processing</p>
                <p class="text-xs text-dark-400 mt-1">Refresh this page in a few seconds to see the result.</p>
            </div>
        @endif

        @if($request->error_message)
            <div class="mt-4 rounded-xl bg-red-500/10 border border-red-500/20 p-4">
                <p class="text-sm font-semibold text-red-300 mb-1">Request failed</p>
                <p class="text-sm text-red-400/90">{{ $request->error_message }}</p>
            </div>
        @endif

        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('telegram.index') }}" class="flex-1 inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">New Request</a>
            <a href="{{ route('telegram.history') }}" class="flex-1 inline-flex items-center justify-center rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm font-medium text-dark-200 hover:text-white hover:border-primary-500/40 transition-colors">View History</a>
        </div>
    </div>
</div>
@endsection
