<?php

namespace App\Livewire\Components;

use Livewire\Attributes\On;
use Livewire\Component;

class Alert extends Component
{
    public $type;
    public $message;
    public $title;

    #[On('showAlert')]
    public function showAlert($type, $title, $message)
    {
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
    }

    public function render()
    {
        return view('livewire.components.alert');
    }
}
