<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-neutral-200 transition-transform duration-200 ease-in-out flex flex-col justify-between shrink-0 lg:static"
>
    <div>
        {{-- App Logo & Brand --}}
        <div class="h-16 flex items-center px-6 border-b border-neutral-200 gap-3">
            <div class="w-9 h-9 rounded-lg bg-[#2D6A4F] text-white flex items-center justify-center font-bold text-lg shadow-sm">
                SW
            </div>
            <div>
                <span class="text-lg font-bold text-neutral-800 tracking-tight">StockWise</span>
                <span class="block text-[11px] text-neutral-500 font-medium -mt-1">Inventory UMKM</span>
            </div>
        </div>

        {{-- Nav Links --}}
        <nav class="p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i data-lucide="package" class="w-5 h-5"></i>
                <span>Produk</span>
            </a>

            <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i data-lucide="tag" class="w-5 h-5"></i>
                <span>Kategori</span>
            </a>

            <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <i data-lucide="truck" class="w-5 h-5"></i>
                <span>Supplier</span>
            </a>

            <a href="{{ route('stock-movements.index') }}" class="nav-item {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}">
                <i data-lucide="arrow-left-right" class="w-5 h-5"></i>
                <span>Transaksi Stok</span>
            </a>

            {{-- Laporan Dropdown / Group --}}
            <div x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }" class="pt-2">
                <button
                    @click="open = !open"
                    type="button"
                    class="nav-item w-full justify-between {{ request()->routeIs('reports.*') ? 'text-[#2D6A4F] font-semibold' : '' }}"
                >
                    <div class="flex items-center gap-3">
                        <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                        <span>Laporan</span>
                    </div>
                    <i data-lucide="chevron-down" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform"></i>
                </button>
                <div x-show="open" class="pl-8 pr-2 py-1 space-y-1">
                    <a href="{{ route('reports.inventory') }}" class="block px-3 py-1.5 text-xs rounded font-medium {{ request()->routeIs('reports.inventory') ? 'text-[#2D6A4F] bg-green-50' : 'text-neutral-600 hover:text-neutral-900' }}">
                        Inventaris Saat Ini
                    </a>
                    <a href="{{ route('reports.movements') }}" class="block px-3 py-1.5 text-xs rounded font-medium {{ request()->routeIs('reports.movements') ? 'text-[#2D6A4F] bg-green-50' : 'text-neutral-600 hover:text-neutral-900' }}">
                        Pergerakan Stok
                    </a>
                    <a href="{{ route('reports.low-stock') }}" class="block px-3 py-1.5 text-xs rounded font-medium {{ request()->routeIs('reports.low-stock') ? 'text-[#2D6A4F] bg-green-50' : 'text-neutral-600 hover:text-neutral-900' }}">
                        Stok Menipis & Habis
                    </a>
                </div>
            </div>

            @if (auth()->check() && auth()->user()->isAdmin())
                <div class="pt-4 border-t border-neutral-200 mt-4">
                    <p class="px-3 text-[11px] font-semibold text-neutral-400 uppercase tracking-wider mb-1">Administrasi</p>
                    <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                </div>
            @endif
        </nav>
    </div>

    {{-- User Profile & Logout Section --}}
    @if(auth()->check())
        <div class="p-4 border-t border-neutral-200 bg-neutral-50/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-[#2D6A4F]/20 text-[#2D6A4F] font-bold text-xs flex items-center justify-center shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="truncate">
                        <p class="text-sm font-medium text-neutral-800 truncate">{{ auth()->user()->name }}</p>
                        <span class="inline-block px-1.5 py-0.5 text-[10px] uppercase font-semibold rounded {{ auth()->user()->isAdmin() ? 'bg-blue-100 text-blue-700' : 'bg-neutral-200 text-neutral-600' }}">
                            {{ auth()->user()->role }}
                        </span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar" class="p-1.5 text-neutral-400 hover:text-red-600 rounded hover:bg-neutral-100 transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    @endif
</aside>
