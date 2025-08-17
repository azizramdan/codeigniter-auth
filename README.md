# CodeIgniter Auth
A minimal starter backend for secure token-based API authentication using CodeIgniter 4 and Shield, featuring role-based access control (RBAC) with groups and permissions. Designed to work seamlessly with [Nuxt Auth project](https://github.com/azizramdan/nuxt-auth).

## Tech stack
- CodeIgniter 4
- CodeIgniter Shield
- PHP 8.4

## Run project

```bash
# setup .env values
cp env .env

# install dependencies
composer install

# run migration
php spark migrate --all

# run users seeder
php spark db:seed UserSeeder

# run dev mode
php spark serve
```

## Notes

- This starter is designed specifically as an API backend: **all responses are JSON**.
- Some default behaviors in CodeIgniter Shield (e.g. `group` and `permission` filters) return redirects, which are not suitable for APIs.
- To provide consistent and meaningful HTTP responses for API clients, the `group` and `permission` filters have been **overridden to return JSON** instead of redirects.
- See [Config/Events.php](app/Config/Events.php), [Filters/GroupFilter.php](app/Filters/GroupFilter.php), and [Filters/PermissionFilter.php](app/Filters/PermissionFilter.php) for details.