<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class TestLivewire extends Component
{
    public $counter = 0;
    public $message = 'Click button to test Livewire';
    public $selectedItem = null;

    public function increment()
    {
        $this->counter++;
        $this->message = "Button clicked {$this->counter} times";
        Log::info('Increment called', ['counter' => $this->counter]);
    }

    public function selectItem($id)
    {
        $this->selectedItem = $id;
        $this->message = "Item {$id} selected";
        Log::info('SelectItem called', ['id' => $id]);
    }

    public function render()
    {
        return view('livewire.test-livewire')
            ->layout('components.layouts.public')
            ->layoutData(['title' => 'Test Livewire']);
    }
}
