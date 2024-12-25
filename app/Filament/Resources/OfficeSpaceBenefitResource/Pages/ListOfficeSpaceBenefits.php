<?php

namespace App\Filament\Resources\OfficeSpaceBenefitResource\Pages;

use App\Filament\Resources\OfficeSpaceBenefitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOfficeSpaceBenefits extends ListRecords
{
    protected static string $resource = OfficeSpaceBenefitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
