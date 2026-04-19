KPOP STORE API (UTP TIS)

Project ini merupakan API sederhana berbasis Laravel yang dibuat untuk memenuhi tugas UTP Teknologi Integrasi Sistem. API ini mensimulasikan backend e-commerce sederhana dengan menggunakan data JSON (tanpa database).

DESKRIPSI
API ini digunakan untuk mengelola data produk K-Pop seperti album, lightstick, dan merchandise lainnya. Fitur utama yang tersedia adalah CRUD (Create, Read, Update, Delete).
Data disimpan dalam file JSON sehingga tidak menggunakan database.

BASE URL
http://127.0.0.1:8000/api

ENDPOINT API
1. Menampilkan semua item
GET /items
Digunakan untuk mengambil seluruh data barang.

2. Menampilkan item berdasarkan ID
GET /items/{id}
Contoh:
GET /items/1

3. Menambahkan item
POST /items
Body (JSON):
{
  "name": "TXT Album - Freefall",
  "price": 300000
}

4. Mengupdate seluruh data item
PUT /items/{id}
Digunakan untuk mengganti seluruh data item berdasarkan ID.

5. Mengupdate sebagian data item
PATCH /items/{id}
Contoh:
{
  "price": 750000
}

6. Menghapus item
DELETE /items/{id}


VALIDASI DAN ERROR HANDLING
API ini sudah menerapkan validasi input, misalnya:
- name harus diisi
- price harus berupa angka
Jika item dengan ID tertentu tidak ditemukan, maka akan muncul response seperti berikut:
{
  "message": "Item dengan ID tidak ditemukan"
}

TEKNOLOGI YANG DIGUNAKAN
- Laravel
- JSON sebagai penyimpanan data (tanpa database)

PENUTUP
API ini dibuat sebagai latihan implementasi konsep REST API sederhana menggunakan Laravel. Semua endpoint sudah diuji menggunakan Postman dan berjalan dengan baik.