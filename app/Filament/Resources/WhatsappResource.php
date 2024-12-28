<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Pages\EditWhatsApp;
use Pages\ListWhatsApp;
use Filament\Forms\Form;
use Pages\CreateWhatsapp;
use Filament\Tables\Table;
use App\Models\WhatsAppSetting;
use Filament\Resources\Resource;
use App\Filament\Resources\WhatsAppResource\Pages;

class WhatsAppResource extends Resource
{
    protected static ?string $model = WhatsAppSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationLabel = 'WhatsApp Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('device_name')
                            ->required(),
                        Forms\Components\TextInput::make('phone_number')
                            ->tel()
                            ->required(),
                        Forms\Components\View::make('filament.forms.components.qr-scanner'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device_name'),
                Tables\Columns\TextColumn::make('phone_number'),
                Tables\Columns\BooleanColumn::make('is_connected'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWhatsapps::route('/'),
            'create' => Pages\CreateWhatsapp::route('/create'),
            'edit' => Pages\EditWhatsApp::route('/{record}/edit'),
        ];
    }
}
