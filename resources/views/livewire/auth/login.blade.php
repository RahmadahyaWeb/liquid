<div>
    <form wire:submit.prevent="login" class="space-y-4 md:space-y-6" action="#">
        <x-form.input id="email" label="Email" placeholder="johndoe@gmail.com" wire:model="email" />
        <x-form.input type="password" id="password" label="Password" placeholder="•••••••••" wire:model="password" />

        <x-ui.button type="submit" :block="true">Sign In</x-ui.button>
    </form>
</div>
