
# Setup Documentation for Laravel News Aggregator with Pest Testing

A brief description of what this project does and who it's for


## Prerequisites

Before you begin, ensure you have the following installed on your machine:

- PHP (version 8.2 or higher)
- Composer (dependency manager for PHP)
- Laravel (the framework used in this project)
- Node.js (for front-end dependencies)
- MySQL (or another supported database)
## Installation

### Step 1: Clone the Repository


```bash
  git clone https://github.com/Masumrahmanhasan/news-aggregator.git
  cd news-aggregator
```

### Step 2: Install Dependencies


```bash
  composer install
  npm install && npm run dev
``` 


### Step 3: Setup Database and run migration


```bash
  cp .env.example .env 
  cp .env.example .env.testing 
  php artisan key:generate 
  php artisan migrate
``` 

### Step 3: Console Command to run the Fetch Article Command


```bash
  php artisan fetch-articles
``` 

### Step 3: Run test


```bash
  ./vendor/bin/pest
``` 


## API Reference

#### Login

```http
  POST /api/v1/auth/login
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**. Your email address |
| `password` | `string` | **Required**. Your password |

#### Register

```http
  POST /api/v1/auth/register
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**. Your email address |
| `password` | `string` | **Required**. Your password |
| `name` | `string` | **Required**. Your name |
| `password_confirmation` | `string` | **Required**. confirm password |


#### Forget password

```http
  POST /api/v1/auth/password/forgot
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**. Your email address |



#### Reset password

```http
  POST /api/v1/auth/password/reset
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**. Your email address |
| `password` | `string` | **Required**. Your password |
| `password_confirmation` | `string` | **Required**. Your confirm  password |
| `token` | `string` | **Required**. reset token |



#### Articles

```http
  GET /api/v1/articles
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `category` | `string` | **Optional**. Preferred Category |
| `source` | `string` | **Optional**. Preferred soource |
| `date` | `string` | **Optional**. Preferred date |
| `author` | `string` | **Optional**. Preferred author |


## Features

- User login/Register/Password Reset
- User Profile
- Articles Fetch with api
- User Preferences with personalized NewsFeed


## Documentation

[https://www.postman.com/funnell/news-aggregator/documentation/atv9zsv/news-aggregator?workspaceId=98337f3d-d665-4ac3-aeb3-342d228703c0](https://www.postman.com/funnell/news-aggregator/documentation/atv9zsv/news-aggregator?workspaceId=98337f3d-d665-4ac3-aeb3-342d228703c0)

