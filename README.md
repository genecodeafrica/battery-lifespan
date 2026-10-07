# Battery Lifespan Management System

Repository: genecodeafrica

A final-year project developed by Gene Kiliyobas Chipau, Matriculation Number: 24/155703, as a Software and Web Development student at Federal Polytechnic Bauchi, Bauchi State.

This project is a full-stack web application for tracking battery health, recording performance measurements, and predicting remaining useful life (RUL) using machine learning. It combines a Laravel-based management dashboard with a FastAPI Python prediction service to help monitor battery degradation, identify potential failures early, and support maintenance decisions.

This project was designed to solve a real-world problem in battery monitoring and predictive maintenance while showcasing practical skills in web development, database design, API integration, and intelligent system development.

## Overview

Battery performance degrades over time, and predicting the end of a battery's useful life is critical for maintenance planning, safety, and operational efficiency. This final-year project provides a practical solution for:

- registering and managing battery assets
- storing measurement data such as capacity, voltage, temperature, and cycle statistics
- analyzing degradation trends over time
- generating AI-based lifespan predictions using a trained ML model
- creating reports and PDFs for review and maintenance decisions
- notifying users about battery health changes and warning levels

## Key Features

- Battery inventory management with detailed metadata
- Battery measurement tracking and historical analysis
- AI-driven Remaining Useful Life (RUL) prediction
- Estimated end-of-life cycle forecasting
- Admin and user access structure
- Notification center for health warnings and updates
- Report generation for individual batteries and portfolio summaries
- CSV/asset import workflow for efficient data onboarding
- Web application built to demonstrate full-stack development capability in a real-world domain

## Tech Stack

- Laravel 13 + PHP 8.3
- SQLite database
- Blade templating for the dashboard UI
- Python + FastAPI for ML prediction API
- scikit-learn / joblib-based deployed model
- Vite for frontend asset bundling
- DOMPDF for printable PDF reports

## Project Structure

```text
battery-lifespan/
├── app/                  # Laravel application logic
├── bootstrap/            # Laravel bootstrap files
├── config/               # Framework configuration
├── database/             # Migrations and seeders
├── ml/                   # Python ML service and trained model files
│   ├── api/
│   ├── datasets/
│   ├── models/
│   └── notebooks/
├── public/               # Public entry point
├── resources/            # Views, CSS, and JS assets
├── routes/               # Web routes
├── storage/              # Cache, logs, uploads, and framework data
├── tests/                # Test suite
├── .env.example          # Example environment file
├── composer.json         # PHP dependencies
├── package.json          # Frontend dependencies
├── artisan               # Laravel CLI
├── vite.config.js        # Vite config
├── phpunit.xml           # PHPUnit configuration
├── README.md             # Project documentation
└── LICENSE               # License file
```

## How It Works

1. A battery is created and stored in the Laravel application.
2. Measurements are added over time to track capacity, voltage, temperature, and lifecycle behavior.
3. The application sends the relevant battery features to the ML API.
4. The Python service loads the trained model and returns predicted RUL and estimated end-of-life cycle.
5. Results are stored and displayed in the dashboard for operational decision-making.
6. Reports and notifications help teams act before degradation becomes critical.

## Getting Started

### Prerequisites

- PHP 8.3+
- Composer
- Node.js and npm
- Python 3.10+

### 1. Install PHP dependencies

```bash
composer install
```

### 2. Install frontend dependencies

```bash
npm install
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Update your environment values as needed, especially database and app settings.

### 4. Run database migrations

```bash
php artisan migrate
```

### 5. Start the Laravel app

```bash
php artisan serve
```

### 6. Start the Python ML API

From the project root:

```bash
cd ml
```

On Linux/macOS:

```bash
source venv/bin/activate
uvicorn api.main:app --host 0.0.0.0 --port 8001 --reload
```

On Windows PowerShell:

```powershell
.\venv\Scripts\Activate.ps1
uvicorn api.main:app --host 0.0.0.0 --port 8001 --reload
```

The Laravel app is configured to call the ML service at the default URL:

```text
http://127.0.0.1:8001
```

## Environment Notes

The Laravel app expects the Python prediction API to be available at the `ML_API_URL` endpoint. The default is configured in `config/services.php` as:

```php
'ml' => [
    'url' => env('ML_API_URL', 'http://127.0.0.1:8001'),
],
```

If needed, set it explicitly in your `.env` file:

```env
ML_API_URL=http://127.0.0.1:8001
```

## Use Cases

- Battery maintenance planning
- Predictive monitoring for industrial or EV fleets
- R&D and battery lifecycle analysis
- Health tracking for asset-intensive operations
- Academic demonstration of software engineering, intelligent systems, and web-based data management

## Academic Context

This project was developed as part of my final-year academic work in Software and Web Development at Federal Polytechnic Bauchi, Bauchi State. The system demonstrates the integration of:

- backend development with Laravel
- database modeling and application logic
- frontend design with Blade and Vite
- machine learning with Python and FastAPI
- predictive analytics for real-world decision making

It reflects both the technical and problem-solving skills expected of a graduating student in software and web development.

## License

This project is open-source software licensed under the MIT License.

## Contributing

Contributions are welcome. If you want to improve the system, fix bugs, or expand the prediction logic, feel free to fork the repository and submit a pull request.
