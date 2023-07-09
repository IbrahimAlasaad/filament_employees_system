<?php

namespace App\Filament\Resources\CountryResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use App\Models\Country;
use App\Models\State;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    protected static ?string $recordTitleAttribute = 'first_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name'),
                TextInput::make('last_name'),
                TextInput::make('address'),
                TextInput::make('zip_code'),
                DatePicker::make('birth_date'),
                DatePicker::make('date_hired'),
                // Select::make('country_id')->relationship('country','name'), 

                //dependant dropdown
                Select::make('country_id')->label('country')->options(Country::all()->pluck('name','id')->toArray())->reactive()
                ->afterStateUpdated(fn(callable $set)=>$set('state_id',null)),
                
                Select::make('state_id')->label('state')
                ->options(function(callable $get){
                    $country=Country::find($get('country_id'));
                    if(!$country)
                    {
                        return State::all()->pluck('name','id');
                    }
                    return $country->states->pluck('name','id');
                }),
                
                
                Select::make('state_id')->relationship('state','name'), 
                Select::make('city_id')->relationship('city','name'), 
                Select::make('department_id')->relationship('department','name'), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
