<?php

namespace App\Filament\Resources\Blog;

use Filament\Forms;
// use App\Filament\Resources\Blog\LinkResource\RelationManagers;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Blog\Link;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Resources\Blog\LinkResource\Pages;
// use Filament\Tables\View\TablesRenderHook;
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletingScope;

class LinkResource extends Resource
{

    use Translatable;

    protected static ?string $model = Link::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),

                Forms\Components\ColorPicker::make('color')
                    ->required()
                    ->hex()
                    ->hexColor(),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image')
                    ->image(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('title'),
                ColorEntry::make('color'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('url')
                    ->label('URL')
                    ->columnSpanFull()
                    ->url(fn(Link $record): string => '#' . urlencode($record->url)),
                ImageEntry::make('image'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('image')
                        ->height('100%')
                        ->width('100%'),

                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('title')
                            ->weight(FontWeight::Bold),

                        Tables\Columns\TextColumn::make('url')
                            ->formatStateUsing(fn(string $state): string => Str($state)->after('://')->ltrim('www.')->trim('/'))
                            ->color('gray')
                            ->limit(30),
                    ]),
                ])->space(3),
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\ColorColumn::make('color')
                            ->grow(false),

                        Tables\Columns\TextColumn::make('description')
                            ->color('gray')
                    ]),
                ])->collapsible(),
            ])
            ->filters([
                //
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated([
                18,
                36,
                72,
                'all',
            ])
            ->actions([
                Tables\Actions\Action::make('visit')
                    ->label('Visit Link')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn(Link $record): string => '#' . urlencode($record->url)),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'view' => Pages\ViewLink::route('/{record}'),  // Correct the parameter here
            'edit' => Pages\EditLink::route('/{record}/edit'),
        ];
    }

    public static function getTranslatableLocales(): array
    {
        return ['en', 'fr'];
    }
}
