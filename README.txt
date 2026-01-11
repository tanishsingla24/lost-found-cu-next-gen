# 📘 README.txt

### Project: Lost and Found for Chandigarh University (NextGen)

---

## 🧩 Overview

**Lost and Found for Chandigarh University (NextGen)** is a secure, multi-page web application built using **PHP**, **MySQL**, **HTML**, **CSS**, and a touch of **AJAX**.
It allows CU students to:

* Report **lost or found items** on campus
* View all listed items
* Mark their own item as **returned** after recovery
* Mark someone else’s lost item as **found** when they locate it
* Prevent duplicate postings using **photo hash & title-based duplicate checks**
* Manage their own posts in a personalized **profile dashboard**

---

## 🧱 Features

✅ **User Registration & Login** (UID-based authentication)
✅ **Dashboard** with navigation to all modules
✅ **Report Lost/Found Items** (with photo upload & duplicate detection)
✅ **View & Search Listings**
✅ **Mark Found / Mark Returned** actions via AJAX
✅ **Profile Page** (user’s own posts)
✅ **Session Security** (login required to access pages)
✅ **Responsive Design** (works on mobile & desktop)
✅ **Clean UI with Minimal CSS**
✅ **Ready for GitHub deployment**

---

## ⚙️ Technologies Used

| Category        | Tools/Frameworks |
| --------------- | ---------------- |
| Backend         | PHP (Procedural) |
| Database        | MySQL (via PDO)  |
| Frontend        | HTML5, CSS3      |
| Enhancements    | JavaScript, AJAX |
| Local Server    | XAMPP / LAMP     |
| Version Control | Git & GitHub     |

---

## 🗂️ Project Structure

```
lost-found-cu-nextgen/
│
├── assets/
│   ├── css.css          # All styles
│   └── js.js            # JS (future extensions)
│
├── uploads/             # User-uploaded item images (keep writable)
│
├── db.php               # Database connection (PDO)
├── functions.php        # Helper functions (login check, duplicate detection)
│
├── register.php         # New student registration
├── login.php            # Login form
├── logout.php           # Logout script
│
├── dashboard.php        # Home page after login
├── report_item.php      # Page to report lost/found item
├── list_items.php       # Page to browse and search items
├── mark_found.php       # AJAX endpoint for marking found/returned
├── profile.php          # Shows logged-in user's own posts
│
├── init.sql             # Database schema for quick setup
├── .htaccess            # Optional Apache security config
└── README.txt           # This documentation
```

---

## 🧰 Installation Guide (XAMPP)

1. **Extract the folder**
   Unzip into your XAMPP `htdocs` directory.
   Example path:
   `C:\xampp\htdocs\lost-found-cu-nextgen`

2. **Create the database**

   * Open [phpMyAdmin](http://localhost/phpmyadmin)
   * Click **Import**
   * Select the file `init.sql` from the project folder
   * This will create a new DB named **`lost_found_cu_nextgen`**

3. **Configure database connection**

   * Open `db.php`
   * Make sure the following credentials match your local MySQL:

     ```php
     $user = 'root';
     $pass = '';  // change if your MySQL has a password
     ```

4. **Set folder permissions**

   * Ensure `/uploads` is writable by PHP.
     On Windows (XAMPP) this usually works automatically.
     On Linux/Mac, you may need:

     ```bash
     chmod 777 uploads
     ```

5. **Start Apache & MySQL** in XAMPP Control Panel.

6. **Access the project**
   Open your browser and go to:
   👉 [http://localhost/lost-found-cu-nextgen/register.php](http://localhost/lost-found-cu-nextgen/register.php)

7. **Register a test student**, then log in via
   👉 [http://localhost/lost-found-cu-nextgen/login.php](http://localhost/lost-found-cu-nextgen/login.php)

---

## 🔒 Default Database Name

* **Database:** `lost_found_cu_nextgen`
  (This name avoids overwriting any previous `lost_and_found` DB.)

---

## 💡 Tips

* Use meaningful **titles** & **locations** when reporting to improve duplicate detection.
* You can search items by keywords or filter by type (Lost/Found/Returned).
* Only the **original reporter** can mark their item as returned.
* The **status** automatically updates in real-time through AJAX.

---

## 🧑‍💻 Future Enhancements (Suggestions)

* Admin dashboard (approve/delete posts)
* Notification system for item matches
* Email verification
* QR code for item claiming
* Advanced fuzzy duplicate detection (full-text/trigram)
* REST API endpoints for a mobile app version

---

## 🧾 License

This project is open for educational use by **Chandigarh University** students and faculty.
You may modify and redistribute it freely with proper attribution.

---

## ✍️ Author

**Developed by:** Tanish Singla
**University:** Chandigarh University
**Project Title:** Lost and Found for Chandigarh University (NextGen)
**Language:** PHP + MySQL + HTML/CSS + JS
**Version:** 1.0 (November 2025)
