# Panduan Deployment: Render (Web) + Neon (Database) - PERCUMA

Panduan ini adalah cara **paling pantas dan percuma** untuk deploy projek Laravel anda untuk tujuan presentation. Kita akan gunakan **Render.com** untuk hosting web dan **Neon.tech** untuk database.

---

## Langkah 1: Tambah Fail Docker & Push Kod ke GitHub

Oleh kerana Render mungkin tak detect PHP secara automatik, kita dah tambah fail `Dockerfile` untuk paksa dia guna setting yang betul.

1.  Buka terminal di VS Code.
2.  Jalankan arahan berikut satu persatu untuk update GitHub awak dengan fail baru:
    ```bash
    git add .
    git commit -m "Tambah Dockerfile untuk deployment Render"
    git push origin main
    ```

---

## Langkah 2: Setup Database (Neon.tech)

Render ada database percuma tapi ia akan **padam selepas 90 hari**. Neon.tech adalah pilihan percuma yang lebih baik dan stabil.

1.  Pergi ke [Neon.tech](https://neon.tech) dan Sign Up.
2.  Cipta projek baru.
3.  Pastikan pilih Region **Singapore**.
4.  Copy **Connection String** (pilih yang `postgres://...` atau `postgresql://...`).

---

## Langkah 3: Setup Web Service (Render.com)

1.  Pergi ke [Render.com](https://render.com) dan Sign Up/Login.
2.  Klik butang **New +** dan pilih **Web Service**.
3.  Pilih repository `smartmed3`.
4.  **PENTING**:
    *   Sekarang Render sepatutnya akan detect **Docker** sebagai Environment (sebab kita dah ada Dockerfile).
    *   Jika dia tanya "Runtime", pilih **Docker**.
    *   **Region**: Singapore.
    *   **Branch**: `main`.

5.  **Environment Variables** (Sangat Penting!):
    Klik "Advanced" atau scroll ke bawah ke bahagian "Environment Variables". Tambah variable berikut:

    | Key | Value |
    | :--- | :--- |
    | `APP_NAME` | `SmartMed` |
    | `APP_ENV` | `production` |
    | `APP_KEY` | (Salin dari file .env tempatan anda, mula dengan `base64:...`) |
    | `APP_DEBUG` | `true` |
    | `APP_URL` | (Biarkan kosong dulu, nanti update lepas dapat link Render) |
    | `DB_CONNECTION` | `pgsql` |
    | `DATABASE_URL` | (Paste Connection String dari Neon.tech tadi di sini) |

6.  Klik **Create Web Service**.

---

## Langkah 4: Tunggu Deployment

1.  Render akan mula proses deployment. Anda boleh tengok log.
2.  Oleh kerana kita guna Docker, proses setup database (migration) akan jalan secara **automatik** bila server start (saya dah set dalam Dockerfile).
3.  Tunggu sehingga status jadi hijau ("Live").

---

## Langkah 5: Selesai!

1.  Dapatkan link website anda di dashboard Render.
2.  Buka link tersebut. Website anda sepatutnya sudah live!

Selamat maju jaya untuk presentation esok! Anda pasti boleh buat! 💪
