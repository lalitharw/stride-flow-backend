<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\RichEditor;
use BackedEnum;

class BusinessSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.business-settings';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCog8Tooth;

    public ?array $data = [];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                RichEditor::make("terms_and_conditions")->required(),
                RichEditor::make("privacy_policy")->required()
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        dd($data);
    }
}