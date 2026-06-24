<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — QuickBite</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-sans: ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji';
        }
    </style>
</head>
<body class="h-full bg-slate-950 font-sans antialiased">

<div class="flex min-h-full flex-col items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="mb-8 flex flex-col items-center gap-3">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-500 shadow-xl shadow-orange-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7 text-white">
                    <path fill-rule="evenodd" d="M14.615 1.595a.75.75 0 01.359.852L12.982 9.75h7.268a.75.75 0 01.548 1.262l-10.5 11.25a.75.75 0 01-1.272-.71l1.992-7.302H3.75a.75.75 0 01-.548-1.262l10.5-11.25a.75.75 0 01.913-.143z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="text-center">
                <h1 class="text-2xl font-bold tracking-tight text-white">QuickBite</h1>
                <p class="mt-1 text-sm text-slate-400">Sign in to continue</p>
            </div>
        </div>

        {{-- Form card --}}
        <div class="overflow-hidden rounded-2xl bg-slate-900 shadow-2xl ring-1 ring-white/10">

            <div class="border-b border-white/10 px-8 py-5">
                <h2 class="text-base font-semibold text-white">Welcome back</h2>
                <p class="mt-0.5 text-sm text-slate-400">Enter your credentials to access your portal.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5 px-8 py-6">
                @csrf

                {{-- Error banner --}}
                @if ($errors->any())
                    <div class="flex items-start gap-3 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mt-0.5 h-4 w-4 shrink-0">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300">Email address</label>
                    <input id="email"
                           name="email"
                           type="email"
                           autocomplete="email"
                           autofocus
                           value="{{ old('email') }}"
                           class="mt-1.5 block w-full rounded-xl border border-white/10 bg-slate-800 px-3.5 py-2.5 text-sm text-white
                                  placeholder:text-slate-500 focus:border-orange-500 focus:outline-none focus:ring-2
                                  focus:ring-orange-500/20 transition"
                           placeholder="you@example.com">
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                    <input id="password"
                           name="password"
                           type="password"
                           autocomplete="current-password"
                           class="mt-1.5 block w-full rounded-xl border border-white/10 bg-slate-800 px-3.5 py-2.5 text-sm text-white
                                  placeholder:text-slate-500 focus:border-orange-500 focus:outline-none focus:ring-2
                                  focus:ring-orange-500/20 transition"
                           placeholder="••••••••">
                </div>

                {{-- Remember me --}}
                <div>
                    <label class="flex cursor-pointer items-center gap-2.5">
                        <input type="checkbox"
                               name="remember"
                               class="h-4 w-4 rounded border-slate-600 bg-slate-700 accent-orange-500"
                               {{ old('remember') ? 'checked' : '' }}>
                        <span class="text-sm text-slate-400">Remember me</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full rounded-xl bg-orange-500 px-4 py-3 text-sm font-semibold text-white
                               shadow-lg shadow-orange-500/20 transition-colors hover:bg-orange-600
                               focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                    Sign in
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-slate-600">
            QuickBite &middot; Secure Portal
        </p>
    </div>
</div>

</body>
</html>
