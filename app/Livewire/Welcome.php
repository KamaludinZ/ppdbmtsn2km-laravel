<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\Attributes\Title;

#[Title('Welcome - SPMBM MTsN 2 Kota Malang')]
class Welcome extends Component
{
    public $message = 'Selamat datang di Aplikasi SPMBM MTS N 2 KM!';
    public $counter = 0;

    public function increment()
    {
        $this->counter++;
    }

    public function decrement()
    {
        $this->counter--;
    }

    public function render()
    {
        return view('livewire.welcome');
    }
}
