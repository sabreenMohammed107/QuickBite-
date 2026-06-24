<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — QuickBite Admin</title>

    {{-- Tailwind CSS v4 via CDN (no build step required) --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-sans: ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji';
        }
    </style>
</head>
<body class="h-full bg-slate-50 font-sans antialiased">

<div class="flex h-full overflow-hidden">

    {{-- Mobile overlay --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 z-20 hidden bg-black/50 backdrop-blur-sm lg:hidden"
         onclick="sidebarClose()"></div>

    {{-- ===================== SIDEBAR ===================== --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-30 flex w-64 shrink-0 flex-col bg-slate-900
                  -translate-x-full transition-transform duration-300 ease-out
                  lg:static lg:translate-x-0">

        {{-- Brand --}}
        <div class="flex items-center gap-3 border-b border-white/10 px-5 py-[18px]">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-orange-500 shadow-lg shadow-orange-500/40">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-white">
                    <path fill-rule="evenodd" d="M14.615 1.595a.75.75 0 01.359.852L12.982 9.75h7.268a.75.75 0 01.548 1.262l-10.5 11.25a.75.75 0 01-1.272-.71l1.992-7.302H3.75a.75.75 0 01-.548-1.262l10.5-11.25a.75.75 0 01.913-.143z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-bold text-white">QuickBite</p>
                <p class="text-[11px] text-slate-400">Admin Panel</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5">

            <div>
                <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Management</p>

                @php
                    $mgmtLinks = [
                        [
                            'href'  => route('admin.restaurants.index'),
                            'match' => 'admin.restaurants.*',
                            'label' => 'Restaurants',
                            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016 2.993 2.993 0 002.25-1.016 3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/></svg>',
                        ],
                        [
                            'href'  => route('admin.branches.index'),
                            'match' => 'admin.branches.*',
                            'label' => 'Branches',
                            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>',
                        ],
                    ];
                @endphp

                @foreach($mgmtLinks as $link)
                    <a href="{{ $link['href'] }}"
                       class="mt-0.5 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                              {{ request()->routeIs($link['match']) ? 'bg-orange-500 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        {!! $link['icon'] !!}
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            <div>
                <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Staff</p>

                @php
                    $staffLinks = [
                        [
                            'href'  => route('admin.members.index'),
                            'match' => 'admin.members.*',
                            'label' => 'Members',
                            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>',
                        ],
                    ];
                @endphp

                @foreach($staffLinks as $link)
                    <a href="{{ $link['href'] }}"
                       class="mt-0.5 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                              {{ request()->routeIs($link['match']) ? 'bg-orange-500 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        {!! $link['icon'] !!}
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            <div>
                <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Catalog</p>

                @php
                    $catalogLinks = [
                        [
                            'href'  => route('admin.products.index'),
                            'match' => 'admin.products.*',
                            'label' => 'Products',
                            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>',
                        ],
                        [
                            'href'  => route('admin.product-details.index'),
                            'match' => 'admin.product-details.*',
                            'label' => 'Branch Catalog',
                            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>',
                        ],
                    ];
                @endphp

                @foreach($catalogLinks as $link)
                    <a href="{{ $link['href'] }}"
                       class="mt-0.5 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                              {{ request()->routeIs($link['match']) ? 'bg-orange-500 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        {!! $link['icon'] !!}
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

        </nav>

        {{-- Sidebar footer --}}
        <div class="border-t border-white/10 px-4 py-3.5">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-orange-500 text-xs font-bold text-white">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-white">{{ auth()->user()?->name ?? 'Admin' }}</p>
                    <p class="truncate text-[11px] text-slate-400">{{ auth()->user()?->email ?? '' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            title="Log out"
                            class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-white/10 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ===================== MAIN ===================== --}}
    <div class="flex min-w-0 flex-1 flex-col overflow-hidden">

        {{-- Top Navbar --}}
        <header class="flex h-16 shrink-0 items-center gap-4 border-b border-slate-200 bg-white px-6 shadow-sm">
            <button onclick="sidebarOpen()"
                    class="rounded-lg p-1.5 text-slate-500 transition-colors hover:bg-slate-100 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>

            <h1 class="flex-1 truncate text-base font-semibold text-slate-800">@yield('title', 'Dashboard')</h1>

            <div class="flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5">
                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-orange-500 text-[10px] font-bold text-white">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                </div>
                <span class="hidden text-sm font-medium text-slate-700 sm:block">{{ auth()->user()?->name ?? 'Admin' }}</span>
            </div>
        </header>

        {{-- Scrollable content area --}}
        <main class="flex-1 overflow-y-auto p-6">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mt-0.5 h-4 w-4 shrink-0 text-green-500">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mt-0.5 h-4 w-4 shrink-0 text-red-500">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    function sidebarOpen() {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.remove('hidden');
    }
    function sidebarClose() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.add('hidden');
    }
</script>
@stack('scripts')
</body>
</html>
