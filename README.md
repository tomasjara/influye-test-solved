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
cd frontend
npm install
npm run serve
```

La configuración para conectar con el backend está en `Main.js`:

```js
window.axios = Axios.create({
  baseURL: 'http://localhost:8000/api'
});
```

## 💽 Levantar el backend

1. Crea la base de datos y renombra `.env.example` a `.env`.
   Luego, ajusta las credenciales (nombre de BD, usuario, contraseña).

2. Desde la carpeta `backend/`, ejecuta:

```bash
composer install
php artisan migrate    # aplica las migraciones
php artisan serve      # inicia Laravel en http://127.0.0.1:8000
```
