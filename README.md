# Learning & Development

### Login Admin

```bash
USERNAME : admin_dlc
PASSWORD : dlc2026
```

### Langkah-Langkah Membuat Sandi Aplikasi (App Password)

1. Masuk ke Menu Sandi Aplikasi

Ketik Sandi Aplikasi di kolom pencarian paling atas (Telusuri Akun Google), lalu klik hasil yang muncul.

Atau secara manual: Gulir ke bawah pada bagian Cara Anda login ke Google > klik Verifikasi 2 Langkah (masukkan sandi akun jika diminta) > gulir ke bagian paling bawah dan pilih Sandi Aplikasi.

2. Buat Sandi Aplikasi Baru

Masukkan nama aplikasi pada kolom yang tersedia, misalnya: Laravel SMTP atau Web App.

Klik tombol Buat (Create).

3. Salin Kode Sandi

Jendela pop-up akan menampilkan 16 karakter huruf (biasanya dipisah dengan spasi, contoh: abcd efgh ijkl mnop).

Salin seluruh kode 16 digit tersebut. Tanda spasi boleh diabaikan atau dihapus.

Klik Selesai. Kode ini tidak dapat dilihat lagi setelah jendela ditutup.

4. Pasang di File Konfigurasi (.env)
Tempelkan kode tersebut pada variabel konfigurasi email aplikasi:

Contoh:
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=luth88049@gmail.com
MAIL_PASSWORD="gpaadwgjhufozngf"   # App Password dari Google Account
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="luth88049@gmail.com"
MAIL_FROM_NAME="Dharma Learning Center"
````
