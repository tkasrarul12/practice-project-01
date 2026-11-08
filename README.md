
# Docker-Based Multi-Container Login & Signup Application on AWS

A simple 3d web application where users can create an account and log in with their email and password.

The project is built with AWS Cloud, HTML, CSS and JavaScript on the frontend, PHP on the backend, and MySQL for storing user details. Docker is used to run the different parts in separate containers.

## What This Project Does

- Shows a signup form for new users.
- Allows existing users to log in.
- Checks whether an email is already registered.
- Saves user details in a MySQL database.
- Stores passwords as secure hashes instead of plain text.
- Shows a welcome message after a successful login.
- Opens a dashboard after login.

## Technologies Used

| Technology | Purpose |
| --- | --- |
| HTML | Builds the web pages and forms |
| CSS | Adds the design and layout |
| JavaScript | Handles form actions and sends requests to the backend |
| Nginx | Serves the frontend and forwards PHP requests |
| PHP 8.2 | Handles signup and login requests |
| MySQL | Stores user account details |
| Docker | Runs the frontend and backend in containers |

## AWS Deployment Environment

This project was deployed on **AWS** using an **Amazon EC2** virtual server.

The setup included:

- **Amazon EC2:** Used to create and run the cloud server.
- **Custom VPC:** A separate AWS network created for this project.
- **Ubuntu Linux:** Used as the operating system on the EC2 instance.
- **Docker:** Used on the Ubuntu server to build and run the application containers.

This setup helped me practise running a Docker-based application on a cloud server instead of only running it on a local computer.

## How the Project Works

The application has three main parts:

1. **Frontend:** The user opens the website and enters their signup or login details.
2. **Backend:** JavaScript sends those details to `auth.php`. PHP checks the input and communicates with the database.
3. **Database:** MySQL stores the user details in a table named `users`.

## Project Structure

```text
practice-project-01/
├── backend/
│   ├── Dockerfile
│   ├── auth.php
│   └── db.env
│
└── frontend/
    ├── Dockerfile
    ├── dashboard.html
    ├── index.html
    ├── nginx.conf
    ├── script.js
    └── style.css
```

## Requirements

Before running the project, make sure you have:

- Docker installed.
- A Docker network that the frontend, backend and MySQL containers can use to communicate.
- A MySQL container with a database named `testing`.
- The container names and environment settings configured to match the values used by this project.

## Database Settings

The backend uses these database settings by default:

| Setting | Value |
| --- | --- |
| Database host | `condb` |
| Database port | `3306` |
| Database name | `testing` |
| Database username | `root` |
| Database password | `12345` |

The backend creates the `users` table automatically when it connects to the database for the first time.

The table contains:

- `id` – a unique ID for each user.
- `full_name` – the user's name.
- `email` – the user's email address.
- `password` – the hashed password.
- `created_at` – the date and time when the account was created.

**Important:** These are the current practice settings from the project. Change the database password before using the application outside a local learning environment. Do not upload real passwords or secrets to a public GitHub repository.

## Deploying on AWS EC2

The application is hosted on an Ubuntu Linux EC2 instance inside the project's custom VPC.

At a high level, the deployment steps are:

1. Create a custom VPC in AWS.
2. Launch an EC2 instance with Ubuntu Linux.
3. Connect to the EC2 instance.
4. Install and use Docker on the Ubuntu server.
5. Build the frontend and backend Docker images.
6. Run the application containers and connect them through a Docker network.
7. Open the application using the EC2 public address and the configured port.

## Build the Docker Images

Open a terminal in the folder that contains `backend` and `frontend`.

Build the frontend image:

```bash
docker build -t tashaddock/fronti .
```

Build the backend image:

```bash
docker build -t tashaddock/backi .
```

## Docker Hub Images

I have also built and uploaded the frontend and backend Docker images to Docker Hub.

### Available Docker Hub Images:

- Frontend Image:
https://hub.docker.com/repository/docker/tashaddock/fronti/general

- Backend Image:
https://hub.docker.com/repository/docker/tashaddock/backi/general

## Run the Containers

The containers must be on the same Docker network so they can reach each other by container name.

The current configuration expects:

- MySQL to be reachable as `condb` on port `3306`.
- PHP-FPM to be reachable as `conb` on port `9000`.

Make sure your MySQL and backend containers use those names, or update `DB_HOST` in the backend settings and `fastcgi_pass` in `frontend/nginx.conf` to match your actual container names.

After starting the containers, publish the frontend's port `80` to a port on your machine or server. Then open the frontend address in a browser.

## Important Configuration Notes

- The frontend sends signup and login requests to `auth.php`.
- Nginx forwards PHP requests to the PHP-FPM service at `conb:9000`.
- PHP connects to MySQL at `condb:3306` by default.
- The `db.env` file is copied into the backend image, but copying an environment file into an image does not automatically load its values. Pass the settings to the backend container when you start it, for example with Docker's `--env-file` option, or set the environment variables another way.
- Keep the backend, frontend and database on a network where they can communicate.

## Future Improvements

Some useful improvements for a next version could be:

- Add a logout button.
- Add proper server-side login sessions.
- Add a password reset option.
- Add stronger input checks and clearer error messages.
- Move database passwords to secure environment settings.
- Add a proper access check for the dashboard.

#### Application Screenshots

### 1. AWS custom VPC

<img width="1920" height="1080" alt="Screenshot (149)" src="https://github.com/user-attachments/assets/851b2b0e-bb44-47a5-be57-030a8b91302f" />

### 2. AWS EC2 Instance

<img width="1920" height="1080" alt="Screenshot (150)" src="https://github.com/user-attachments/assets/a14b401e-29af-4086-ae18-bdccf1cc49d6" />

### 3. Project Directories Structure

<img width="1684" height="904" alt="Screenshot (151)" src="https://github.com/user-attachments/assets/803e1510-5376-4f63-93b3-3c270bca3336" />

### 4. Docker Containers

<img width="1920" height="1080" alt="Screenshot (152)" src="https://github.com/user-attachments/assets/17e93c5c-8fd2-401b-9b83-5ef40588531c" />

### 5. Signup page

<img width="1920" height="1080" alt="Screenshot (154)" src="https://github.com/user-attachments/assets/1219854e-99a1-4476-bf23-29ef3b2c2704" />

### 6. Login Page

<img width="1920" height="1080" alt="Screenshot (155)" src="https://github.com/user-attachments/assets/00104e52-c452-4654-aa4a-c9f525eee7fe" />

### 7. Dashboard

<img width="1920" height="1080" alt="Screenshot (156)" src="https://github.com/user-attachments/assets/2d0b2690-9ec4-4af1-9cf1-be112fa07450" />

### 8. MySQL Database Management

<img width="1920" height="1080" alt="Screenshot (157)" src="https://github.com/user-attachments/assets/730f9c1e-4bad-4929-af59-9256c3584470" />

## Author

**Tashad Asrari**

