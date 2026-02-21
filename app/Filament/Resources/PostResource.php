<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) =>
                                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                                            ),

                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(Post::class, 'slug', ignoreRecord: true),

                                        Forms\Components\RichEditor::make('content')
                                            ->columnSpanFull()
                                            ->fileAttachmentsDisk('public')
                                            ->fileAttachmentsDirectory('uploads'),

                                        Forms\Components\Textarea::make('excerpt')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('SEO')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->maxLength(60)
                                            ->placeholder('SEO title (60 chars max)'),
                                        Forms\Components\Textarea::make('meta_description')
                                            ->rows(2)
                                            ->maxLength(160)
                                            ->placeholder('SEO description (160 chars max)'),
                                        Forms\Components\TextInput::make('meta_keywords')
                                            ->maxLength(255)
                                            ->placeholder('keyword1, keyword2, keyword3'),
                                    ])
                                    ->collapsible(),
                            ])
                            ->columnSpan(2),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Publishing')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'publish' => 'Published',
                                                'pending' => 'Pending Review',
                                                'private' => 'Private',
                                                'scheduled' => 'Scheduled',
                                            ])
                                            ->required()
                                            ->default('draft'),

                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('Published Date'),

                                        Forms\Components\DateTimePicker::make('scheduled_at')
                                            ->label('Scheduled Date')
                                            ->visible(fn (Forms\Get $get) => $get('status') === 'scheduled'),

                                        Forms\Components\Select::make('user_id')
                                            ->label('Author')
                                            ->options(User::all()->pluck('name', 'id'))
                                            ->required()
                                            ->default(auth()->id()),

                                        Forms\Components\Select::make('comment_status')
                                            ->options(['open' => 'Open', 'closed' => 'Closed'])
                                            ->default('open'),

                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('Featured Post'),
                                    ]),

                                Forms\Components\Section::make('Featured Image')
                                    ->schema([
                                        Forms\Components\FileUpload::make('featured_image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('featured-images')
                                            ->imageResizeMode('cover')
                                            ->imageCropAspectRatio('16:9'),
                                    ]),

                                Forms\Components\Section::make('Categories & Tags')
                                    ->schema([
                                        Forms\Components\Select::make('categories')
                                            ->relationship('categories', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')->required(),
                                                Forms\Components\Hidden::make('type')->default('category'),
                                            ]),

                                        Forms\Components\TagsInput::make('tag_names')
                                            ->label('Tags')
                                            ->placeholder('Add tags...')
                                            ->afterStateHydrated(function ($component, $record) {
                                                if ($record) {
                                                    $component->state(
                                                        $record->tags()->pluck('name')->toArray()
                                                    );
                                                }
                                            }),
                                    ]),

                                Forms\Components\Section::make('Page Attributes')
                                    ->schema([
                                        Forms\Components\Select::make('parent_id')
                                            ->label('Parent Page')
                                            ->options(fn () => Post::where('type', 'page')
                                                ->pluck('title', 'id')
                                                ->toArray())
                                            ->searchable(),

                                        Forms\Components\TextInput::make('menu_order')
                                            ->numeric()
                                            ->default(0),

                                        Forms\Components\Select::make('template')
                                            ->options([
                                                'default' => 'Default Template',
                                                'full-width' => 'Full Width',
                                                'sidebar-left' => 'Left Sidebar',
                                                'sidebar-right' => 'Right Sidebar',
                                            ]),
                                    ])
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'page')
                                    ->collapsible(),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->disk('public')
                    ->label('Image')
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Post $record) => Str::limit($record->excerpt, 60)),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'publish',
                        'warning' => 'pending',
                        'secondary' => 'draft',
                        'danger' => 'trash',
                        'info' => 'scheduled',
                    ]),

                Tables\Columns\TextColumn::make('type')
                    ->badge(),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('views')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'publish' => 'Published',
                        'pending' => 'Pending',
                        'private' => 'Private',
                        'scheduled' => 'Scheduled',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'post' => 'Post',
                        'page' => 'Page',
                    ]),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Author')
                    ->options(User::all()->pluck('name', 'id')),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
