<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-6 text-gray-900">
        <h2 class="text-2xl font-bold mb-4"><?php echo e($message); ?></h2>

        <div class="mb-6">
            <p class="text-lg mb-4">Counter Example (Livewire 3):</p>
            <div class="flex items-center gap-4">
                <button
                    wire:click="decrement"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
                >
                    -
                </button>

                <span class="text-3xl font-bold"><?php echo e($counter); ?></span>

                <button
                    wire:click="increment"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition"
                >
                    +
                </button>
            </div>
        </div>

        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded">
            <h3 class="font-semibold text-blue-900 mb-2">Status Aplikasi:</h3>
            <ul class="list-disc list-inside text-blue-800 space-y-1">
                <li>Laravel 12 ✓</li>
                <li>Livewire 3.7.3 ✓</li>
                <li>Vite 7 dengan Hot Module Replacement ✓</li>
                <li>Tailwind CSS 4 ✓</li>
            </ul>
        </div>

        <div class="mt-6 text-sm text-gray-600">
            <p>Aplikasi sudah siap untuk development dan production!</p>
            <p class="mt-2">Lihat file <code class="bg-gray-100 px-2 py-1 rounded">SETUP.txt</code> untuk panduan lengkap.</p>
        </div>
    </div>
</div>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\livewire\welcome.blade.php ENDPATH**/ ?>