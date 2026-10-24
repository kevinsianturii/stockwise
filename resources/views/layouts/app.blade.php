<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}StockWise</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Lucide Icons CDN --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
    @stack('styles')
</head>
<body class="h-full bg-neutral-50 text-neutral-800 antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-full">

        {{-- Mobile overlay --}}
        <div
            x-show="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black/40 lg:hidden"
            style="display: none;"
        ></div>

        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main content wrapper --}}
        <div class="flex flex-1 flex-col min-w-0 overflow-hidden">
            {{-- Topbar --}}
            @include('components.topbar')

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8">
                {{-- Flash messages --}}
                @include('components.flash-message')

                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
