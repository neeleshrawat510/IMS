# 📡 API Documentation

## Base URL

```text
http://localhost/Invoice_management_System/api/jwt/
```

All endpoints return responses in **JSON** format.

---

# Authentication

The API uses **JWT (JSON Web Token)** authentication. Include the token in the request header for protected endpoints.

```http
Authorization: Bearer <your_jwt_token>
```

---

# Contacts API

| Endpoint                       | Method | Description                |
| ------------------------------ | :----: | -------------------------- |
| `/add_contacts.php`            |  POST  | Create a new contact       |
| `/edit_contacts.php?id={id}`   |  PATCH | Update an existing contact |
| `/delete_contacts.php?id={id}` | DELETE | Delete a contact           |
| `/view_contacts.php`           |   GET  | Retrieve contacts          |

### Create Contact

**Request**

```json
{
    "name": "Nitin",
    "number": "7888765928",
    "email": "nitin@example.com",
    "company": "IT",
    "gst": "29ABCDE4234F2Z5",
    "address": "Patiala"
}
```

> **Note:** All fields are required.

### Update Contact

**Request**

```json
{
    "name": "Nitin Rana",
    "address": "Amritsar"
}
```

Only include the fields you want to update.

### Query Parameters

| Parameter   | Description      |
| ----------- | ---------------- |
| `id`        | Contact ID       |
| `name`      | Contact name     |
| `number`    | Phone number     |
| `email`     | Email address    |
| `gst`       | GST number       |
| `page`      | Page number      |
| `page_size` | Records per page |

Example:

```http
GET /view_contacts.php?id=1
GET /view_contacts.php?name=nitin
GET /view_contacts.php?page=1&page_size=50
```

---

# Products API

| Endpoint                       | Method | Description       |
| ------------------------------ | :----: | ----------------- |
| `/add_products.php`            |  POST  | Create a product  |
| `/edit_products.php?id={id}`   |  PATCH | Update a product  |
| `/delete_products.php?id={id}` | DELETE | Delete a product  |
| `/view_products.php`           |   GET  | Retrieve products |

### Create Product

```json
{
    "product_code": "255",
    "product_name": "Keyboard",
    "cost_price": "80",
    "selling_price": "100",
    "tax": "10"
}
```

### Update Product

```json
{
    "selling_price": "150",
    "tax": "18"
}
```

Only include the fields you want to update.

### Query Parameters

| Parameter   | Description      |
| ----------- | ---------------- |
| `id`        | Product ID       |
| `code`      | Product code     |
| `name`      | Product name     |
| `page`      | Page number      |
| `page_size` | Records per page |

Example:

```http
GET /view_products.php?name=keyboard
GET /view_products.php?code=255
```

---

# Invoices API

| Endpoint                       | Method | Description       |
| ------------------------------ | :----: | ----------------- |
| `/create_invoices.php`         |  POST  | Create an invoice |
| `/edit_invoices.php?id={id}`   |  PATCH | Update an invoice |
| `/delete_invoices.php?id={id}` | DELETE | Delete an invoice |
| `/view_invoices.php`           |   GET  | Retrieve invoices |

### Create Invoice

```json
{
    "contact_id": 6,
    "invoice_no": "INV-10016",
    "due_date": "2026-07-24",
    "status": "unpaid",
    "items": [
        {
            "product_id": 26,
            "qty": 2
        }
    ]
}
```

### Update Invoice

```json
{
    "due_date": "2026-07-30",
    "status": "Unpaid",
    "items": [
        {
            "product_id": 26,
            "qty": 5
        }
    ]
}
```

Only include the fields you want to update.

### Query Parameters

| Parameter      | Description                      |
| -------------- | -------------------------------- |
| `id`           | Invoice ID                       |
| `invoice_no`   | Invoice number                   |
| `invoice_date` | Invoice date                     |
| `start_date`   | Filter start date (`YYYY-MM-DD`) |
| `end_date`     | Filter end date (`YYYY-MM-DD`)   |
| `page`         | Page number                      |
| `page_size`    | Records per page                 |

Example:

```http
GET /view_invoices.php?id=1
GET /view_invoices.php?invoice_no=INV-10016
GET /view_invoices.php?start_date=2026-06-01&end_date=2026-06-29
```

---

# Standard Response

Successful response

```json
{
    "message": "Success",
    "data": [],
    "pagination": {
        "page": 1,
        "page_size": 25,
        "total_records": 100,
        "total_pages": 4,
        "has_next": true,
        "has_prev": false
    }
}
```

Error response

```json
{
    "message": "Invalid request"
}
```

---

# Pagination

All **GET** endpoints support pagination.

| Parameter       | Description                              |
| --------------- | ---------------------------------------- |
| `page`          | Current page number                      |
| `page_size`     | Number of records per page               |
| `total_records` | Total available records                  |
| `total_pages`   | Total number of pages                    |
| `has_next`      | Indicates whether another page exists    |
| `has_prev`      | Indicates whether a previous page exists |

---

# HTTP Status Codes

| Code    | Description           |
| ------- | --------------------- |
| **200** | Request successful    |
| **201** | Resource created      |
| **400** | Bad request           |
| **401** | Unauthorized          |
| **404** | Resource not found    |
| **500** | Internal server error |
