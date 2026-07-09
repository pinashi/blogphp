# Blog PHP

A simple blog application built with pure PHP following MVC pattern.

## Features

- User registration and authentication
- Create, edit, delete posts
- Comments system
- Access control — only authors can edit their posts

## Tech Stack

- PHP 8.2
- MySQL 8.0
- Nginx
- Docker

## Requirements

- Docker
- Docker Compose

## Installation

1. Clone the repository
```bash
git clone https://github.com/pinashi/blogphp.git
cd blogphp
```

2. Copy environment file
```bash
cp src/.env.example src/.env
```

3. Start Docker containers
```bash
docker compose up -d
```

4. Open Adminer at `http://localhost:8081`
   - Server: `mysql`
   - Username: `blog_user`
   - Password: `blog_password`
   - Database: `blog`

5. Run the following SQL to create tables:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

6. Open `http://localhost:8080`

## Project Structure

```
src/
  Controllers/    — request handlers
  Models/         — database interaction
  Views/          — HTML templates
  config/         — database configuration
  public/         — entry point
```