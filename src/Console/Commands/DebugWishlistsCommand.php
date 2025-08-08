<?php

namespace admin\wishlists\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class DebugWishlistsCommand extends Command
{
    protected $signature = 'wishlists:debug';
    protected $description = 'Debug Wishlists module loading';

    public function handle()
    {
        $this->info(' Debugging Wishlists Module...');
        
        // Check which route file is being loaded
        $this->info("\n Route Loading Priority:");
        $moduleRoutes = base_path('Modules/Wishlists/routes/web.php');
        $packageRoutes = base_path('packages/admin/wishlists/src/routes/web.php');
        
        if (File::exists($moduleRoutes)) {
            $this->info(" Module routes found: {$moduleRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($moduleRoutes)));
        } else {
            $this->error(" Module routes not found");
        }
        
        if (File::exists($packageRoutes)) {
            $this->info("Package routes found: {$packageRoutes}");
            $this->info(" Last modified: " . date('Y-m-d H:i:s', filemtime($packageRoutes)));
        } else {
            $this->error(" Package routes not found");
        }
        
        // Check view loading priority
        $this->info("\n View Loading Priority:");
        $viewPaths = [
            'Module views' => base_path('Modules/Wishlists/resources/views'),
            'Published views' => resource_path('views/admin/wishlist'),
            'Package views' => base_path('packages/admin/wishlists/resources/views'),
        ];
        
        foreach ($viewPaths as $name => $path) {
            if (File::exists($path)) {
                $this->info("{$name}: {$path}");
            } else {
                $this->warn(" {$name}: NOT FOUND - {$path}");
            }
        }
        
        // Check controller resolution
        $this->info("\n Controller Resolution:");
        $controllerClass = 'Modules\\Wishlists\\app\\Http\\Controllers\\Admin\\WishlistManagerController';
        
        if (class_exists($controllerClass)) {
            $this->info(" Controller class found: {$controllerClass}");
            
            $reflection = new \ReflectionClass($controllerClass);
            $this->info(" File: " . $reflection->getFileName());
            $this->info(" Last modified: " . date('Y-m-d H:i:s', filemtime($reflection->getFileName())));
        } else {
            $this->error("Controller class not found: {$controllerClass}");
        }
        
        // Show current routes
        $this->info("\n Current Routes:");
        $routes = Route::getRoutes();
        $wishlistRoutes = [];
        
        foreach ($routes as $route) {
            $action = $route->getAction();
            if (isset($action['controller']) && str_contains($action['controller'], 'WishlistManagerController')) {
                $wishlistRoutes[] = [
                    'uri' => $route->uri(),
                    'methods' => implode('|', $route->methods()),
                    'controller' => $action['controller'],
                    'name' => $route->getName(),
                ];
            }
        }
        
        if (!empty($wishlistRoutes)) {
            $this->table(['URI', 'Methods', 'Controller', 'Name'], $wishlistRoutes);
        } else {
            $this->warn("No wishlist routes found.");
        }
    }
}
