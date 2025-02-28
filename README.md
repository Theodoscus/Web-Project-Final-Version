# 🌍 WebProject – Collaborative Offer Search Platform

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue?style=for-the-badge&logo=php)](https://www.php.net/)  
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-yellow?style=for-the-badge&logo=javascript)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)  
[![MySQL](https://img.shields.io/badge/MySQL-Database-orange?style=for-the-badge&logo=mysql)](https://www.mysql.com/)  

🔍 **A collaborative platform for searching and evaluating supermarket offers. Users can submit, review, and validate offers while interacting in real-time.**  

---

## **📌 Project Overview**
This project was developed as part of an **academic assignment** by a **team of three members**. The main goal was to create a **user-friendly web application** where users could:  
✔️ Discover **real-time supermarket offers**  
✔️ Submit new offers for the community  
✔️ Validate the authenticity of offers (Like, Dislike, Out-of-Stock)  
✔️ Earn reward points based on participation  

The system bridges the gap between **existing price comparison platforms** and **actual product availability** in supermarkets, ensuring a more **transparent shopping experience**.

---

## **🛠️ Tech Stack**
| **Technology** | **Purpose** |
|--------------|-----------|
| **PHP** | Backend logic, form handling, and database interactions |
| **JavaScript (ES6+)** | Frontend interactivity and AJAX requests |
| **MySQL** | Database for storing users, offers, and ratings |
| **HTML5 & CSS3** | UI structure and styling |
| **Leaflet.js** | Interactive maps for locating offers |
| **AJAX** | Asynchronous data fetching for real-time updates |

---

## **🖥️ Features & Functionalities**
### **🔹 User Functionalities**
✅ **User Registration & Login**  
Users can create an account with secure **email & password authentication**.  

✅ **Offer Submission**  
Users can add **new product offers**, specifying **store location, product details, and price**.  

✅ **Map-Based Search**  
A **dynamic map** displays all available offers based on **real-time user submissions**.  

✅ **Offer Evaluation**  
- **Like** 👍: Approve an offer as valid  
- **Dislike** 👎: Indicate an incorrect price or expired offer  
- **Out-of-Stock** ⛔: Mark items that are no longer available  

✅ **Leaderboard & Rewards**  
Users are **rewarded with points** for submitting valid offers. A **leaderboard** ranks contributors based on their activity.

---

## **🎨 System Architecture**
This project follows a **Model-View-Controller (MVC) architecture**, ensuring **scalability and maintainability**.

```
/WebProject
├── /assets        # Static files (CSS, Images, JS)
├── /includes      # PHP utility scripts
├── /database      # SQL database schema & connections
├── /templates     # HTML templates for UI
├── home.php       # Homepage with active offers
├── login.php      # User authentication
├── register.php   # User registration
├── map.php        # Interactive map for offers
└── README.md      # Project documentation
```

---

## **📀 How to Run the Project Locally**
Follow these steps to set up the project on your local environment.

### **1️⃣ Clone the Repository**
```bash
git clone https://github.com/ChrisLoukas007/WebProject.git
cd WebProject
```

### **2️⃣ Set Up the Database**
1. Import the provided `database.sql` file into MySQL.
2. Update `config.php` with your **database credentials**.

### **3️⃣ Start a Local Server**
For PHP development, use:
```bash
php -S localhost:8000
```
Then, open **http://localhost:8000** in your browser.

---

## **🛡️ Security Considerations**
🔐 **Password Encryption**  
All user passwords are **hashed using bcrypt** before storing in the database.  

🛨 **SQL Injection Protection**  
All SQL queries use **prepared statements** to prevent SQL injection attacks.  

---
