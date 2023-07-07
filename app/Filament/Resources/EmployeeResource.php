<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeeStatsOverview;
use App\Models\Employee;
use App\Models\Country;
use App\Models\State;
  
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
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
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('department.name')->sortable()->searchable(), //relationship
                TextColumn::make('state.name')->sortable()->searchable(), //relationship
                TextColumn::make('date_hired')->date(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('department')->relationship('department' ,'name'),
                SelectFilter::make('city')->relationship('city' ,'name'),
                SelectFilter::make('state')->relationship('state' ,'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getWidgets(): array
    {
        return [
            EmployeeStatsOverview::class,
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }    
}
