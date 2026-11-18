# Secure PHP Authentication System

A clean, modern, and secure user login and registration system built from scratch using PHP, MySQL, and vanilla CSS. Designed with a focus on web security best practices and a smooth user experience.

## ✨ Features
- **Secure Authentication:** Uses PHP's native `password_hash()` and `password_verify()` for industry-standard password security.
- **SQL Injection Prevention:** Fully implements MySQL prepared statements (`$conn->prepare()`) to protect database queries.
- **Session Management:** Securely handles user session handling and redirection upon successful login.
- **Modern UI/UX:** Features a sleek, centered card design, responsive layout, and smooth focus states built with modern CSS variables.
- **Client-side Validation:** Built-in JavaScript checks for strong password requirements (uppercase, lowercase, number, special character) and password matching before submission.