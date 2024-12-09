# Funeral Management System (FMS)

### Overview
The **Funeral Management System (FMS)** is a web-based platform designed to streamline operations for funeral service providers. It helps manage logistics, inventory, scheduling, payments, client information, and user accounts efficiently.

---

## Features

### 1. Logistics Management
- Tracks body pickup and transportation.
- Records vehicle details, driver info, and destinations.
- Updates transport status (`pending` or `completed`).
- Generates reports by date range.

### 2. Inventory Management
- Manages stock of coffins, urns, caskets, chapels, etc.
- Updates inventory levels in real-time.
- Generates inventory reports for specific date ranges.

### 3. Scheduling and Records
- Schedules funerals, cremations, and memorials.
- Includes **Google Maps** for location tracking.
- Ensures compliance with local regulations.
- Provides event reports.

### 4. Financial Management
- Handles payments, invoices, and receipts.
- Calculates totals, taxes, discounts, and fees.
- Generates financial reports by date range.

### 5. Client Information
- Stores client and deceased details.
- Records agreements and service requests.
- Tracks interactions and generates client reports.

### 6. User Accounts
- Supports **Admin** and **Others** users roles.
- Manages user roles, permissions, and access.
- Implements secure authentication.

---


## Screenshots

### 1. Dashboard
![Dashboard](img/dashboard.png)

### 2. Booking Form
![Booking Form](img/bookings.png)

### 3. Inventory Management
![Inventory Management](img/inventory.png)

### 4. Client Records
![Client Records](img/clients.png)

### 5. Records
![Server Records](img/reports.png)

### 6. Payments
![Payments](img/payment.png)

### 7. Client Report
![Client Report](img/client-report.png)


---
## Screenshots

### 1. Dashboard
![Dashboard](screenshots/dashboard.png)

### 2. Booking Form
![Booking Form](screenshots/booking_form.png)

### 3. Inventory Management
![Inventory Management](screenshots/inventory_management.png)

### 4. Client Records
![Client Records](screenshots/client_records.png)

---


## Technologies Used

### Frontend
- **HTML5**: Structure
- **CSS3**: Styling
- **Bootstrap**: Responsive design and UI components

### Backend
- **PHP**: Server-side scripting
- **MySQL**: Database management

---

## Database Structure
| Table Name         | Description                                     |
|--------------------|-------------------------------------------------|
| `users`            | User credentials and roles (admin, staff).      |
| `clients`          | Family representative information.              |
| `deceased`         | Details of deceased individuals.               |
| `logistics`        | Tracks transportation details.                 |
| `inventory`        | Stock management (coffins, urns, etc.).         |
| `schedules`        | Funeral and cremation scheduling.               |
| `payments`         | Payment and financial transaction records.      |
| `client_interactions` | Tracks client interactions and requests.    |
| `reports`          | Stores generated reports.                      |

---

## Installation and Setup

### Prerequisites
- **XAMPP** or any PHP and MySQL-compatible server.
- A browser to access the web interface.

### Steps
1. Clone or download the repository.
2. Place the project folder in your web server's root directory (e.g., `htdocs` for XAMPP).
3. Import the provided `fms.sql` file into your MySQL database.
4. Update the database configuration in `config.php`.
5. Access the application via your browser (`http://localhost/fms`).

---

## Usage

### Admin
- Full control over logistics, inventory, scheduling, financials, and user accounts.

### Staff
- Limited access to manage logistics, inventory, scheduling, and client information.

---



