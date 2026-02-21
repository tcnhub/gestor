<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CmsInstall extends Command
{
    protected $signature = 'cms:install {--fresh : Fresh install, will wipe database}';

    protected $description = 'Install Laravel CMS with all migrations and seed data';

    public function handle(): int
    {
        $this->info('');
        $this->info('  ██╗      █████╗ ██████╗  █████╗ ██╗   ██╗███████╗██╗      ██████╗███╗   ███╗███████╗');
        $this->info('  ██║     ██╔══██╗██╔══██╗██╔══██╗██║   ██║██╔════╝██║     ██╔════╝████╗ ████║██╔════╝');
        $this->info('  ██║     ███████║██████╔╝███████║██║   ██║█████╗  ██║     ██║     ██╔████╔██║███████╗');
        $this->info('  ██║     ██╔══██║██╔══██╗██╔══██║╚██╗ ██╔╝██╔══╝  ██║     ██║     ██║╚██╔╝██║╚════██║');
        $this->info('  ███████╗██║  ██║██║  ██║██║  ██║ ╚████╔╝ ███████╗███████╗╚██████╗██║ ╚═╝ ██║███████║');
        $this->info('  ╚══════╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝  ╚═══╝  ╚══════╝╚══════╝ ╚═════╝╚═╝     ╚═╝╚══════╝');
        $this->info('');
        $this->info('  Laravel CMS - Installation Wizard');
        $this->info('');

        // Check environment
        if (!file_exists(base_path('.env'))) {
            $this->error('.env file not found! Copy .env.example to .env and configure your database.');
            return Command::FAILURE;
        }

        // Run migrations
        $this->info('Running database migrations...');
        if ($this->option('fresh')) {
            Artisan::call('migrate:fresh', ['--force' => true]);
        } else {
            Artisan::call('migrate', ['--force' => true]);
        }
        $this->info('✓ Migrations complete');

        // Seed database
        $this->info('Seeding database with initial data...');
        Artisan::call('db:seed', ['--force' => true]);
        $this->info('✓ Database seeded');

        // Create storage link
        $this->info('Creating storage symlink...');
        Artisan::call('storage:link');
        $this->info('✓ Storage link created');

        // Clear caches
        $this->info('Clearing caches...');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        $this->info('✓ Caches cleared');

        // Optimize
        Artisan::call('optimize');

        $this->info('');
        $this->info('✅ Laravel CMS installed successfully!');
        $this->info('');
        $this->table(
            ['Credential', 'Value'],
            [
                ['Admin URL', url('/admin')],
                ['Admin Email', 'admin@example.com'],
                ['Admin Password', 'password'],
                ['', ''],
                ['Editor Email', 'editor@example.com'],
                ['Editor Password', 'password'],
            ]
        );
        $this->info('');
        $this->warn('⚠️  Remember to change the default passwords after logging in!');
        $this->info('');

        return Command::SUCCESS;
    }
}
