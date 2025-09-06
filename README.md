
## 🎯 Descripción del Proyecto

Sistema integral de gestión para bibliotecas digitales desarrollado con **CodeIgniter 4** que permite administrar recursos bibliográficos, libros, personas y organizaciones de manera eficiente y moderna.

### ✨ Características Principales

- 📖 **Gestión de Recursos Digitales** - Administración de recursos bibliográficos
- 📚 **Catálogo de Libros** - Control de inventario y clasificación
- 📱 **Interfaz Responsiva** - Diseño adaptable con Bootstrap 5
- 🎨 **UI Moderna** - Interfaz intuitiva con Bootstrap Icons

## 🚀 Tecnologías Utilizadas

### Backend
- **PHP 8.1+** - Lenguaje de programación principal
- **CodeIgniter 4** - Framework PHP moderno y ligero
- **MySQL** - Sistema de gestión de base de datos

### Frontend
- **Bootstrap 5.2.3** - Framework CSS responsivo
- **Bootstrap Icons** - Librería de iconos oficial
- **JavaScript ES6+** - Funcionalidades dinámicas
- **SweetAlert2** - Alertas y confirmaciones elegantes
- **Toastify** - Notificaciones toast modernas

### Herramientas de Desarrollo
- **Composer** - Gestor de dependencias PHP
- **PHPUnit** - Framework de testing
- **Git** - Control de versiones

## 📋 Funcionalidades por Módulo

### 🗂️ Gestión de Recursos
- ✅ Listado de recursos
- ✅ Creación y edición de recursos
- ✅ Carga de archivos (PDF, imágenes)
- ✅ Categorización por tipo y estado
- ✅ Vista previa de portadas
- ✅ Control de estados (Bueno, Regular, Malo)

## 🏗️ Arquitectura del Sistema

```
biblioteca/
├── app/
│   ├── Controllers/          # Controladores MVC
│   │   ├── LibroController.php
│   │   ├── RecursoController.php
│   │   ├── PersonaController.php
│   │   └── ...
│   ├── Models/              # Modelos de datos
│   │   ├── Libro.php
│   │   ├── Recurso.php
│   │   ├── Categoria.php
│   │   └── ...
│   ├── Views/               # Vistas y templates
│   │   ├── Layouts/
│   │   ├── recursos/
│   │   ├── libros/
│   │   └── ...
│   ├── Config/              # Configuraciones
│   └── Database/            # Migraciones y seeds
├── public/                  # Archivos públicos
│   ├── uploads/             # Archivos subidos
│   └── index.php
├── writable/               # Archivos escribibles
└── vendor/                 # Dependencias
```

## ⚙️ Instalación y Configuración

### Prerrequisitos
- PHP 8.1 o superior
- MySQL 5.7+ o MariaDB
- Composer
- Servidor web (Apache/Nginx)

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/usuarioCL/biblioteca.git
   cd biblioteca
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   ```

3. **Configurar base de datos**
   ```bash
   # Copiar archivo de configuración
   cp env.example .env
   
   # Editar configuración de BD en .env
   database.default.hostname = localhost
   database.default.database = biblioteca
   database.default.username = tu_usuario
   database.default.password = tu_password
   ```



## 📊 Estructura de Base de Datos

### Tablas Principales
- `recursos` - Almacena recursos digitales
- `editoriales` - Casas editoras
- `categorias` - Clasificación de contenido
- `subcategorias` - Subclasificaciones

### Entorno de Desarrollo
```bash
php spark serve
# Accede a: http://localhost:8080
```

### Entorno de Producción
1. Configurar servidor web
2. Ajustar configuraciones en `.env`
3. Ejecutar optimizaciones:
   ```bash
   composer install --no-dev --optimize-autoloader
   php spark optimize
   ```
