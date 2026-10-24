@if (session('success'))
    <div x-data="{ show: true }" x-show="show" class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 flex items-start justify-between gap-3 text-green-800 transition-all">
        <div class="flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 shrink-0"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
        <button @click="show = false" type="button" class="text-green-600 hover:text-green-800 p-1">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-show="show" class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 flex items-start justify-between gap-3 text-red-800 transition-all">
        <div class="flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
        <button @click="show = false" type="button" class="text-red-600 hover:text-red-800 p-1">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
@endif

@if (session('warning'))
    <div x-data="{ show: true }" x-show="show" class="mb-4 rounded-lg bg-amber-50 border border-amber-200 p-4 flex items-start justify-between gap-3 text-amber-800 transition-all">
        <div class="flex items-center gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 shrink-0"></i>
            <span class="text-sm font-medium">{{ session('warning') }}</span>
        </div>
        <button @click="show = false" type="button" class="text-amber-600 hover:text-amber-800 p-1">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
@endif

@if (session('info'))
    <div x-data="{ show: true }" x-show="show" class="mb-4 rounded-lg bg-blue-50 border border-blue-200 p-4 flex items-start justify-between gap-3 text-blue-800 transition-all">
        <div class="flex items-center gap-3">
            <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0"></i>
            <span class="text-sm font-medium">{{ session('info') }}</span>
        </div>
        <button @click="show = false" type="button" class="text-blue-600 hover:text-blue-800 p-1">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
@endif
