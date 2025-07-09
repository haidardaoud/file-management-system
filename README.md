# Collaborative File Management System

A Laravel-based file collaboration system ensuring no two users edit the same file simultaneously, with backup, access control, and real-time locking.

## ⚙️ Features
- File locking: out-check / in-check
- Batch locking: all-or-nothing
- Real-time file state updates
- Group-based file access
- Auto backups before and after edit
- User activity logs and trace reports
- Notifications for file status changes
- PDF/CSV reports export

## 🧰 Tech Stack
- Laravel, Filament, MySQL
- Livewire, Blade

## 📦 Installation
1. `git clone`
2. `composer install`
3. Create `.env` and configure DB
4. `php artisan migrate`
5. `php artisan serve`

## 🔐 Admin Panel
Built with Filament for efficient internal use

## 📄 License
Graduation capstone project
