<?php

namespace App\Filament\Resources\Administrator;

use App\Filament\Resources\Administrator\ActivityLogResource\Pages;
use App\Support\ResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-clock';

    protected static string|null|\UnitEnum $navigationGroup = 'Administrator';

    protected static ?string $modelLabel = 'Activity Log';

    protected static ?string $pluralModelLabel = 'Activity Log';

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Waktu')->dateTime()->sortable(),
                TextColumn::make('causer.name')->label('User')->placeholder('Sistem')->searchable(),
                TextColumn::make('event')->label('Event')->badge()->sortable(),
                TextColumn::make('description')->label('Aktivitas')->searchable()->wrap(),
                TextColumn::make('subject_type')->label('Model')->formatStateUsing(fn (?string $state): string => class_basename($state ?? ''))->toggleable(),
                TextColumn::make('subject_id')->label('ID')->sortable()->toggleable(),
            ])
            ->filters([])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function canViewAny(): bool
    {
        return ResourceAccess::isAdmin();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
