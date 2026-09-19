@extends('layouts.app', ['title' => 'Dashboard - IQAB'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8 fade-up">
        <div class="flex items-center gap-3">
            <span class="hidden sm:block h-10 w-1 rounded-full bg-gradient-to-b from-primary-400 to-primary-700"></span>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gold-gradient">Dashboard</h1>
                <p class="mt-1.5 text-sm text-dark-400">Welcome back, {{ auth()->user()->name }} — here's what's happening with your numbers.</p>
            </div>
        </div>
        <div class="inline-flex items-center gap-2 self-start sm:self-auto rounded-full bg-emerald-500/10 border border-emerald-500/20 px-4 py-1.5 text-xs font-semibold text-emerald-400">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span id="live-ts">Live &middot; synced just now</span>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <x-stat-card title="Active Numbers" value="{{ $activeNumbers }}" animate delay="0" valueId="stat-active" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>' />
        <x-stat-card title="Total Numbers" value="{{ $totalNumbers }}" animate delay="80" valueId="stat-total" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>' subtitle="Across all countries" />
        <x-stat-card title="Messages" value="{{ $totalMessages }}" animate delay="160" valueId="stat-messages" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>' />
        <x-stat-card title="Total Purchases" value="{{ $recentPurchases->count() }}" animate delay="240" valueId="stat-purchases" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' />
    </div>

    {{-- Quick actions + Recent messages --}}
    <div class="grid lg:grid-cols-5 gap-6 mb-8">
        <div class="lg:col-span-2 rounded-xl bg-dark-900 border border-dark-800 p-6 reveal">
            <h2 class="text-lg font-semibold text-white flex items-center gap-2 mb-5">
                <svg class="h-5 w-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Quick Actions
            </h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('numbers.index') }}" class="group/tile relative overflow-hidden rounded-xl bg-dark-800 border border-dark-700 p-4 hover:-translate-y-1 hover:border-primary-500/40 transition-all duration-300">
                    <div class="absolute inset-x-0 top-0 h-px card-topline opacity-0 group-hover/tile:opacity-100 transition-opacity"></div>
                    <div class="h-10 w-10 rounded-lg bg-primary-500/10 ring-1 ring-primary-500/20 flex items-center justify-center group-hover/tile:scale-110 group-hover/tile:-rotate-3 transition-transform duration-300">
                        <svg class="h-5 w-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-dark-100">Buy Number</p>
                    <p class="mt-0.5 text-xs text-dark-500">Browse the catalog</p>
                    <svg class="absolute top-4 end-4 h-4 w-4 text-dark-600 -translate-x-1 group-hover/tile:translate-x-0 group-hover/tile:text-primary-400 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

                <a href="{{ route('my-numbers.index') }}" class="group/tile relative overflow-hidden rounded-xl bg-dark-800 border border-dark-700 p-4 hover:-translate-y-1 hover:border-primary-500/40 transition-all duration-300">
                    <div class="absolute inset-x-0 top-0 h-px card-topline opacity-0 group-hover/tile:opacity-100 transition-opacity"></div>
                    <div class="h-10 w-10 rounded-lg bg-primary-500/10 ring-1 ring-primary-500/20 flex items-center justify-center group-hover/tile:scale-110 group-hover/tile:-rotate-3 transition-transform duration-300">
                        <svg class="h-5 w-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-dark-100">My Numbers</p>
                    <p class="mt-0.5 text-xs text-dark-500">Manage what you own</p>
                    <svg class="absolute top-4 end-4 h-4 w-4 text-dark-600 -translate-x-1 group-hover/tile:translate-x-0 group-hover/tile:text-primary-400 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

                <a href="{{ route('telegram.index') }}" class="group/tile relative overflow-hidden rounded-xl bg-dark-800 border border-dark-700 p-4 hover:-translate-y-1 hover:border-primary-500/40 transition-all duration-300">
                    <div class="absolute inset-x-0 top-0 h-px card-topline opacity-0 group-hover/tile:opacity-100 transition-opacity"></div>
                    <div class="h-10 w-10 rounded-lg bg-primary-500/10 ring-1 ring-primary-500/20 flex items-center justify-center group-hover/tile:scale-110 group-hover/tile:-rotate-3 transition-transform duration-300">
                        <svg class="h-5 w-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-dark-100">Telegram Tools</p>
                    <p class="mt-0.5 text-xs text-dark-500">Inbox &amp; history</p>
                    <svg class="absolute top-4 end-4 h-4 w-4 text-dark-600 -translate-x-1 group-hover/tile:translate-x-0 group-hover/tile:text-primary-400 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

                <a href="{{ route('marketplace') }}" class="group/tile relative overflow-hidden rounded-xl bg-dark-800 border border-dark-700 p-4 hover:-translate-y-1 hover:border-primary-500/40 transition-all duration-300">
                    <div class="absolute inset-x-0 top-0 h-px card-topline opacity-0 group-hover/tile:opacity-100 transition-opacity"></div>
                    <div class="h-10 w-10 rounded-lg bg-primary-500/10 ring-1 ring-primary-500/20 flex items-center justify-center group-hover/tile:scale-110 group-hover/tile:-rotate-3 transition-transform duration-300">
                        <svg class="h-5 w-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-dark-100">Marketplace</p>
                    <p class="mt-0.5 text-xs text-dark-500">Explore listings</p>
                    <svg class="absolute top-4 end-4 h-4 w-4 text-dark-600 -translate-x-1 group-hover/tile:translate-x-0 group-hover/tile:text-primary-400 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Activity mini-chart (real data) --}}
            @php $maxBar = max(1, ...$dailyMessages); @endphp
            <div class="mt-6 pt-5 border-t border-dark-800">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-dark-400 uppercase tracking-wider">Messages this week</p>
                    <span id="week-delta" class="text-xs {{ $weekDelta >= 0 ? 'text-emerald-400' : 'text-red-400' }} font-semibold">{{ $weekDelta >= 0 ? '+' : '' }}{{ $weekDelta }}%</span>
                </div>
                <div id="week-chart" class="flex items-end gap-2 h-20">
                    @foreach($dailyMessages as $i => $v)
                        @php $h = $v > 0 ? max(14, round(($v / $maxBar) * 86)) : 3; @endphp
                        <div class="flex-1 flex flex-col items-center gap-1.5">
                            <div class="w-full rounded-full bg-gradient-to-t from-primary-700/40 to-primary-400/80 bar-grow" style="height: {{ $h }}%; animation-delay: {{ 200 + $i * 90 }}ms;"></div>
                            <span class="text-[10px] text-dark-500">{{ ['S', 'M', 'T', 'W', 'T', 'F', 'S'][$i] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Recent messages --}}
        <div class="lg:col-span-3 rounded-xl bg-dark-900 border border-dark-800 overflow-hidden reveal" style="animation-delay: 100ms;">
            <div class="flex items-center justify-between px-6 py-5 border-b border-dark-800">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="h-5 w-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Recent Messages
                </h2>
                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-[11px] font-semibold text-emerald-400">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                    </span>
                    Live
                </span>
            </div>
            <div id="messages-feed" class="px-4 py-3 max-h-80 overflow-y-auto">
                @forelse($recentMessages as $msg)
                    <div class="group/msg flex items-center justify-between gap-3 rounded-lg px-2 py-2.5 hover:bg-dark-800/70 hover:translate-x-1 transition-all duration-200">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-9 w-9 shrink-0 rounded-full bg-primary-500/10 ring-1 ring-primary-500/25 flex items-center justify-center text-primary-400 text-sm font-bold group-hover/msg:scale-110 transition-transform">
                                {{ strtoupper(substr($msg->sender ?? '?', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm text-dark-100 truncate font-medium">{{ $msg->sender ?? 'Unknown' }}</p>
                                <p class="text-xs text-dark-500 truncate">{{ $msg->message }} @if($msg->phoneNumber?->phone_number)<span class="text-primary-500/70">· {{ $msg->phoneNumber->phone_number }}</span>@endif</p>
                            </div>
                        </div>
                        <span class="text-[11px] text-dark-500 shrink-0 group-hover/msg:text-primary-400 transition-colors">{{ $msg->received_at?->diffForHumans() ?? '' }}</span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="h-14 w-14 rounded-2xl bg-primary-500/10 ring-1 ring-primary-500/20 flex items-center justify-center float-slow">
                            <svg class="h-7 w-7 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <p class="mt-4 text-sm text-dark-300 font-medium">No messages yet</p>
                        <p class="mt-1 text-xs text-dark-500">Messages you receive will show up here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent purchases --}}
    @if($recentPurchases->count())
    <div class="rounded-xl bg-dark-900 border border-dark-800 overflow-hidden reveal">
        <div class="flex items-center justify-between px-6 py-5 border-b border-dark-800">
            <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="h-5 w-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Recent Purchases
            </h2>
            <a href="{{ route('my-numbers.index') }}" class="group/link inline-flex items-center gap-1 text-xs font-semibold text-primary-400 hover:text-primary-300 transition-colors">
                View All
                <svg class="h-3.5 w-3.5 group-hover/link:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-dark-800 bg-dark-800/30">
                        <th class="text-start py-3 px-6 text-xs font-semibold text-dark-400 uppercase tracking-wider">Number</th>
                        <th class="text-start py-3 px-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Country</th>
                        <th class="text-start py-3 px-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Status</th>
                        <th class="text-start py-3 px-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Date</th>
                        <th class="text-end py-3 px-6 text-xs font-semibold text-dark-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody id="purchases-body">
                @foreach($recentPurchases as $purchase)
                    @php $st = $purchase->status->value; @endphp
                    <tr class="group/row border-b border-dark-800/50 hover:bg-dark-800/40 transition-colors">
                        <td class="py-3 px-6">
                            <a href="{{ route('my-numbers.show', $purchase) }}" class="font-mono text-primary-300 hover:text-primary-200 group-hover/row:translate-x-1 inline-block transition-transform">
                                {{ $purchase->phoneNumber?->phone_number ?? 'N/A' }}
                            </a>
                        </td>
                        <td class="py-3 px-4 text-dark-300">{{ $purchase->phoneNumber?->country ?? 'N/A' }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $st === 'active' ? 'bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20' : ($st === 'expired' ? 'bg-red-500/10 text-red-400 ring-1 ring-red-500/20' : 'bg-amber-500/10 text-amber-400 ring-1 ring-amber-500/20') }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $st === 'active' ? 'bg-emerald-400' : ($st === 'expired' ? 'bg-red-400' : 'bg-amber-400') }}"></span>
                                {{ $purchase->status->label() }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-dark-400">{{ $purchase->created_at->format('M d, Y') }}</td>
                        <td class="py-3 px-6 text-end">
                            <a href="{{ route('my-numbers.show', $purchase) }}" class="inline-flex items-center justify-center rounded-lg bg-primary-500/10 ring-1 ring-primary-500/25 px-3 py-1.5 text-xs font-semibold text-primary-300 hover:bg-primary-500/20 hover:text-primary-200 transition-colors">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<script>
    (function () {
        var url = "{{ route('dashboard.live') }}";
        var feed = document.getElementById('messages-feed');
        var chart = document.getElementById('week-chart');
        var pbody = document.getElementById('purchases-body');
        var ts = document.getElementById('live-ts');
        var delta = document.getElementById('week-delta');

        function esc(s) {
            var d = document.createElement('div');
            d.textContent = s == null ? '' : String(s);
            return d.innerHTML;
        }

        function countUp(el, to) {
            if (!el) return;
            var from = parseInt(el.textContent.replace(/[^0-9]/g, ''), 10) || 0;
            if (from === to) return;
            el.classList.remove('flash-pulse');
            void el.offsetWidth;
            el.classList.add('flash-pulse');
            var dur = 650, start = null;
            function step(t) {
                if (!start) start = t;
                var p = Math.min((t - start) / dur, 1);
                el.textContent = from + Math.round((to - from) * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        }

        var EMPTY_FEED = '<div class="flex flex-col items-center justify-center py-10 text-center">'
            + '<div class="h-14 w-14 rounded-2xl bg-primary-500/10 ring-1 ring-primary-500/20 flex items-center justify-center mx-auto float-slow">'
            + '<svg class="h-7 w-7 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>'
            + '</div><p class="mt-4 text-sm text-dark-300 font-medium">No messages yet</p>'
            + '<p class="mt-1 text-xs text-dark-500">Messages you receive will show up here.</p></div>';

        function renderFeed(msgs) {
            if (!feed) return;
            if (!msgs || !msgs.length) { feed.innerHTML = EMPTY_FEED; return; }
            feed.innerHTML = msgs.map(function (m) {
                return '<div class="group/msg flex items-center justify-between gap-3 rounded-lg px-2 py-2.5 hover:bg-dark-800/70 hover:translate-x-1 transition-all duration-200">'
                    + '<div class="flex items-center gap-3 min-w-0">'
                    + '<div class="h-9 w-9 shrink-0 rounded-full bg-primary-500/10 ring-1 ring-primary-500/25 flex items-center justify-center text-primary-400 text-sm font-bold">' + esc(m.initial) + '</div>'
                    + '<div class="min-w-0"><p class="text-sm text-dark-100 truncate font-medium">' + esc(m.sender) + '</p>'
                    + '<p class="text-xs text-dark-500 truncate">' + esc(m.message) + (m.number ? ' <span class="text-primary-500/70">&middot; ' + esc(m.number) + '</span>' : '') + '</p></div></div>'
                    + '<span class="text-[11px] text-dark-500 shrink-0">' + esc(m.time) + '</span></div>';
            }).join('');
        }

        function renderPurchases(rows) {
            if (!pbody) return;
            if (!rows || !rows.length) return;
            pbody.innerHTML = rows.map(function (p) {
                var pill = p.status === 'active' ? 'bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20' : (p.status === 'expired' ? 'bg-red-500/10 text-red-400 ring-1 ring-red-500/20' : 'bg-amber-500/10 text-amber-400 ring-1 ring-amber-500/20');
                var dot = p.status === 'active' ? 'bg-emerald-400' : (p.status === 'expired' ? 'bg-red-400' : 'bg-amber-400');
                return '<tr class="group/row border-b border-dark-800/50 hover:bg-dark-800/40 transition-colors">'
                    + '<td class="py-3 px-6"><a href="' + esc(p.url) + '" class="font-mono text-primary-300 hover:text-primary-200 inline-block transition-transform">' + esc(p.number) + '</a></td>'
                    + '<td class="py-3 px-4 text-dark-300">' + esc(p.country) + '</td>'
                    + '<td class="py-3 px-4"><span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ' + pill + '"><span class="h-1.5 w-1.5 rounded-full ' + dot + '"></span>' + esc(p.status_label) + '</span></td>'
                    + '<td class="py-3 px-4 text-dark-400">' + esc(p.date) + '</td>'
                    + '<td class="py-3 px-6 text-end"><a href="' + esc(p.url) + '" class="inline-flex items-center justify-center rounded-lg bg-primary-500/10 ring-1 ring-primary-500/25 px-3 py-1.5 text-xs font-semibold text-primary-300 hover:bg-primary-500/20 hover:text-primary-200 transition-colors">View</a></td></tr>';
            }).join('');
        }

        function renderChart(daily) {
            if (!chart) return;
            var max = Math.max.apply(null, daily);
            var days = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
            chart.innerHTML = daily.map(function (v, i) {
                var h = v > 0 ? Math.max(14, Math.round((v / Math.max(max, 1)) * 86)) : 3;
                return '<div class="flex-1 flex flex-col items-center gap-1.5">'
                    + '<div class="w-full rounded-full bg-gradient-to-t from-primary-700/40 to-primary-400/80 bar-grow" style="height:' + h + '%; animation-delay:' + (60 * i) + 'ms;"></div>'
                    + '<span class="text-[10px] text-dark-500">' + days[i] + '</span></div>';
            }).join('');
        }

        function poll() {
            fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.ok ? r.json() : Promise.reject(); })
                .then(function (d) {
                    countUp(document.getElementById('stat-active'), d.activeNumbers);
                    countUp(document.getElementById('stat-total'), d.totalNumbers);
                    countUp(document.getElementById('stat-messages'), d.totalMessages);
                    countUp(document.getElementById('stat-purchases'), d.totalPurchases);
                    renderFeed(d.messages);
                    renderPurchases(d.purchases);
                    renderChart(d.daily);
                    if (delta) {
                        delta.textContent = (d.delta >= 0 ? '+' : '') + d.delta + '%';
                        delta.className = 'text-xs font-semibold ' + (d.delta >= 0 ? 'text-emerald-400' : 'text-red-400');
                    }
                    if (ts) ts.textContent = 'Live &middot; synced ' + d.ts;
                })
                .catch(function () { if (ts) ts.textContent = 'Live &middot; reconnecting&hellip;'; });
        }

        setInterval(poll, 5000);
        poll();
    })();
</script>
@endsection