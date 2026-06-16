# Project Plan: ARC NEBU-PEN (Breathing Wellness Subscription Platform)

## Stack & Architecture
- Backend: PHP (OOP / MVC Structure)
- Database: MySQL
- Frontend: Vanilla HTML5, Tailwind CSS, Vanilla JavaScript
- Mobile: Flutter (with native Camera API integration)

## MVC Directory Structure
breathflow/
├── controllers/
│   ├── AuthController.php
│   ├── ProductController.php
│   ├── BundleController.php
│   └── SubscriptionController.php
├── models/
│   ├── User.php
│   ├── Product.php
│   ├── Bundle.php
│   └── Subscription.php
├── views/
│   ├── home.php
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│   ├── products.php
│   ├── science.php
│   ├── profiles.php
│   ├── bundle_builder.php
│   └── subscription.php
├── config/
│   └── database.php
└── index.php

## Database Schema
- users (user_id, fullname, email, password, role, created_at)
- products (product_id, name, description, price, image, stock)
- bundles (bundle_id, user_id, flavor1, flavor2, flavor3, total_price)
- subscriptions (subscription_id, user_id, plan, status, discount)

## Web Pages & Features
1. Home Page: Dark theme navbar, Teal color accents, Hero background video, Product introduction, and CTA.
2. Science Section: Information on Pursed Lip Breathing and the Vagus Nerve. Simple body diagram graphic (no animation).
3. Unheated Advantage: Comparison table between NEBU-PEN (Cool Air, Comfortable) vs. Heated Vapor (Hot Vapor, Irritating).
4. Sensory Profiles: Three product cards (Mint, Berry, Citrus) featuring images, descriptions, and benefits.
5. Bundle Builder: Dropdown selectors for Flavor 1, 2, and 3. JavaScript calculates the dynamic total price in RM (Base bundle: RM57.00). No drag-and-drop.
6. Subscription Page: Core Club info (20% discount, Monthly delivery). Saves data to database on submit. No payment gateway integration needed.
7. Auth Pages: Login and Registration views handling session state.
8. User Dashboard: Displays user profile, current saved bundle, and subscription status.
9. Admin Dashboard: Basic CRUD grids to manage Products (Add/View/Edit/Delete) and manage User Subscriptions.

## Flutter Mobile App
- Splash Screen (Logo)
- Login Screen (API auth to PHP backend)
- Home Screen (Product List via API)
- Bundle Builder Screen (Dropdown flavor selector with total calculation)
- Profile Screen (User info)
- Camera Screen (Utilizes native camera package to take a photo of the device)