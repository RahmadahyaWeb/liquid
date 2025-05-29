import './bootstrap';
import { Modal } from 'flowbite';

const modals = {};

document.addEventListener('livewire:navigated', () => {
    initFlowbite();
});

document.addEventListener('livewire:init', () => {
    Livewire.on('toggle-modal', (event) => {
        const id = event[0].id;
        const action = event[0].action || 'toggle';
        if (!id) return console.error('Modal ID tidak diberikan');

        const selector = id.startsWith('#') ? id : `#${id}`;
        const el = document.querySelector(selector);

        if (!el) return console.error(`Elemen modal dengan selector ${selector} tidak ditemukan`);

        // Cek: jika instance belum ada, atau element DOM-nya berbeda (karena navigasi), buat ulang
        if (!modals[selector] || modals[selector]._targetEl !== el) {
            modals[selector] = new Modal(el, {
                backdrop: 'static',
                backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                closable: true,
                onHide: () => console.log(`${selector} modal is hidden`),
                onShow: () => console.log(`${selector} modal is shown`),
                onToggle: () => console.log(`${selector} modal has been toggled`),
            });
        }

        const modal = modals[selector];

        if (action === 'show') modal.show();
        else if (action === 'hide') modal.hide();
        else modal.toggle();
    });
});
