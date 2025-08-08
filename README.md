# Admin Page (CMS) Manager

This package provides an Admin Page (CMS) Manager for managing content pages within your application.

## Features

- Create, edit, and delete CMS pages
- Organize pages with categories or hierarchies
- WYSIWYG editor support
- SEO-friendly URLs and metadata management
- User permissions and access control

## Usage

1. **Create**: Add a new page by providing a title, slug, content, and optional metadata.
2. **Read**: View all pages in a paginated list or access individual page details.
3. **Update**: Edit page content, metadata, or organizational structure.
4. **Delete**: Remove pages that are no longer needed.

## Example Endpoints

| Method | Endpoint         | Description              |
|--------|------------------|--------------------------|
| GET    | `/pages`         | List all pages           |
| POST   | `/pages`         | Create a new page        |
| GET    | `/pages/{id}`    | Get page details         |
| PUT    | `/pages/{id}`    | Update a page            |
| DELETE | `/pages/{id}`    | Delete a page            |

## Requirements

- PHP 8.2+
- Laravel Framework

## Need to update `composer.json` file

Add the following to your `composer.json` to use the package from a local path:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-pages.git"
    }
]
```

## Installation

```bash
composer require admin/pages:@dev
```

## Usage

1. Publish the configuration and migration files:
    ```bash
    php artisan pages:publish --force

    composer dump-autoload
    
    php artisan migrate
    ```
2. Access the CMS manager from your admin dashboard.

## Example

```php
// Creating a new page
$page = new Page();
$page->title = 'About Us';
$page->slug = 'about-us';
$page->content = '<p>Welcome to our website!</p>';
$page->save();
```

## Customization

You can customize views, routes, and permissions by editing the configuration file.

## License

This package is open-sourced software licensed under the Dotsquares.write code in the readme.md file regarding to the admin/page(CMS) manager
