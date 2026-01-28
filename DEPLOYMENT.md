# Panduan Deployment: Render (Web) + Neon (Database) - PERCUMA

Panduan ini adalah cara **paling pantas dan percuma** untuk deploy projek Laravel anda untuk tujuan presentation. Kita akan gunakan **Render.com** untuk hosting web dan **Neon.tech** untuk database.

---

## Langkah 1: Push Kod ke GitHub

Render memerlukan kod anda berada di GitHub.

1.  Buka terminal di VS Code.
2.  Jalankan arahan berikut satu persatu:
    ```bash
    git add .
    git commit -m "Siap untuk deployment"
    ```
3.  Pergi ke [GitHub.com](https://github.com) dan cipta **New Repository** (namakan `smartmed3`).
4.  Ikut arahan GitHub untuk push kod anda (biasanya seperti ini):
    ```bash
    git remote add origin https://github.com/USERNAME_ANDA/smartmed3.git
    git branch -M main
    git push -u origin main
    ```

---

## Langkah 2: Setup Database (Neon.tech)

Render ada database percuma tapi ia akan **padam selepas 90 hari**. Neon.tech adalah pilihan percuma yang lebih baik dan stabil.

1.  Pergi ke [Neon.tech](https://neon.tech) dan Sign Up.
2.  Cipta projek baru.
3.  Anda akan dapat **Connection String** (contoh: `postgres://user:pass@ep-xyz.aws.neon.tech/neondb...`).
4.  **Salin** connection string ini. Kita akan gunakannya nanti.

---

## Langkah 3: Setup Web Service (Render.com)

1.  Pergi ke [Render.com](https://render.com) dan Sign Up/Login (boleh guna akaun GitHub).
2.  Klik butang **New +** dan pilih **Web Service**.
3.  Sambungkan akaun GitHub anda dan pilih repository `smartmed3` yang anda baru push tadi.
4.  Isi maklumat berikut:
    *   **Name**: `smartmed-app` (atau apa-apa nama unik).
    *   **Region**: Singapore (paling dekat dengan Malaysia) atau mana-mana yang available.
    *   **Branch**: `main`.
    *   **Root Directory**: (biarkan kosong).
    *   **Runtime**: `PHP`.
    *   **Build Command**: `composer install --no-dev --optimize-autoloader && npm install && npm run build`
    *   **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`

5.  **Environment Variables** (Sangat Penting!):
    Klik "Advanced" atau scroll ke bawah ke bahagian "Environment Variables". Tambah variable berikut:

    | Key | Value |
    | :--- | :--- |
    | `APP_NAME` | `SmartMed` |
    | `APP_ENV` | `production` |
    | `APP_KEY` | (Salin dari file .env tempatan anda, mula dengan `base64:...`) |
    | `APP_DEBUG` | `true` (Set `true` sementara untuk nampak error jika ada) |
    | `APP_URL` | (Biarkan kosong dulu, nanti update lepas dapat link Render) |
    | `DB_CONNECTION` | `pgsql` (**PENTING**: Tukar ke `pgsql` sebab Neon guna PostgreSQL) |
    | `DATABASE_URL` | (Paste Connection String dari Neon.tech tadi di sini) |

    *Nota: Bila guna `DATABASE_URL`, Laravel akan automatik detect DB_HOST, DB_USER, dll.*

6.  Klik **Create Web Service**.

---

## Langkah 4: Tunggu Deployment & Migration

1.  Render akan mula proses deployment. Anda boleh tengok log di tab "Logs".
2.  Tunggu sehingga nampak mesej "Your service is live" atau status hijau.
3.  **Run Migration (Untuk create table database):**
    *   Di dashboard Render projek anda, cari butang **Shell** atau **Connect** (di menu kiri atau atas).
    *   Ini akan buka terminal server Render.
    *   Taip arahan ini dan tekan Enter:
        ```bash
        php artisan migrate --force
        ```
    *   Kalau berjaya, database anda sudah siap!

---

## Langkah 5: Selesai!

1.  Dapatkan link website anda di bahagian atas dashboard Render (contoh: `https://smartmed-app.onrender.com`).
2.  Buka link tersebut. Website anda sepatutnya sudah live!
3.  **Tips Presentation**:
    *   Pastikan login admin dan doktor berfungsi.
    *   Kalau ada error, semak tab **Logs** di Render.

Selamat maju jaya untuk presentation esok! Anda pasti boleh buat! 💪
