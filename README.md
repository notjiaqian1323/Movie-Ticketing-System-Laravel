# 🎟️ Movie Ticket Booking System

A scalable, reusable movie ticket booking system built with Laravel Livewire Components, and design patterns, web services.

## Table of Contents
- [About](#about)
- [Tech Stack](#tech-stack)
- [Installation](#installation)


## About 💡
### 💡 Core Functionalities

| Feature | Description | Design Pattern Used |
| :--- | :--- | :--- |
| **Managing Movies** | Allow admins to add new movies, editing, filtering and listing movies. | State Pattern |
| **Managing Schedules** | Allow admins to add, editing, filtering movie showtimes. | Strategy Pattern |
| **Managing Bookings** | Selecting seats, booking tickets for desired movies and generating receipt. | Facade Pattern |
| **Managing Reviews** | Create reviews for booked movies, editing and managing the reviews. | Observer Pattern |


## Tech Stack 💻 

-  Laravel
-  Tailwind CSS
-  MySQL
-  Livewire
-  Vite



## Installation 
## 💾 Installation and Setup

### Prerequisites
Before you begin, ensure you have the following installed on your system:
* Laravel 12
* Git
* Composer
* Node.js
* XAMPP

Make sure to have these in your `.env` file:
```bash

    MOVIE_API_URL=http://localhost:8001/api
    REVIEW_API_URL=http://localhost:8002/api
    ACCOUNT_API_URL=http://localhost:8003/api
    MOVIE_ADMIN_API_URL=http://localhost:8004/api/admin
    INTERNAL_API_TOKEN=1|OBf4ERAnQxcTY1MeOKZl4L4fgfZ16qW65cBQWWnD25e9bde2
    MOVIE_ADMIN_TOKEN=1|OBf4ERAnQxcTY1MeOKZl4L4fgfZ16qW65cBQWWnD25e9bde2
    SCHEDULE_API_URL=http://localhost:8005/api
    BOOKING_API_URL = http://localhost:8006/api

```


### Steps

1.  **Install Composer and NPM**
    It's best practice to do so to make sure everything is in place to start the app:
    ```bash
    composer install
    npm install
    ```

2.  **Run the Migrations**
    It's best to run the migrations and seeders completely before starting the application
    ```bash
    php artisan migrate:fresh --seed
    ```

3.  **Linking the Storage**
    Linking the storage to the app to make sure any updates to the images will be updated too for each:
    ```
    php artisan storage:link
    ```

4.  **Run the Application**
    Carry out the following steps in separate command lines to make it work:
    ```bash
    npm run dev
    php artisan serve --port=8000
    php artisan serve --port=8001
    php artisan serve --port=8002
    php artisan serve --port=8003
    php artisan serve --port=8004
    php artisan serve --port=8005
    php artisan serve --port=8006
    ```


