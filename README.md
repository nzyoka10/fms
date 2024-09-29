# Funeral Management System (FMS)

#### `Overview`

The **Funeral Management System (FMS)** is a web-based platform designed to streamline the operations of a funeral service provider. 
- It allows **admins** and **staff** to efficiently manage logistics, inventory, scheduling, payments, client information, and user accounts. 
- The system is built using **PHP** and **MySQL** for backend logic and storage, with a frontend powered by **HTML**, **CSS**, and **Bootstrap**.

---

## Features

### 1. **Logistics Management**
- Tracks the pickup and transportation of the deceased.
- Records transportation details such as vehicle used, driver, pickup date, and destination.
- Provides status updates (`pending` or `completed`) for each transport request.
- Generates reports based on transport data over a given date range.

### 2. **Inventory Management**
- Tracks coffins, urns, caskets, chapels, cremators, and meeting rooms.
- Allows real-time updates on stock levels.
- Generates detailed inventory reports for specified date ranges (daily, weekly, monthly).

### 3. **Scheduling and Records**
- Manages scheduling for services like funerals, cremations, and memorials.
- Integrates **Google Maps** for easy location management.
- Ensures compliance with local regulations for scheduling.
- Provides basic reporting functionalities for scheduled events.

### 4. **Financial Management**
- Processes payments, invoices, and receipts for services rendered.
- Calculates totals, taxes, discounts, and fees.
- Generates detailed financial reports based on payment data, invoices, and accounts.
- Allows filtering and report generation over specific date ranges.

### 5. **Client Information**
- Stores and manages basic information about clients (e.g., family representatives) and deceased individuals.
- Records agreements and payment information.
- Tracks client interactions and service requests.
- Generates individual client reports.

### 6. **User Accounts**
- Supports two user roles: **admin** and **staff**.
- Administers user roles, permissions, and access controls.
- Implements secure authentication with password encryption.

---

## Technologies Used

### **Frontend**
- **HTML5**: Structure of the web pages.
- **CSS3**: Styling and layout of the web pages.
- **Bootstrap**: Responsive design and prebuilt UI components.

### **Backend**
- **PHP**: Server-side scripting for handling logic and data.
- **MySQL**: Database for storing and managing system data.

---

## Database Structure

The database consists of the following tables:
1. **users**: Stores user credentials and roles (admin, staff).
2. **clients**: Manages client information (family representatives).
3. **deceased**: Records details about deceased individuals.
4. **logistics**: Tracks transportation and body pickup details.
5. **inventory**: Manages stock levels of coffins, urns, chapels, etc.
6. **schedules**: Handles service scheduling (funerals, cremations).
7. **payments**: Manages payments, invoices, and financial transactions.
8. **client_interactions**: Tracks client interactions and service requests.
9. **reports**: Stores generated reports for logistics, inventory, finance, and clients.

---

## Installation and Setup

### Prerequisites
- A web server like **XAMPP** for running PHP and MySQL.
- A browser to access the frontend interface.
- Basic knowledge of PHP and MySQL.

## Usage
1. **Admin**
    - Admin users have full access to manage logistics, inventory, scheduling, financials, client information, and user accounts.
2. **Staff**
    - Staff users can manage logistics, inventory, scheduling, and client information but have restricted access to certain admin-only features.


