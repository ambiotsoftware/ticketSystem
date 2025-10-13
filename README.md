# Sistema de Tickets Avenir

Plataforma de gestión de tickets orientada a atención de soporte técnico con paneles diferenciados por rol, control de asignaciones, y notificaciones simuladas por correo.

## 🚀 Características

- **Autenticación y verificación** (Laravel Breeze)
- **Paneles por rol**:
  - **Cliente**: creación y seguimiento de sus tickets, acceso a "Mi Plan"
  - **Técnico**: tickets asignados, controles de tiempo (en progreso)
  - **Admin**: visión general, asignación rápida de tickets, categorías, notificaciones
- **Gestión de Tickets**: crear, ver, editar (según permisos), adjuntos (estructura incluida)
- **Asignación de Tickets**: manual por administradores, botón de asignación rápida desde el dashboard
- **Notificaciones**: generación de notificaciones simuladas en BD (no envía correos reales)
- **Control de roles y middlewares** para proteger rutas y vistas
- **Layout unificado** con navegación según rol

## 🛠 Tecnologías

- **PHP 8.4**
- **Laravel 12**
- **MySQL 8+**
- **Laravel Breeze** (autenticación)
- **Bootstrap personalizado** (CSS interno)

## 📋 Requerimientos

- PHP >= 8.2 con extensiones comunes (mbstring, openssl, pdo_mysql, etc.)
- Composer
- MySQL 8+ (o compatible)
- Node.js (opcional si ajustas los assets de Breeze)

## ⚙️ Instalación

### 1. Clonar e instalar dependencias

```bash
git clone <repositorio>
cd sistema_tickets
composer install
```

### 2. Configurar entorno

Crea tu archivo `.env` a partir del ejemplo:

```bash
cp .env.example .env
```

Asegúrate de configurar:

```env
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_tickets
DB_USERNAME=root
DB_PASSWORD=
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME= EL CORREO A USAR
MAIL_PASSWORD= LA PASSWORD DE APPS (SI ES GMAIL)
MAIL_FROM_ADDRESS= EL CORREO A USAR
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Generar APP_KEY

```bash
php artisan key:generate
```

### 4. Ejecutar migraciones

```bash
php artisan migrate
```

Esto creará las tablas:
- `users`, `sessions`, `jobs`
- `categories`, `tickets`, `ticket_time_entries`, `ticket_attachments`
- `email_notifications` (bandeja de salida simulada)

### 5. Levantar el servidor

```bash
php artisan serve
```

Accede a: **http://127.0.0.1:8000**

## 👥 Usuarios de Prueba

### Administradores
- `admin@avenir-support.com` / `password`
- `admin2@test.com` / `password123`

### Técnicos
- `tecnico@avenir-support.com` / `password`
- `tecnico2@test.com` / `password123`

### Clientes
- `cliente1@test.com` / `password123`
- `cliente2@test.com` / `password123`

## 🎭 Flujo por Rol

### 👤 Cliente
- ✅ Crear ticket
- ✅ Ver estado de sus tickets
- 🔄 "Mi Plan" (resumen de consumo/horas – vista base incluida)

### 🔧 Técnico
- ✅ Ver tickets asignados
- 🔄 Controles de tiempo (UI y endpoints en progreso)
- ❌ NO puede crear tickets

### 🛡️ Admin
- ✅ Dashboard con métricas
- ✅ Asignación rápida de tickets (botón "Asignar" en listado)
- ✅ Gestión de categorías
- ✅ Notificaciones generadas (sección 📧 Notificaciones)
- ✅ Crear tickets para cualquier cliente

## 📜 Reglas de Negocio

1. **Solo clientes y administradores pueden crear tickets**
2. **Técnicos NO pueden crear tickets**
3. **La asignación la realizan exclusivamente administradores**
4. **Al crear un ticket se generan notificaciones para**:
   - Cliente que registró el ticket (confirmación)
   - Hans Higueros (admin principal: `admin@avenir-support.com`)
   - Supervisores/Admins (para asignar)

## 📧 Sistema de Notificaciones (Simuladas)

**No se envían correos reales**. Se registran en la tabla `email_notifications`.

- **Ver notificaciones**: Menú Admin → 📧 Notificaciones
- **Marcar como enviadas**: Seleccionar y "Marcar como Enviadas"

### Estructura de notificaciones:
```php
- ticket_id, recipient_email, recipient_name, recipient_role
- subject, body, type, sent, sent_at, metadata
```

### Servicio: `NotificationService.php`
- `notifyTicketCreated(Ticket)`
- `notifyTicketAssigned(Ticket, Technician, AssignedBy)`

## ⚡ Asignación Rápida de Tickets

1. En el **Dashboard de Admin**, cada ticket reciente tiene un botón "Asignar"
2. Se abre un modal para escoger técnico
3. Ruta: `POST /tickets/{ticket}/assign` (name: `tickets.assign`)
4. Cambia el estado a "en_seguimiento" y genera notificaciones

## 📁 Estructura del Proyecto

```
app/
├── Http/Controllers/
│   ├── DashboardController.php
│   ├── TicketController.php
│   ├── CategoryController.php
│   └── EmailNotificationController.php
├── Models/
│   ├── Ticket.php
│   ├── Category.php
│   ├── User.php
│   └── EmailNotification.php
└── Services/
    └── NotificationService.php

resources/views/
├── layouts/ticket-system.blade.php
├── dashboard/{client,technician,admin}.blade.php
├── tickets/{index,create,edit,show}.blade.php
├── categories/{index,create,edit,show}.blade.php
├── admin/notifications/index.blade.php
└── profile/edit.blade.php
```

## 🗄️ Base de Datos

**Configuración por defecto** (ajustar en `.env`):
- Host: `127.0.0.1`
- Puerto: `3306`
- DB: `sistema_tickets`
- Usuario: `root`
- Password: vacío

**Herramientas recomendadas**:
- MySQL Workbench / DBeaver / HeidiSQL
- phpMyAdmin: `http://localhost/phpmyadmin`

## 🧰 Comandos Útiles

### Limpiar cachés
```bash
php artisan route:clear && php artisan config:clear && php artisan view:clear
```

### Ver rutas
```bash
php artisan route:list
```

### Inspección con Tinker
```bash
php artisan tinker
```

### Consultas de ejemplo
```php
// Ver todos los usuarios
User::all(['name', 'email', 'role']);

// Ver tickets de un usuario
User::find(5)->tickets;

// Ver notificaciones pendientes
App\Models\EmailNotification::where('sent', false)->get();
```

## 🚧 Roadmap / Próximos Pasos

- [ ] **Control de tiempo**: `TicketTimeController` completo
- [ ] **Sección "Mi Plan"**: cálculo de horas consumidas/disponibles/extras
- [ ] **Políticas de acceso** y pruebas automatizadas
- [ ] **Sistema de envío real** de correos (SMTP/Queue) opcional
- [ ] **Gestión de usuarios** desde admin
- [ ] **Reportes y métricas** avanzadas

## 💻 Notas para Windows (PowerShell)

- Ejecuta comandos con `pwsh` o PowerShell 7+
- Si usas XAMPP/WAMP, asegúrate que MySQL esté activo
- Verifica que los puertos no estén ocupados

## 🤝 Contribución

Sugerencias y PRs son bienvenidos. Antes de contribuir:
- Ejecuta migraciones y valida que el proyecto levante sin errores
- Mantén el estilo del layout `ticket-system` y las reglas de rol
- Sigue las convenciones de Laravel

## 📄 Licencia

Uso interno/privado. Ajustar según las necesidades del proyecto.
# ticketSystem
