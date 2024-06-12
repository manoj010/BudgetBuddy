# BudgetBuddy API

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Project](#running-the-project)
- [Swagger Documentation](#swagger-documentation)

## Installation

1. **Clone the repository**

    ```sh
    git clone https://github.com/manoj010/BudgetBuddy.git
    ```

2. **Install dependencies**

    Make sure you have [Composer](https://getcomposer.org/) installed.

    ```sh
    composer install
    ```

3. **Install npm dependencies**

    Make sure you have [Node.js](https://nodejs.org/) and npm installed.

    ```sh
    npm install
    ```

## Configuration

1. **Copy the `.env` file**

    ```sh
    cp .env.example .env
    ```

2. **Generate an application key**

    ```sh
    php artisan key:generate
    ```

3. **Set up your database**

    Update the `.env` file with your database credentials.

    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=yourdatabase
    DB_USERNAME=yourusername
    DB_PASSWORD=yourpassword
    ```

## Running the Project

1. **Run database migrations**

    ```sh
    php artisan migrate
    ```

2. **Run database seeders (optional)**

    ```sh
    php artisan db:seed
    ```

3. **Start the development server**

    ```sh
    php artisan serve
    ```

    The project will be available at [http://localhost:8000](http://localhost:8000).

## Database Migration


