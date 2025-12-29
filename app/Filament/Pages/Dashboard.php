<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('customer_type') 
                            ->label('Business customers only')
                            ->placeholder('-')
                            ->options([
                                'business' => 'Business',
                                'individual' => 'Individual',
                            ]),
                        
                        // 2. Start Date
                        DatePicker::make('startDate')
                            ->label('Start date'),

                        // 3. End Date
                        DatePicker::make('endDate')
                            ->label('End date'),
                    ])
                    ->columns(3),
            ]);
    }
}