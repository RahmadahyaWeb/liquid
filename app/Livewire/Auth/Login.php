<?php

namespace App\Livewire\Auth;

use App\Livewire\Menu\Dashboard;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    public $email;
    public $password;

    protected function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt($this->only('email', 'password'))) {
            $this->redirectIntended(Dashboard::class, true);
        } else {
            $this->addError('email', 'Wrong email or password.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
