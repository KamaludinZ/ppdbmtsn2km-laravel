<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow p-8">
            <h1 class="text-2xl font-bold mb-4">Livewire Test Page</h1>

            <!-- Test 1: Simple counter -->
            <div class="mb-8 border-b pb-6">
                <h2 class="text-lg font-semibold mb-3">Test 1: Simple Button Click</h2>
                <div class="mb-4">
                    <p class="text-gray-700">Message: <span class="font-medium text-emerald-600">{{ $message }}</span></p>
                    <p class="text-gray-700">Counter: <span class="font-medium text-blue-600">{{ $counter }}</span></p>
                </div>
                <button
                    wire:click="increment"
                    wire:loading.attr="disabled"
                    wire:target="increment"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="increment">Click Me!</span>
                    <span wire:loading wire:target="increment">Loading...</span>
                </button>

                <div class="mt-2 text-sm text-gray-500">
                    Expected: Counter increases and message changes when button clicked
                </div>
            </div>

            <!-- Test 2: Item selection (like jalur selection) -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-3">Test 2: Item Selection (Similar to Jalur)</h2>
                <div class="mb-4">
                    <p class="text-gray-700">Selected: <span class="font-medium text-purple-600">{{ $selectedItem ?? 'None' }}</span></p>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    @foreach([1, 2, 3] as $item)
                        <div
                            wire:click="selectItem({{ $item }})"
                            wire:loading.class="opacity-50"
                            wire:target="selectItem({{ $item }})"
                            class="border-2 rounded-lg p-4 cursor-pointer transition-all
                                   @if($selectedItem == $item)
                                       border-emerald-500 bg-emerald-50
                                   @else
                                       border-gray-200 hover:border-gray-300
                                   @endif">

                            @if($selectedItem == $item)
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-6 h-6 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif

                            <p class="text-center font-medium">Item {{ $item }}</p>
                            <p class="text-center text-sm text-gray-500">Click to select</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-2 text-sm text-gray-500">
                    Expected: Border and background change when item clicked, checkmark appears
                </div>
            </div>

            <!-- Debug Info -->
            <div class="bg-gray-100 rounded-lg p-4">
                <h3 class="font-semibold mb-2">Debug Info</h3>
                <pre class="text-xs">{{ json_encode([
                    'counter' => $counter,
                    'message' => $message,
                    'selectedItem' => $selectedItem,
                    'livewire_loaded' => true
                ], JSON_PRETTY_PRINT) }}</pre>
            </div>

            <!-- Instructions -->
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4">
                <h3 class="font-semibold text-blue-900 mb-2">Testing Instructions:</h3>
                <ol class="list-decimal list-inside space-y-1 text-sm text-blue-800">
                    <li>Open browser console (F12)</li>
                    <li>Check for JavaScript errors</li>
                    <li>Click "Click Me!" button - counter should increment</li>
                    <li>Click any item box - it should get selected (green border)</li>
                    <li>Check Network tab - should see /livewire/message requests</li>
                    <li>If nothing works, Livewire is not loaded properly</li>
                </ol>
            </div>

            <div class="mt-4 text-center">
                <a href="/daftar" class="text-emerald-600 hover:text-emerald-700 font-medium">
                    ← Back to Registration Form
                </a>
            </div>
        </div>
    </div>
</div>