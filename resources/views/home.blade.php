<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home — QuickBite</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme { --font-sans: ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="flex min-h-full flex-col items-center justify-center bg-slate-50 font-sans">
    <div class="text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-500 shadow-xl shadow-orange-500/30">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 text-white">
                <path fill-rule="evenodd" d="M14.615 1.595a.75.75 0 01.359.852L12.982 9.75h7.268a.75.75 0 01.548 1.262l-10.5 11.25a.75.75 0 01-1.272-.71l1.992-7.302H3.75a.75.75 0 01-.548-1.262l10.5-11.25a.75.75 0 01.913-.143z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="mt-2 text-sm text-slate-500">The customer portal is coming soon.</p>
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit"
                    class="rounded-xl bg-slate-800 px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-slate-700">
                Sign out
            </button>
        </form>
    </div>
</body>
</html>
