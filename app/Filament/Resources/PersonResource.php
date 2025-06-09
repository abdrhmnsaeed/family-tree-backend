<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonResource\Pages;
use App\Filament\Resources\PersonResource\RelationManagers;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->nullable(),
                Forms\Components\FileUpload::make('picture')
                    ->image()
                    ->directory('pictures')
                    ->nullable(),
                Forms\Components\Textarea::make('biography')
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('phone_number')
                    ->nullable()
                    ->maxLength(20),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\Radio::make('is_deceased')
                    ->label('Deceased')
                    ->boolean()
                    ->options([
                        true => 'Yes',
                        false => 'No',
                    ])
                    ->default(false),
                Forms\Components\DatePicker::make('date_of_death')
                    ->nullable()
                    ->after('date_of_birth'),
                Forms\Components\Select::make('father_id')
                    ->label('Father')
                    ->options(Person::pluck('full_name', 'id'))
                    ->searchable()
                    ->nullable(),
                Forms\Components\Select::make('mother_id')
                    ->label('Mother')
                    ->options(Person::pluck('full_name', 'id'))
                    ->searchable()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')->date(),
                Tables\Columns\ImageColumn::make('picture'),
                Tables\Columns\TextColumn::make('phone_number'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\BooleanColumn::make('is_deceased')->label('Deceased'),
                Tables\Columns\TextColumn::make('date_of_death')->date(),
                Tables\Columns\TextColumn::make('father.full_name')->label('Father'),
                Tables\Columns\TextColumn::make('mother.full_name')->label('Mother'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'edit' => Pages\EditPerson::route('/{record}/edit'),
        ];
    }
}
