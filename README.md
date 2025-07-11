# Influye Test

Este repositorio contiene dos proyectos dentro de un **monorepo**:

- `frontend/` – Aplicación **Vue.js (SPA)**  
- `backend/` – API desarrollada en **Laravel**

## 🛠️ Requisitos

* **Git** Rara clonar y subir el proyecto
* **Node.js**
* **PHP** 7.4
* **MySQL**
* **Npm** Para instalar las dependencias del frontend
* **Composer** Para instalar las dependencias del backend

## 💻 Levantar el frontend

Desde la carpeta `frontend/`, ejecuta:
   
```bash
npm install
npm run serve
```

La configuración para conectar con el backend está en `Main.js`:

```js
window.axios = Axios.create({
  baseURL: 'http://localhost:8000/api'
});
```

Ejecutar test unitarios

```bash
npm run test:unit
```

Nota extra:

El flag `--openssl-legacy-provider` dentro del script `serve` solo es necesario desde Node.js v17 en adelante.

## 💽 Levantar el backend

1. Crea la base de datos y renombra `.env.example` a `.env`.
   Luego, ajusta las credenciales (nombre de BD, usuario, contraseña).

2. Desde la carpeta `backend/`, ejecuta:

```bash
composer install
php artisan migrate    # aplica las migraciones de laravel
php artisan serve     
```
