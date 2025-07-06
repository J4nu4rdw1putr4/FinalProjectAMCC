nd<h1 align="center">
Baras (Website Jual/Beli barang bekas yang terpercaya(Final Project AMCC))
</h1>

<div align="center">
 <img src="/FrontEnd/Assets/ChatGPT Image Jun 28, 2025, 10_16_08 AM.png" width="200" alt="Logo Baras"/>
</div>

<h1 align="center">
Baras (Website Jual/Beli barang bekas yang terpercaya(Final Project AMCC))
</h1>

<p align="center">
Baras adalah platform web jual beli barang bekas yang dirancang untuk mendorong budaya reuse dan keberlanjutan di Indonesia. Dengan menghubungkan penjual individu, pembeli, dan reseller dalam satu ekosistem digital, Baras mempermudah masyarakat untuk menjual barang bekas layak pakai dan menemukan produk berkualitas dengan harga terjangkau.

Melalui fitur unggulan seperti pengajuan penawaran oleh user/pengguna, unggah foto barang langsung via form digital, serta tampilan yang sederhana dan cepat, Baras membantu mempercepat transaksi barang bekas secara aman dan transparan. Tujuan kami adalah menciptakan ekosistem ekonomi sirkular yang kuat dan memberdayakan masyarakat untuk lebih bijak dalam konsumsi dan distribusi barang.
</p>

<p align="center">
    <a href="#">
      <img src="https://img.shields.io/badge/status-prototipe-yellow" alt="Status Proyek">
    </a>
    <a href="#">
      <img src="https://img.shields.io/badge/license-MIT-blue" alt="Lisensi">
    </a>
</p>

---

## 🌐 Akses Demo & Akun Pengujian

Untuk mempermudah juri/penguji, kami telah menyediakan prototipe yang dapat diakses secara online serta akun demo.

- **Link Demo**: -
- **Link Postman / Dokumentasi API** : https://github.com/J4nu4rdw1putr4/FinalProjectAMCC/blob/810c6c13637a65cc116f78ef7125f63ba2654589/BackEnd/backend-api-main/README.md
- **Email**: `admin@gmail.com`
- **Password**: `admin12345`

---

## ✨ Tampilan Web

<p align="center">
  <img src="/FrontEnd/Assets/BarasHomePage.png" width="200" alt="Halaman Home Reseller">
  <img src="/FrontEnd/Assets/BarasTokoPage.png" width="200" alt="Halaman Toko Reseller">
</p>

---

## 📝 Status Prototipe

Repositori ini berisi prototipe untuk proyek final. Tidak semua fitur yang tercantum dalam deskripsi telah diimplementasikan sepenuhnya. Fokus kami untuk fase ini adalah menghadirkan fungsionalitas inti yang solid.

**Fitur yang Sudah Diimplementasikan:**
- [x] fitur negosiasi (Make an Offer) untuk user
- [ ] User dapat menjadi Reseller ataupun Consumer User
- [ ] Manajemen list barang
- [ ] Menambahkan/upload produk di website
- [ ] Mengedit kartu produk
- [ ] Checkout barang untuk User
- [ ] List pembayaran (Qris, Rek.Bank, dan COD)
---

## 🌟 Fitur Aplikasi

Berikut adalah fitur-fitur yang kami rencanakan untuk Baras:

### 1. Negosiasi (Make an Offer)
- **Negosiasi**:Untuk memudahkan user menemukan barang yang diimpikannya dengan harga yang terjangkau.

### 2. Manajemen list barang bagi para Reseller
- **list manajemen**: Untuk memudahkan para reseller/penjual di website kami dapat memanajemen barang-barang yang banyak

### 3. Reseller
- **Menjadi Reseller**: User dapat menjadi Reseller di website kami ataupun menjadi Consumer User

### 4. Add Product & Edit Product
- **Menambah barang**: Reseller dapat menambahkan barang dan juga mengedit deskripsi produk.

### 5. Checkout
- **Checkout barang**: User dapat memulai pembayaran suatu produk yang diinginkan

### 6. Pembayaran
- **List pembayaran**: User dapat melakukan pembayaran dengan cara Qris, melalui Rekening Bank, atau COD (Cash on Delivery).

---

## 🛠️ Tech Stack
- **Frontend**: `HTML, Tailwind CSS, Javascript`
- **Backend**: `PHP, Laravel`
- **Database**: `mySQL`
- **AI**: `Gemini, Chat GPT`

---

## 🚀 Cara Menjalankan Proyek Secara Lokal

### Pre-requisite
- Node.js (v18++)
- npm atau yarn
- Php
- composer
- Git

### Backend (PHP Laravel)

1.  **Clone repositori ini:**
    ```bash
    git clone https://github.com/J4nu4rdw1putr4/FinalProjectAMCC 
    ```

2.  **Buat Folder baru untuk menempatkan cloningan folder GitHUb tersebut:**
    ```bash
    cd BackEnd
    ```

3.  **Install dependency via Composer:**
    ```bash
    composer install
    ```

4.  **Salin file environment:**
    ```bash
    cp .env.example .env
    ```

5.  **Generate application key:**
    ```bash
    php artisan key:generate
    ```

6.  **Konfigurasi database di file `.env` kamu.**

7.  **Jalankan migrasi database:**
    ```bash
    php artisan migrate
    ```

8.  **Jalankan server lokal:**
    ```bash
    php artisan serve
    ```
    Backend akan berjalan di `http://localhost:8000`.

---

### Frontend (Tailwind CLI)

1.  **Pindah ke direktori frontend (jika terpisah):**
    ```bash
    cd .../FrontEnd
    ```

2.  **Install dependency Node.js:**
    ```bash
    npm install
    ```

3.  **Jalankan Tailwind CLI untuk memantau dan build CSS:**
    ```bash
    npm run dev
    ```
    Pastikan file HTML kamu sudah terhubung dengan file `output.css` yang dihasilkan oleh Tailwind.

---

## 👨‍💻 Tim Kami
- Januar Dwiputra Warsito
- Mikael Mario Senopati
- Ridho Novanny Pratama
- Kunniko Ahmad Zidan M.B

---
