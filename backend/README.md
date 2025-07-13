# Backend influye test

## 💽 Levantar el backend

1. Crea la base de datos y renombra `.env.example` a `.env`.
   Luego, ajusta las credenciales (nombre de BD, usuario, contraseña).

2. Desde la carpeta `backend/`, ejecuta:

```bash
composer install
php artisan migrate    # aplica las migraciones de laravel
php artisan serve     
```
