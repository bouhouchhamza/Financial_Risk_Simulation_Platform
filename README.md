# 💳 Fintech Fraud Detection & Simulation System

## 📌 Overview

This project is a **Fintech Web Application** built with Laravel that simulates financial transactions and detects suspicious or fraudulent activities.

The system analyzes transactions in real time, assigns a risk score, and generates alerts for administrators to review.

---

## 🎯 Objectives

* Monitor financial transactions
* Detect suspicious behavior automatically
* Generate alerts based on fraud rules
* Provide an admin system for review and decision-making
* Simulate transactions for testing fraud detection

---

## 🛠️ Tech Stack

* **Backend:** Laravel (PHP)
* **Frontend:** Blade + JavaScript (Chart.js)
* **Database:** MySQL
* **Architecture:** MVC + Service Layer + Dependency Injection
* **Queue & Sessions:** Database

---

## ⚙️ Features

### 👤 User Features

* Create and manage a startup
* Add transactions (sales / purchases)
* View dashboard:

  * Total revenue
  * Total expenses
  * Total transactions
* View fraud alerts related to their activity

---

### 🔍 Fraud Detection System

* Automatic transaction analysis
* Uses configurable **Fraud Rules**
* Example rules:

  * High transaction amount
  * Suspicious patterns
  * Duplicate transactions

Each rule contains:

* `threshold_value`
* `score_weight`
* `decision` (allow / review / block)

---

### 📊 Risk Scoring

* Each rule increases a **risk score**
* Final result includes:

  * `risk_score`
  * `risk_level` (low / medium / high)
  * `decision`

---

### 🚨 Alerts System

* Alerts generated automatically after analysis
* Each alert contains:

  * Flags (triggered rules)
  * Details
  * Status: `pending`, `approved`, `rejected`

---

### 🛡️ Admin Features

* Admin dashboard
* Review fraud alerts
* Approve / Reject / Investigate alerts
* Manage:

  * Users
  * Startups
  * Transactions
  * Fraud Rules

---

### 🧪 Simulation Module

* Simulate transactions
* Test fraud detection logic
* Validate system behavior

---

## 🔄 System Workflow

```
User creates transaction
→ TransactionController
→ TransactionService
→ FraudAnalysisService
→ FraudDetectionService
→ AlertService
→ Alert stored in database
→ Admin reviews alert
```

---

## 🧱 Architecture

The project follows clean architecture principles:

* **Controllers** → Handle HTTP requests
* **Services** → Business logic
* **Models** → Database interaction
* **Dependency Injection** → Loose coupling & maintainability

### Key Services:

* `TransactionService`
* `FraudAnalysisService`
* `FraudDetectionService`
* `AlertService`
* `DashboardService`

---

## 🗄️ Database Structure

Main tables:

* `users`
* `startups`
* `transactions`
* `fraud_rules`
* `alerts`

---

## 🚀 Installation

```bash
# Clone project
git clone <your-repo-url>

# Enter project
cd project-name

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env

# Configure database in .env

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start server
php artisan serve
```

---

## ⚙️ Environment Configuration

Update your `.env` file:

```
DB_DATABASE=pff
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🧪 Testing

Run tests using:

```bash
php artisan test
```

Includes:

* Transaction creation test
* Fraud detection trigger test
* Admin review workflow

---

## 📊 UI & Dashboard

* Charts built with **Chart.js**
* Revenue vs Expenses visualization
* Clean dark theme interface

---

## 📸 Screenshots

> Add screenshots here:

* Dashboard
* Transactions page
* Alerts page
* Admin panel

---

## 🔮 Future Improvements

* AI-based fraud detection
* Real-time notifications
* REST API integration
* Multi-tenant support
* Advanced analytics dashboard

---

## 👨‍💻 Author

* Your Name

---

## 📄 License

This project is for educational purposes (PFE / academic project).
