<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.settings';

    protected static ?string $navigationGroup = 'Configuration';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => Setting::get('site_name', config('app.name')),
            'site_tagline' => Setting::get('site_tagline', ''),
            'site_description' => Setting::get('site_description', ''),
            'admin_email' => Setting::get('admin_email', ''),
            'timezone' => Setting::get('timezone', config('app.timezone')),
            'date_format' => Setting::get('date_format', 'F j, Y'),
            'time_format' => Setting::get('time_format', 'g:i a'),
            'posts_per_page' => Setting::get('posts_per_page', 10),
            'allow_comments' => Setting::get('allow_comments', true),
            'comment_moderation' => Setting::get('comment_moderation', true),
            'permalink_structure' => Setting::get('permalink_structure', '/%postname%/'),
            'upload_max_filesize' => Setting::get('upload_max_filesize', '10'),
            'meta_robots' => Setting::get('meta_robots', 'index,follow'),
            'google_analytics_id' => Setting::get('google_analytics_id', ''),
            'maintenance_mode' => Setting::get('maintenance_mode', false),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->icon('heroicon-o-cog')
                            ->schema([
                                TextInput::make('site_name')->required()->maxLength(100),
                                TextInput::make('site_tagline')->maxLength(200),
                                Textarea::make('site_description')->rows(3),
                                TextInput::make('admin_email')->email()->required(),
                                Select::make('timezone')
                                    ->options(array_combine(timezone_identifiers_list(), timezone_identifiers_list()))
                                    ->searchable(),
                                Grid::make(2)->schema([
                                    TextInput::make('date_format')->maxLength(20),
                                    TextInput::make('time_format')->maxLength(20),
                                ]),
                            ]),

                        Tabs\Tab::make('Reading')
                            ->icon('heroicon-o-book-open')
                            ->schema([
                                TextInput::make('posts_per_page')->numeric()->minValue(1)->maxValue(100),
                                Toggle::make('maintenance_mode')->label('Enable Maintenance Mode'),
                            ]),

                        Tabs\Tab::make('Discussion')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                Toggle::make('allow_comments')->label('Allow comments by default'),
                                Toggle::make('comment_moderation')->label('Require comment moderation'),
                            ]),

                        Tabs\Tab::make('Permalinks')
                            ->icon('heroicon-o-link')
                            ->schema([
                                Select::make('permalink_structure')
                                    ->options([
                                        '/?p=%post_id%' => 'Plain (?p=123)',
                                        '/%year%/%monthnum%/%day%/%postname%/' => 'Day and name',
                                        '/%year%/%monthnum%/%postname%/' => 'Month and name',
                                        '/%postname%/' => 'Post name',
                                        '/%category%/%postname%/' => 'Category and name',
                                    ]),
                            ]),

                        Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Select::make('meta_robots')
                                    ->options([
                                        'index,follow' => 'Index, Follow',
                                        'noindex,follow' => 'No Index, Follow',
                                        'index,nofollow' => 'Index, No Follow',
                                        'noindex,nofollow' => 'No Index, No Follow',
                                    ]),
                                TextInput::make('google_analytics_id')
                                    ->label('Google Analytics ID')
                                    ->placeholder('G-XXXXXXXXXX'),
                            ]),

                        Tabs\Tab::make('Media')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                TextInput::make('upload_max_filesize')
                                    ->label('Max Upload Size (MB)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(100),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->action('save')
                ->color('primary'),
        ];
    }
}
