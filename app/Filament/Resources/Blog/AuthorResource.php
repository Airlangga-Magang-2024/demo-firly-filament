<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\AuthorResource\Pages;
use App\Filament\Resources\Blog\AuthorResource\RelationManagers;
use App\Models\Blog\Author;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $slug = 'blog/authors';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->required()
                    ->label('Email Address')
                    ->email()
                    ->unique(Author::class, 'email', ignoreRecord: true),

                Forms\Components\MarkdownEditor::make('bio')
                    ->columnSpan('full'),

                Forms\Components\TextInput::make('github_handle')
                    ->label('GitHub handle'),

                Forms\Components\TextInput::make('twitter_handle')
                    ->label('Twitter handle'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->searchable()
                            ->sortable()
                            ->weight('medium')
                            ->alignLeft(),

                        Tables\Columns\TextColumn::make('email')
                            ->label('Email Address')
                            ->searchable()
                            ->sortable()
                            ->color('gray')
                            ->alignLeft(),
                    ])->space(),

                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('github_handle')
                            ->icon('bi-github')
                            ->label('Github')
                            ->alignLeft(),

                        Tables\Columns\TextColumn::make('twitter_handle')
                            ->icon('si-x')
                            ->label('X')
                            ->alignLeft(),
                    ])->space(2),
                ])->from('md'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageAuthors::route('/'),
        ];
    }
}
