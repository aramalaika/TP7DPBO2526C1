# TP7DPBO2526C1
Janji : Saya Aracelli Jasmine Malaika dengan NIM 2501804 mengerjakan Tugas Praktikum 7 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahan-Nya, maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

# 1. Deskripsi Project
Meloire merupakan website berbasis PHP Native yang menggabungkan musik dan pencatatan suasana hati dalam satu platform.

Website ini membantu pengguna menemukan dan mengelola lagu berdasarkan mood, menyimpan cerita melalui diary digital, serta membuat playlist pribadi.

# 2. Teknologi yang Digunakan
- PHP Native
- MySQL
- HTML
- CSS
- PDO (PHP Data Objects)
- Laragon
- Visual Studio Code

# 3. Database
Nama database: meloire_db

## Database Meloire memiliki lima tabel utama:
- Tabel
- Fungsi
- moods : Menyimpan kategori mood pengguna
- songs : Menyimpan informasi lagu
- journals : Menyimpan diary dan cerita pengguna
- playlists : Menyimpan playlist pribadi
- playlist_songs : Menghubungkan lagu dengan playlist

## Relasi Antar Tabel : 
- songs.id_mood berelasi dengan moods.id_mood.
- journals.id_mood berelasi dengan moods.id_mood.
- playlist_songs.id_playlist berelasi dengan playlists.id_playlist.
- playlist_songs.id_song berelasi dengan songs.id_song.
- Relasi tersebut menggunakan Foreign Key untuk menjaga keterhubungan data.

# 4. Fitur Website
## A. Mood Management
Menambahkan mood.

Menampilkan daftar mood.

Mengedit mood.

Menghapus mood.

## B. Song Management
Menambahkan lagu.

Menampilkan daftar lagu.

Mengedit informasi lagu.

Menghapus lagu.

Menghubungkan lagu dengan mood.

## C. Journal Management
Menambahkan diary.

Menampilkan diary.

Mengedit diary.

Menghapus diary.

Menghubungkan diary dengan mood.

## D. Playlist Management
Membuat playlist.

Menampilkan playlist.

Mengedit playlist.

Menghapus playlist.

Menambahkan lagu ke playlist.

Menghapus lagu dari playlist.

# 5. Alur Program
Pengguna membuka website Meloire melalui browser.

Sistem melakukan koneksi ke database MySQL menggunakan class Database.

Pengguna dapat mengakses halaman Discover, My Diary, Playlists, dan Moods.

Setiap halaman menggunakan class masing-masing untuk mengelola data.

Pengguna dapat melakukan operasi Create, Read, Update, dan Delete (CRUD).

Data diproses menggunakan Prepared Statements melalui PDO.

Perubahan data disimpan ke database MySQL dan ditampilkan kembali pada halaman website.

# 6. Struktur Folder
meloire/
│
├── config/
│   └── database.php
│
├── classes/
│   ├── Mood.php
│   ├── Song.php
│   ├── Journal.php
│   └── Playlist.php
│
├── pages/
│   ├── moods.php
│   ├── songs.php
│   ├── journals.php
│   └── playlists.php
│
├── assets/
│   └── style.css
│
├── index.php
├── meloire_db.sql
└── README.md

# 7. Dokumentasi Program
## A. Halaman Utama
[Masukkan screenshot halaman Home]

## B. CRUD Mood
[Masukkan screenshot Create dan Read Mood]

[Masukkan screenshot Update dan Delete Mood]

## C. CRUD Songs
[Masukkan screenshot Create dan Read Songs]

[Masukkan screenshot Update dan Delete Songs]

## D. CRUD Journals
[Masukkan screenshot Create dan Read Journals]

[Masukkan screenshot Update dan Delete Journals]

## E. CRUD Playlists
[Masukkan screenshot Create dan Read Playlists]

[Masukkan screenshot Update dan Delete Playlists]

[Masukkan screenshot Add Song dan Remove Song]

## F. Database
[Masukkan screenshot struktur tabel dan relasi database di phpMyAdmin]

## G. Screen Recording

[Masukkan link video demonstrasi program dan seluruh fitur CRUD]
