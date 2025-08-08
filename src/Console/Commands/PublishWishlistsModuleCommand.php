<?php

namespace admin\wishlists\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishWishlistsModuleCommand extends Command
{
    protected $signature = 'wishlists:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish Wishlist module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing Wishlists module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/Wishlists');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'wishlists',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('Wishlists module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__)); // Go up to packages/admin/wishlists/src
        
        $filesWithNamespaces = [
            // Controllers
            $basePath . '/Controllers/WishlistManagerController.php' => base_path('Modules/Wishlists/app/Http/Controllers/Admin/WishlistManagerController.php'),
            
            // Models
            $basePath . '/Models/Wishlist.php' => base_path('Modules/Wishlists/app/Models/Wishlist.php'),
            
            // Routes
            $basePath . '/routes/web.php' => base_path('Modules/Wishlists/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }

    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\wishlists\\Controllers;' => 'namespace Modules\\Wishlists\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\wishlists\\Models;' => 'namespace Modules\\Wishlists\\app\\Models;',
            'namespace admin\\wishlists\\Requests;' => 'namespace Modules\\Wishlists\\app\\Http\\Requests;',
            
            // Use statements transformations
            'use admin\\wishlists\\Controllers\\' => 'use Modules\\Wishlists\\app\\Http\\Controllers\\Admin\\',
            'use admin\\wishlists\\Models\\' => 'use Modules\\Wishlists\\app\\Models\\',
            'use admin\\wishlists\\Requests\\' => 'use Modules\\Wishlists\\app\\Http\\Requests\\',
            
            // Class references in routes
            'admin\\wishlists\\Controllers\\WishlistManagerController' => 'Modules\\Wishlists\\app\\Http\\Controllers\\Admin\\WishlistManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\Wishlists\\'])) {
            $composer['autoload']['psr-4']['Modules\\Wishlists\\'] = 'Modules/Wishlists/app/';
            
            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}
