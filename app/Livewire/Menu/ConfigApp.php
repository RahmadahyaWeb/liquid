<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Config;
use Livewire\Component;

class ConfigApp extends BaseComponent
{
    public $modalTitle = 'Form Config';

    protected array $permissionMap = [
        'save' => ['edit config'],
        'edit' => ['edit config'],
    ];

    public $editing =  [
        'id' => '',
        'config' => '',
        'value' => '',
    ];

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => Config::class,
            'with' => [],
            'map' => function ($data) {
                return [
                    'id' => $data->id,
                    'config' => $data->config,
                    'value' => $data->value,
                ];
            }
        ]);
    }

    public function save()
    {
        $this->validate([
            'editing.value' => 'required',
        ]);

        $this->executeSave(function () {
            $config = Config::findOrFail($this->editing['id']);

            $config->update([
                'value' => $this->editing['value']
            ]);
        });
    }

    public function render()
    {
        $rows = Config::paginate();

        $columns = [
            'config' => 'Nama Config',
            'value' => 'Value'
        ];

        $canDelete = fn($row) => false;

        return view('livewire.menu.config-app', compact(
            'rows',
            'columns',
            'canDelete',
        ));
    }
}
