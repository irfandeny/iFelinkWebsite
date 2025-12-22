# 📦 iFelink Package API - Implementation Guide

## ✅ Implementasi Selesai!

### 🎯 Yang Sudah Diimplementasikan:

#### 1. **Authentication Endpoints** ✅

-   ✅ `POST /api/auth/register` - Register user baru
-   ✅ `POST /api/auth/login` - Login & dapatkan Bearer Token
-   ✅ `POST /api/auth/logout` - Logout (destroy token)
-   ✅ `GET /api/auth/me` - Get user profile (auth required)

#### 2. **Package Endpoints** ✅

| Endpoint   | Method                  | Middleware      | File Upload | Status |
| ---------- | ----------------------- | --------------- | ----------- | ------ |
| Get All    | GET `/packages`         | ❌ Public       | -           | ✅     |
| Get Detail | GET `/packages/{id}`    | ❌ Public       | -           | ✅     |
| Create     | POST `/packages`        | ✅ auth:sanctum | ✅ Yes      | ✅     |
| Update     | PUT `/packages/{id}`    | ✅ auth:sanctum | ✅ Yes      | ✅     |
| Delete     | DELETE `/packages/{id}` | ✅ auth:sanctum | -           | ✅     |

#### 3. **File Storage** ✅

-   ✅ Kolom `image_path` ditambahkan ke table packages
-   ✅ Validasi file: max 5MB, types: jpg, jpeg, png, pdf, doc, docx
-   ✅ Storage path: `storage/app/public/packages/`
-   ✅ Symbolic link: `public/storage -> storage/app/public`
-   ✅ Auto delete file saat update/delete package

---

## 🚀 Cara Menjalankan

### 1. **Setup Database**

```bash
# Start MySQL di Laragon

# Jalankan migration
php artisan migrate
```

### 2. **Jalankan Server**

```bash
php artisan serve
# Server akan jalan di: http://localhost:8000
```

---

## 📝 Testing dengan Postman

### Import Collection

1. Buka Postman
2. Import file: `iFelink_Package_API_Updated.postman_collection.json`
3. Collection sudah terorganisir dalam 3 folder:
    - **Authentication** (Register, Login, Logout, Me)
    - **Packages - Public** (Get All, Get Detail)
    - **Packages - Protected** (Create, Update, Delete)

### Flow Testing

1. **Register/Login User**

    - Jalankan request "Register User" atau "Login User"
    - Token akan otomatis tersimpan di variable `{{auth_token}}`

2. **Test Public Endpoints** (Tanpa Auth)

    - Get All Packages
    - Get Package Detail

3. **Test Protected Endpoints** (Dengan Auth)
    - Create Package (No File) - JSON body
    - Create Package (With File) - Form-data dengan file upload
    - Update Package (No File) - JSON body
    - Update Package (With File) - Form-data dengan file upload
    - Delete Package

---

## 📝 Testing dengan API_Testing.http

File: `API_Testing.http`

### Cara Menggunakan:

1. Buka file di VS Code
2. Install extension: "REST Client"
3. Jalankan request secara berurutan:
    - Register → Login → Copy token
    - Ganti `@token = YOUR_TOKEN_HERE` dengan token yang didapat
    - Test semua endpoint

**Note:** File upload tidak support di REST Client, gunakan Postman untuk testing file upload.

---

## 🔐 Authentication Flow

```
1. Register/Login
   POST /api/auth/register atau /api/auth/login
   Response: { "data": { "access_token": "..." } }

2. Simpan Token
   Copy token dari response

3. Gunakan Token di Protected Endpoints
   Header: Authorization: Bearer {token}
```

---

## 📤 File Upload

### Create Package dengan File:

```
POST /api/packages
Authorization: Bearer {token}
Content-Type: multipart/form-data

Form Data:
- name: Paket Internet 30GB
- provider: Telkomsel
- quota: 30GB
- price: 150000
- validity_days: 30
- description: Paket dengan gambar
- is_active: true
- image: [file] (max 5MB)
```

### Update Package dengan File:

```
POST /api/packages/{id}  (bukan PUT!)
Authorization: Bearer {token}
Content-Type: multipart/form-data

Form Data:
- _method: PUT  (method spoofing)
- name: Paket Updated
- price: 155000
- image: [file baru]
```

**Important:**

-   Untuk update dengan file, gunakan `POST` dengan `_method=PUT`
-   File akan menggantikan file lama (auto delete)

---

## 📁 File yang Diubah/Dibuat

### Controllers

-   ✅ `app/Http/Controllers/Api/AuthController.php` (BARU)
-   ✅ `app/Http/Controllers/Api/PackageController.php` (MODIFIED)

### Models

-   ✅ `app/Models/User.php` (MODIFIED - tambah HasApiTokens)
-   ✅ `app/Models/Package.php` (MODIFIED - tambah image_path)

### Migrations

-   ✅ `database/migrations/2025_12_15_065119_add_image_path_to_packages_table.php` (BARU)

### Routes

-   ✅ `routes/api.php` (MODIFIED - tambah auth routes & middleware)

### Testing Files

-   ✅ `API_Testing.http` (MODIFIED - tambah auth endpoints)
-   ✅ `iFelink_Package_API_Updated.postman_collection.json` (BARU)

---

## ⚠️ Important Notes

1. **Database Migration**

    - Pastikan MySQL sudah running di Laragon
    - Jalankan `php artisan migrate` setelah database ready

2. **File Upload Validation**

    - Max size: 5MB (5120 KB)
    - Allowed types: jpg, jpeg, png, pdf, doc, docx
    - File disimpan di: `storage/app/public/packages/`

3. **Public vs Protected Routes**

    - Public (tanpa auth): Get All, Get Detail
    - Protected (butuh auth): Create, Update, Delete

4. **Token Management**

    - Token disimpan otomatis di Postman setelah login/register
    - Token berlaku sampai logout
    - Satu user bisa punya multiple tokens

5. **File Update**
    - Saat update package dengan file baru, file lama otomatis dihapus
    - Saat delete package, file juga otomatis dihapus

---

## 🎉 Selesai!

Semua requirement sudah diimplementasikan:

-   ✅ Authentication (Register, Login, Logout, Me)
-   ✅ Middleware auth di Create, Update, Delete
-   ✅ Public routes di Get All & Get Detail
-   ✅ File storage dengan validasi < 5MB
-   ✅ Postman collection & API_Testing.http updated

**Happy Testing! 🚀**
