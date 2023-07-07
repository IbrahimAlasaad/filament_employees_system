<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

 use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Employee;    
use App\Models\Country;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $usEmployees = Country::where('country_code', '=', 'US')->withCount('employees')->first();
        $ukEmployees = Country::where('country_code', '=', 'UK')->withCount('employees')->first();

        return [
            Card::make('Employesss Total:' , Employee::count())
                    ->description('all employees in company')
                    ->descriptionIcon('heroicon-s-trending-up')->color('success'),
                    
            // Card::make($usEmployees->name.'employees:' , $usEmployees->employees_count()),
            // Card::make($ukEmployees->name.'employees:', $ukEmployees->employees_count()),
         ];
    }
}
