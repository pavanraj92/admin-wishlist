<?php

namespace admin\Wishlists;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WishlistsModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package  
        $this->loadViewsFrom([
            base_path('Modules/Wishlists/resources/views'), // Published module views first
            resource_path('views/admin/wishlist'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'wishlists');
        
        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/Wishlists/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Wishlists/resources/views'), 'wishlists-module');
        }
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Also load migrations from published module if they exist
        if (is_dir(base_path('Modules/Wishlists/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/Wishlists/database/migrations'));
        }
        
        // Only publish automatically during package installation, not on every request
        // Use 'php artisan pages:publish' command for manual publishing
        // $this->publishWithNamespaceTransformation();
        
        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../database/migrations' => base_path('Modules/Wishlists/database/migrations'),
            __DIR__ . '/../resources/views' => base_path('Modules/Wishlists/resources/views/'),
        ], 'wishlist');
       
        $this->registerAdminRoutes();
    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $slug = DB::table('admins')->latest()->value('website_slug') ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                // Load routes from published module first, then fallback to package
                if (file_exists(base_path('Modules/Wishlists/routes/web.php'))) {
                    $this->loadRoutesFrom(base_path('Modules/Wishlists/routes/web.php'));
                } else {
                    $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
                }
            });
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\wishlists\Console\Commands\PublishWishlistsModuleCommand::class,
                \admin\wishlists\Console\Commands\CheckModuleStatusCommand::class,
                \admin\wishlists\Console\Commands\DebugWishlistsCommand::class,
                \admin\wishlists\Console\Commands\TestViewResolutionCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../src/Controllers/WishlistManagerController.php' => base_path('Modules/Wishlists/app/Http/Controllers/Admin/WishlistManagerController.php'),
            
            // Models
            __DIR__ . '/../src/Models/wishlist.php' => base_path('Modules/Wishlist/app/Models/wishlist.php'),
            
            // Routes
            __DIR__ . '/routes/web.php' => base_path('Modules/wishlists/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));
                
                // Read the source file
                $content = File::get($source);
                
                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);
                
                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    /**
     * Transform namespaces in PHP files
     */
    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\wishlists\\Controllers;' => 'namespace Modules\\wishlists\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\wishlists\\Models;' => 'namespace Modules\\wishlists\\app\\Models;',
            
            // Use statements transformations
            'use admin\\wishlists\\Controllers\\' => 'use Modules\\wishlists\\app\\Http\\Controllers\\Admin\\',
            'use admin\\wishlists\\Models\\' => 'use Modules\\wishlists\\app\\Models\\',
            
            // Class references in routes
            'admin\\wishlists\\Controllers\\WishlistManagerController' => 'Modules\\wishlists\\app\\Http\\Controllers\\Admin\\WishlistManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = $this->transformControllerNamespaces($content);
        } elseif (str_contains($sourceFile, 'Models')) {
            $content = $this->transformModelNamespaces($content);
        } elseif (str_contains($sourceFile, 'Requests')) {
            $content = $this->transformRequestNamespaces($content);
        } elseif (str_contains($sourceFile, 'routes')) {
            $content = $this->transformRouteNamespaces($content);
        }

        return $content;
    }

    /**
     * Transform controller-specific namespaces
     */
    protected function transformControllerNamespaces($content)
    {
        // Update use statements for models and requests
        $content = str_replace(
            'use admin\\wishlists\\Models\\Wishlist;',
            'use Modules\\wishlists\\app\\Models\\Wishlist;',
            $content
        );


        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        // Any model-specific transformations
        return $content;
    }

    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        // Any request-specific transformations
        return $content;
    }

    /**
     * Transform route-specific namespaces
     */
    protected function transformRouteNamespaces($content)
    {
        // Update controller references in routes
        $content = str_replace(
            'admin\\wishlists\\Controllers\\WishlistManagerController',
            'Modules\\wishlists\\app\\Http\\Controllers\\Admin\\WishlistManagerController',
            $content
        );

        return $content;
    }
}
