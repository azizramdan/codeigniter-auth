# CodeIgniter Auth
A minimal starter backend for secure token-based API authentication using CodeIgniter 4 and Shield, featuring role-based access control (RBAC) with groups and permissions. Designed to work seamlessly with Nuxt frontend projects ([see example repo](https://github.com/azizramdan/nuxt-auth)).

## Tech stack
- CodeIgniter 4
- CodeIgniter Shield
- PHP 8.4

# Run project

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