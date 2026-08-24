@extends('layouts.app', ['title' => 'Telegram Bot - IQAB'])

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Telegram Bot</h1>
        <p class="text-sm text-dark-500 mt-1">Configure and test the bot token defined in System Settings.</p>
    </div>

    @if(!$configured)
        <div class="rounded-xl bg-amber-500/10 border border-amber-500/20 p-4 mb-6 flex items-start gap-3">
            <svg class="h-5 w-5 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <div>
                <p class="text-sm text-amber-300 font-medium">Bot token not configured</p>
                <p class="text-sm text-amber-300/80 mt-1">Add a <code class="text-amber-200">telegram.bot_token</code> value under <a href="{{ route('admin.settings.index') }}" class="underline">System Settings</a> and save before using these actions.</p>
            </div>
        </div>
    @endif

    <div class="grid lg:grid-cols-2 gap-6 mb-6">
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Bot Identity (getMe)</h2>
            @if(($me['ok'] ?? false))
                @php $bot = $me['data']; @endphp
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-dark-500">Name</dt><dd class="text-dark-100">{{ $bot['first_name'] ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-dark-500">Username</dt><dd class="text-dark-100">@{{ $bot['username'] ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-dark-500">Bot ID</dt><dd class="text-dark-100 font-mono">{{ $bot['id'] ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-dark-500">Can connect</dt><dd class="text-emerald-400">Yes</dd></div>
                </dl>
            @else
                <p class="text-sm text-red-400">Unable to reach Telegram: {{ $me['error'] ?? 'unknown error' }}</p>
            @endif
        </div>

        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Webhook Status</h2>
            @if(($webhook['ok'] ?? false))
                @php $info = $webhook['data']; @endphp
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-dark-500">URL</dt><dd class="text-dark-100 break-all text-right">{{ $info['url'] ?? 'Not set' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-dark-500">Pending updates</dt><dd class="text-dark-100">{{ $info['pending_update_count'] ?? 0 }}</dd></div>
                    <div class="flex justify-between"><dt class="text-dark-500">Last error</dt><dd class="text-dark-100 break-all text-right">{{ $info['last_error_message'] ?? '-' }}</dd></div>
                </dl>
            @else
                <p class="text-sm text-red-400">Unable to fetch webhook info: {{ $webhook['error'] ?? 'unknown error' }}</p>
            @endif
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Set Webhook</h2>
            <form method="POST" action="{{ route('admin.telegram.bot.webhook') }}">
                @csrf
                <input type="url" name="url" required placeholder="https://your-domain.com/api/webhooks/telegram"
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none mb-3">
                <p class="text-xs text-dark-500 mb-3">A <code class="text-dark-400">secret_token</code> will be attached automatically from <code class="text-dark-400">telegram.webhook_secret</code>.</p>
                <button type="submit" class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Set Webhook</button>
            </form>
            <form method="POST" action="{{ route('admin.telegram.bot.webhook.delete') }}" class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full rounded-lg bg-dark-800 border border-dark-700 px-4 py-2.5 text-sm font-medium text-dark-300 hover:text-white transition-colors">Delete Webhook</button>
            </form>
        </div>

        <div class="rounded-xl bg-dark-900 border border-dark-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Send Test Message</h2>
            <form method="POST" action="{{ route('admin.telegram.bot.test') }}">
                @csrf
                <input type="text" name="chat_id" required placeholder="Chat ID or @username"
                       class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none mb-3">
                <textarea name="text" required rows="3" placeholder="Hello from IQAB admin panel"
                          class="w-full rounded-lg bg-dark-800 border border-dark-700 px-3 py-2 text-sm text-white placeholder-dark-500 focus:border-primary-500 outline-none mb-3"></textarea>
                <button type="submit" class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Send Message</button>
            </form>
        </div>
    </div>
</div>
@endsection
