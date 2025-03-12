# Installation Guide

## Clone the Repository

1. **Clone the repository**
   ```sh
   git clone <repository_url>
   ```

---

## Backend Setup

1. **Navigate to the backend directory**
   ```sh
   cd backend
   ```

2. **Install dependencies**
   ```sh
   composer install
   ```

3. **Copy `.env.example` and create a new `.env` file**
   ```sh
   cp .env.example .env
   ```

4. **Generate JWT secret key**
   ```sh
   php artisan jwt:secret
   ```

5. **Run database migrations**
   ```sh
   php artisan migrate
   ```

6. **Seed the database**
   ```sh
   php artisan db:seed
   ```

7. **Start the development server**
   ```sh
   php artisan serve
   ```

### Troubleshooting

- If you encounter a `file_put_contents` error, run the following commands:
  ```sh
  mkdir -p storage/framework/{sessions,cache,views}
  chmod -R 777 storage bootstrap/cache
  php artisan cache:clear
  php artisan config:clear
  php artisan config:cache
  php artisan optimize
  ```

---

## Frontend Setup

1. **Navigate to the frontend directory**
   ```sh
   cd frontend
   ```

2. **Copy `.env.example` and create a new `.env` file**
   ```sh
   cp .env.example .env
   ```

3. **Install dependencies**
   ```sh
   npm install
   ```

4. **Run the development server**
   ```sh
   npm run dev
   ```

1. **Navigate to the frontend directory**
   ```sh
   cd frontend
   ```

2. **Install dependencies**
   ```sh
   npm install
   ```

3. **Run the development server**
   ```sh
   npm run dev
   ```

### Troubleshooting

- If you encounter an error related to `primevue/toastservice`:
  ```sh
  rm -rf node_modules
  npm install
  ```
  Try again until it works.

---

## Docker Setup

1. **Build and start the Docker containers**
   ```sh
   docker-compose up --build -d
   ```

2. **Run migrations inside the Docker container**
   ```sh
   docker exec -it laravel_app php artisan migrate
   ```

3. **Seed the database inside the Docker container**
   ```sh
   docker exec -it laravel_app php artisan db:seed
   ```
