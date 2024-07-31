# Maasai Market Online

Welcome to Maasai Market Online, a platform dedicated to selling authentic Maasai and African antiques, jewelry, and fabrics. This project is built using Laravel for the backend and VueJS for the frontend, with MySQL as the database.

## Table of Contents

1. Introduction

2. Features

3. Installation

4. Usage

5. Contributing

6. License

7. Contact

## Introduction

Maasai Market Online is designed to showcase and sell traditional Maasai and African crafts. It focuses on simplicity and user experience, with a straightforward design for easy navigation.

## Features

- Display of authentic Maasai and African products
- Pre-order feature for products
- Blog section for updates and stories
- Easy-to-navigate interface

### Installation

Before you start, ensure you have the following installed:

- PHP >= 8.0
- Composer
- Node.js and npm
- MySQL

### Clone the Repository

- To get started, clone the repository:

```
git clone https://github.com/yourusername/cargosai.git
cd cargosai
```

##### Backend Setup

1. Install dependencies:

```
composer install
```

2. Copy the example environment file and update environment settings:

```
cp .env.example .env
```

Edit the .env file to set your database credentials and other environment settings.

3. Generate the application key:

```
php artisan key:generate
```

4. Run migrations and seed the database:

```
php artisan migrate --seed
```

#### Frontend Setup

1. Navigate to the frontend directory:

```
cd frontend
```

2. Install npm dependencies:

```
npm install
```

3. Run the development server

```
npm run dev
```

#### Usage

After setting up the backend and frontend, start the application by running the Laravel server:

```
php artisan serve
```

Then, visit http://localhost:8000 in your browser to view the application.

#### License

This project is licensed under the MIT License - see the LICENSE file for details.
