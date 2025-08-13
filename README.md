# Admin Wishlist Manager

This package provides an Admin Wishlist Manager for managing user wishlists within your application.

## Features

- View all wishlists created by users
- View details of individual wishlists
- Search and filter wishlists in the admin panel
- Paginated wishlist listing
- User permissions and access control for wishlist management

## Usage

1. **Read**: View all wishlists in a paginated list.
2. **Show**: View details of a specific wishlist.

## Example Endpoints

| Method | Endpoint           | Description              |
|--------|--------------------|--------------------------|
| GET    | `/wishlists`       | List all wishlists       |
| GET    | `/wishlists/{id}`  | Show wishlist details    |

## Requirements

- PHP 8.2+
- Laravel Framework

## Need to update `composer.json` file

Add the following to your `composer.json` to use the package from a local path:

```json
"repositories": [
    {
        "type": "vcs",
         "url": "https://github.com/pavanraj92/admin-wishlist.git"
    }
]
```

## Installation

```bash
composer require admin/wishlists:@dev
```

## Usage

1. Publish the configuration and migration files:
    ```bash
    php artisan wishlists:publish --force

    composer dump-autoload
    
    php artisan migrate
    ```
2. Access the Wishlist manager from your admin dashboard.

## Example

```php
// Viewing all wishlists
$wishlists = Wishlist::paginate(20);

// Viewing a single wishlist
$wishlist = Wishlist::find($id);
```

## Customization

You can customize views, routes, and permissions by editing the configuration file.

## License

This package is open-sourced software licensed under the MIT license.
