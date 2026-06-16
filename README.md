# ARC NEBU-PEN Platform

Welcome to the ARC NEBU-PEN platform repository. This project contains the complete end-to-end stack for our premium wellness device, featuring a clean PHP MVC Web Application and a native Flutter Mobile Companion App.

---

## 🗄️ 1. Database Setup

The platform uses a MySQL database to securely handle user authentication, product catalogs, and custom subscription bundles.

1. **Start your local server environment** (e.g., XAMPP, MAMP, or Laragon).
2. **Access phpMyAdmin** (typically at `http://localhost/phpmyadmin`).
3. **Create a new database** named `breathflow`.
4. **Import the schema:**
   - Navigate to the **Import** tab inside phpMyAdmin.
   - Choose the `breathflow/schema.sql` file from this project and click **Go/Import**.
5. **Seed Initial Products:**
   Run the following SQL query in the `SQL` tab to populate the default sensory profiles:
   ```sql
   INSERT INTO products (name, description, price, stock) VALUES
   ('Mint', 'Cooling & Refreshing', 19.00, 100),
   ('Berry', 'Smooth & Soothing', 19.00, 100),
   ('Citrus', 'Bright & Uplifting', 19.00, 100),
   ('Lavender', 'Calming & Relaxing', 19.00, 100);
   ```

---

## 🌐 2. Web Server Configuration

The web platform is built on a custom, lightweight PHP MVC architecture leveraging modern Glassmorphism aesthetics and Tailwind CSS.

1. **Configure Database Credentials:**
   Open `breathflow/config/database.php` and verify that the PDO connection variables match your local environment.
   ```php
   // Default settings for XAMPP
   $host = '127.0.0.1';
   $db   = 'breathflow';
   $user = 'root';
   $pass = ''; // Typically blank on XAMPP by default
   ```
2. **Boot the Local Environment:**
   Navigate to the web directory in your terminal and spin up PHP's built-in development server:
   ```bash
   cd breathflow
   php -S localhost:8000
   ```
3. **View the Platform:**
   Open your browser and navigate to `http://localhost:8000`. You will be greeted by the ARC NEBU-PEN homepage!

---

## 📱 3. Mobile App Setup

The native mobile companion app is built using Flutter and connects securely to the PHP backend REST APIs.

1. **Navigate to the App Directory:**
   ```bash
   cd mobile_app
   ```
2. **Install Flutter Dependencies:**
   Fetch all required packages declared in `pubspec.yaml` (such as `http`, `camera`, and `flutter_secure_storage`):
   ```bash
   flutter pub get
   ```
3. **Launch an Emulator:**
   Ensure you have an Android Emulator or iOS Simulator booted up and ready via Android Studio or Xcode.
4. **Run the Application:**
   Compile and launch the app to your device:
   ```bash
   flutter run
   ```
   
> **Testing Note:** The mobile app's `ApiService` is set to target `http://10.0.2.2/breathflow/api` by default. This specific IP allows the Android Emulator to correctly resolve network requests pointing back to your Windows host machine's XAMPP `localhost` environment.
