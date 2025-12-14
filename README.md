# INVW – Inventory & Warehouse Management System

INVW adalah aplikasi **Inventory & Warehouse Management System** berbasis web yang dibangun menggunakan **CodeIgniter 4**. Sistem ini dirancang untuk membantu pengelolaan stok barang, pergerakan inventori, dan aktivitas gudang secara **efisien, terstruktur, dan terkontrol**.

Project ini dikembangkan sebagai **real project / academic project** dengan fokus pada penerapan best practice backend, manajemen data inventori, serta integrasi frontend modern.

---

## 🚀 Fitur Utama

* 📦 Manajemen Data Barang (CRUD)
* 🏬 Manajemen Gudang & Lokasi Penyimpanan
* 🔄 Pencatatan Barang Masuk & Barang Keluar
* 📊 Monitoring Stok Real-time
* 👥 Manajemen User & Role (Admin / Staff)
* 🧾 Riwayat Transaksi Inventori
* 🔍 Pencarian & Filter Data
* 📈 Tabel Interaktif menggunakan DataTables

---

## 🛠️ Teknologi yang Digunakan

### Backend

* **PHP 8+**
* **CodeIgniter 4**
* **MySQL / MariaDB**

### Frontend

* **Tailwind CSS**
* **DaisyUI**
* **DataTables (via npm)**
* **JavaScript**

### Tools & Dependency Management

* **Composer** (PHP dependencies)
* **npm** (Frontend dependencies)
* **Git** (Version control)

---

## 📁 Struktur Project (Ringkas)

```
app/            # Logic aplikasi (Controller, Model, View)
public/         # Public assets (index.php, CSS, JS)
writable/       # Cache, logs, session, uploads
vendor/         # PHP dependencies (Composer)
resources/      # Asset source (Tailwind input, JS)
```

---

## ⚙️ Instalasi & Setup (Development)

### 1️⃣ Clone Repository

```bash
git clone https://github.com/username/invw.git
cd invw
```

### 2️⃣ Install Dependency Backend

```bash
composer install
```

### 3️⃣ Install Dependency Frontend

```bash
npm install
```

### 4️⃣ Build Asset Frontend (Tailwind)

```bash
npm run build
```

### 5️⃣ Konfigurasi Environment

Salin file `.env.example` menjadi `.env` lalu sesuaikan konfigurasi database:

```env
app.baseURL = 'http://localhost:8080'
database.default.hostname = localhost
database.default.database = invw
database.default.username = root
database.default.password =
```

### 6️⃣ Jalankan Server Development

```bash
php spark serve
```

Akses aplikasi di:

```
http://localhost:8080
```

---

## 🧪 Akun Default (Opsional)

| Role  | Username | Password |
| ----- | -------- | -------- |
| Admin | admin    | admin123 |

> *Catatan: Ubah password default setelah login pertama.*

---

## 📦 Deployment (Shared Hosting)

* Build asset frontend di lokal (`npm run build`)
* Pastikan folder `vendor/` dan file hasil build (`public/css/output.css`) tersedia
* Upload project ke hosting
* Set **document root** ke folder `/public`
* Buat file `.env` langsung di server
* Pastikan folder `writable/` memiliki permission write

---

## 🔐 Keamanan

* File `.env` **tidak disertakan** dalam repository
* Validasi input dilakukan di sisi server
* Manajemen akses berdasarkan role user

---

## 👨‍💻 Tim Pengembang

Project ini dikembangkan oleh tim sebagai bagian dari:

* Tugas perkuliahan
* Project pembelajaran
* Pengembangan sistem inventory berbasis web

---

## 📄 Lisensi

Project ini dikembangkan untuk tujuan **edukasi dan pengembangan internal**.
Silakan digunakan dan dimodifikasi sesuai kebutuhan.

---

## ✨ Catatan

Jika Anda ingin mengembangkan project ini lebih lanjut (fitur laporan, barcode, export data, dll), silakan lakukan fork dan pull request.

---

**INVW – Inventory & Warehouse Management System**
