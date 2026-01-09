# Laravel Invoices Management System

A web-based **Invoices Management System** built using **Laravel**, designed to manage invoices, clients, payments.

---

## 📌 Features

- User authentication (Login / Register)
- Create, update, delete invoices
- Invoice status tracking (Paid / Unpaid / Partial)
- PDF invoice generation
- Search & filter invoices
- Create, update, delete receipts
- Create, update, delete expenses
- Create, update, delete products
- Create, update, delete sections
- Clients management
- User authorization Roles & permissions
- Archives management
- Responsive dashboard

---

## 🛠️ Technologies Used

- Laravel 10+
- PHP 8.2+
- MySQL
- Blade Templates
- Bootstrap
---
---
## ⚙️ Installation

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/ahmed4-75/invoices_system.git
cd laravel-invoices_system
```

### 2️⃣ Install Dependencies
```bash
composer install
npm run build  
```

### 3️⃣ Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```
---
---
### 🗄️ Database Configuration

### 1️⃣ Update .env file :
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=invoices_system
DB_USERNAME=root
DB_PASSWORD=
```
### 2️⃣ Run migrations:
```bash
php artisan migrate
```
### 3️⃣ Run Seeders
```bash
php artisan db:seed --class=RolesPermissionsSeeder
```
---
---
### 🚀 Run the Application
```bash
php artisan serve
```
### Open in browser:
```bash
http://127.0.0.1:8000
```