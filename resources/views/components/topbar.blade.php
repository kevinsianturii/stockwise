<header class="h-16 bg-white border-b border-neutral-200 px-4 sm:px-6 lg:px-8 flex items-center justify-between shrink-0">
    <div class="flex items-center gap-4">
        {{-- Mobile Hamburger --}}
        <button
            @click="sidebarOpen = !sidebarOpen"
            type="button"
            class="p-2 -ml-2 text-neutral-600 rounded-lg lg:hidden hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]"
        >
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        {{-- Page Heading / Breadcrumb --}}
        <div>
            <h2 class="text-base sm:text-lg font-semibold text-neutral-800">
                {{ $title ?? config('app.name', 'StockWise') }}
            </h2>
        </div>
    </div>

    {{-- Right Section: Quick Action & Date --}}
    <div class="flex items-center gap-3">
        <span class="hidden md:inline-flex items-center gap-1.5 text-xs font-medium text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-full">
            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
        </span>

        @if(auth()->check())
            <a href="{{ route('stock-movements.create') }}" class="btn-primary !py-1.5 !px-3 !text-xs hidden sm:inline-flex">
                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                Catat Stok
            </a>
        @endif
    </div>
</header>
