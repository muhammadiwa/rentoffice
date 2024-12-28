# RentOffice - Office Space Rental Platform

A modern office space rental platform built with Laravel and React.js that enables users to book and manage office spaces efficiently.

## Features

- 🏢 Office Space Listings
- 📅 Real-time Booking System
- 💳 Payment Integration
- 📱 WhatsApp Notifications
- 👥 User Management
- 📊 Admin Dashboard using Filament

## Tech Stack

### Backend
- Laravel 11
- MySQL
- Filament Admin Panel
- REST API

### Frontend
- React.js
- Tailwind CSS
- Axios
- React Router

## Prerequisites

- PHP >= 8.1
- Node.js >= 16
- Composer
- MySQL

## Installation

1. Clone the repository
```bash
git clone https://github.com/muhammadiwa/rentoffice.git
cd rentoffice
```
2. Install PHP dependencies
```bash
composer install
```
3. Install Node.js dependencies
```bash
npm install
```
4. Create a `.env` file and configure your database settings
```bash
cp .env.example .env
```
5. Generate an application key
```bash
php artisan key:generate
```
6. Run database migrations and seed the database
```bash
php artisan migrate --seed
```
7. Start the development server
```bash
php artisan serve
```
8. Start the React development
```bash
npm run dev
```

## Contributing
Feel free to contribute to the project by opening issues or submitting pull requests.
1. Fork the repository
2. Create a new branch for your feature or bug fix `git checkout -b feature/your-feature`
3. Commit your changes `git commit -m 'Add some feature'`
4. Push to the branch `git push origin feature/your-feature`
5. Open a pull request

## License
This project is licensed under the MIT License - see the LICENSE file for details

## Contact
Muhammad Iwa - @muhammadiwa

Project Link: https://github.com/muhammadiwa/rentoffice
