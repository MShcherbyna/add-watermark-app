# Check the base color and add a watermark

## Getting Started

- Rename ".env.example" to ".env"

```bash
mv .env.example .env
```

- Install dependencies for app

```bash
composer install && composer update
npm install
npm run dev
```

- Start application

```bash
php artisan serve
```

## Usage

Upload an image and submit the form, then you get pictures with a watermark (the browser should download a picture)
