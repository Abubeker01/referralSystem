# Referral System

A referral system web application that allows users to register, log in, and earn points by referring others. The system supports multi-level referrals, tracks points, and provides visual analytics of referral activity.

## Features

- **User Registration & Login:** Secure sign-up and login with email and password.
- **Referral Codes:** Each user receives a unique referral code and link to invite others.
- **Multi-level Points:** Points are awarded for direct and indirect referrals (up to 3 levels).
- **Referral Dashboard:** Users can view their referral statistics, including:
  - Total points earned
  - Points from direct and indirect referrals
  - Charts showing earnings over the last 7 and 30 days
  - Table of referred users
- **Copy Referral Link:** Easy copy-to-clipboard functionality for sharing referral links.
- **Responsive UI:** Clean, modern interface for both desktop and mobile.

## Tech Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP (with MySQL)
- **Database:** MySQL (see `referal_system (1).sql` for schema)
- **Charts:** Chart.js, Moment.js

## Project Structure

```
referralSystem/
  ├── charts/                # PHP endpoints for chart data
  ├── form/                  # Login and signup forms and styles
  ├── img/                   # Images
  ├── chart.js               # Chart rendering logic
  ├── referal_code.js        # Referral link logic
  ├── referal_link.php       # API for user referral code
  ├── referral_code.php      # Referral code generation
  ├── multilevel_point.php   # Multi-level point assignment logic
  ├── register_user.php      # User registration handler
  ├── login_user.php         # User login handler
  ├── index.php              # Main dashboard
  ├── table.js               # Referral table logic
  ├── table.php              # API for referral table data
  ├── style.css              # Main styles
  ├── package.json           # JS dependencies
  └── referal_system (1).sql # Database schema and sample data
```

## Database

- Import `referal_system (1).sql` into your MySQL server to set up the required tables (`users`, `referrals`) and sample data.

## Setup & Usage

1. **Clone the repository** and place it in your web server's root directory.
2. **Import the database:**
   - Use phpMyAdmin or the MySQL CLI to import `referal_system (1).sql`.
3. **Configure database credentials:**
   - Update the database connection details in PHP files if needed (`localhost`, `root`, etc.).
4. **Install JS dependencies:**
   - Run `npm install` to install Chart.js and Moment.js (if using a build process).
5. **Access the app:**
   - Open `index.php` in your browser (ensure your web server is running).
