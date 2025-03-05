# Project setup instructions

Follow the steps below to get started.

## Step 1: Install XAMPP

1. Download XAMPP from [here](https://www.apachefriends.org/index.html). Better to get the latest version.
2. Run the installer and follow the on-screen instructions to complete the installation process.
3. Once installed, open the XAMPP Control Panel and start the following services:
    - **Apache**
    - **MySQL**

> **Note**: By default, XAMPP uses `localhost` for the database server, and the database is accessible via `http://localhost/phpmyadmin`.

## Step 2: Clone the repository

```bash
https://github.com/Gayan-Muthukumarana/project-astudio.git
cd your-repository-name
```
## Step 3: Install PHP dependencies

Navigate to your project folder and run:

```bash
composer install
```

## Step 4: Set up environment variables

```bash
cp .env.example .env
```
or else make a copy of `.env.example` and save it as `.env`.

Edit the `.env` file to set up your local environment. For example, set your database connection as follows:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=astudio_project_db
DB_USERNAME=root
DB_PASSWORD=
```
> **Note**: XAMPP's default MySQL username is `root` with `no password`, so you can leave `DB_PASSWORD` empty.

## Step 5: Generate application Key

```bash
php artisan key:generate
```
## Step 6: Migrate the database & run the seeders

```bash
php artisan migrate
php artisan db:seed
```
This will create the database tables & the default user as well.

## Step 7: Set up Laravel passport for API authentication

```bash
php artisan passport:install
```
This will regenerate the `client_id` and `client_secret`. You may copy them and set `PASSPORT_PERSONAL_ACCESS_CLIENT_ID` and `PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET` values in your `.env` file.

# API Documentation

## Authentication

## 1. Login

### Endpoint
`POST /api/login`

### Description
Logs in a user and returns a JWT token.

### Request Body
```json
{
    "email": "gayan.muthukumarana@test.test",
    "password": "password#1234"
}
```

### Responses

#### Success (200)
```json
{
    "success": true,
    "statusCode": 200,
    "message": "User has been logged successfully.",
    "token": {
        "token_type": "Bearer",
        "expires_in": 10000,
        "access_token": "token_here",
        "refresh_token": "token_here"
    }
}
```

#### Error (422)
```json
{
  "success": false,
  "statusCode": 422,
  "message": "Validation error!",
  "error": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

#### Error (401)
```json
{
  "success": false,
  "statusCode": 401,
  "message": "Unauthorized!",
  "data": ""
}
```

# Attribute

## 1. **Get all attributes**
- **Endpoint**: `GET /api/attributes`
- **Description**: Retrieves all attributes. You can filter the attributes based on their name or type.
- **Query Parameters**:
    - `name`: Filter by the name of the attribute (optional)
    - `type`: Filter by type of attribute (optional, values: `text`, `date`, `number`, `select`).
- **Response**:
    - **Code**: 200
    - **Body**:
      ```json
      {
        "success": true,
        "statusCode": 200,
        "message": "Data retrieved!",
        "data": [
          {
            "id": 1,
            "name": "Department",
            "type": "text"
          },
          {
            "id": 2,
            "name": "Start Date",
            "type": "date"
          }
        ]
      }
      ```

## 2. **Get specific attribute**
- **Endpoint**: `GET /api/attributes/{id}`
- **Description**: Retrieves a specific attribute by its ID.
- **URL Parameters**:
    - `id`: The ID of the attribute to retrieve.
- **Response**:
    - **Code**: 200
    - **Body**:
      ```json
      {
        "success": true,
        "statusCode": 200,
        "message": "Data retrieved!",
        "data": {
          "id": 1,
          "name": "Department",
          "type": "text"
        }
      }
      ```

## 3. **Create new attribute**
- **Endpoint**: `POST /api/attributes`
- **Description**: Creates a new attribute with a name and type.
- **Request Body**:
    - `name`: The name of the attribute (required, string).
    - `type`: The type of the attribute (required, values: `text`, `date`, `number`, `select`).
- **Response**:
    - **Code**: 201
    - **Body**:
      ```json
      {
        "success": true,
        "statusCode": 201,
        "message": "Attribute created successfully!!",
        "data": {
          "id": 3,
          "name": "End Date",
          "type": "date"
        }
      }
      ```

## 4. **Update attribute**
- **Endpoint**: `PUT /api/attributes/{id}`
- **Description**: Updates an existing attribute by its ID.
- **URL Parameters**:
    - `id`: The ID of the attribute to update.
- **Request Body**:
    - `name`: The new name of the attribute (required, string).
    - `type`: The new type of the attribute (required, values: `text`, `date`, `number`, `select`).
- **Response**:
    - **Code**: 200
    - **Body**:
      ```json
      {
        "success": true,
        "statusCode": 200,
        "message": "Attribute updated successfully!",
        "data": {
          "id": 1,
          "name": "Department",
          "type": "text"
        }
      }
      ```

## 5. **Delete attribute**
- **Endpoint**: `DELETE /api/attributes/{id}`
- **Description**: Deletes an attribute by its ID.
- **URL Parameters**:
    - `id`: The ID of the attribute to delete.
- **Response**:
    - **Code**: 200
    - **Body**:
      ```json
      {
        "success": true,
        "statusCode": 200,
        "message": "Attribute deleted successfully!",
        "Deleted Attribute ID": 1
      }
      ```

## Error Responses

### Common Error Response Format:
```json
{
  "success": false,
  "statusCode": 400,
  "message": "Error message",
  "data": {}
}
```


# Projects

## 1. **Get all projects**
- **Endpoint**: `GET /api/projects`
- **Description**: Retrieves a list of all projects, with optional filters and dynamic attribute filtering.
- **Query Parameters**:
    - `filters`: Filters for the project name, status, or dynamic attribute values.
    - `filters.eav`: Filters for dynamic attribute values. Provide an object with the attribute name and value to filter by (optional).
- **Response**:
    - **Code**: 200
    - **Body**:
      ```json
      {
        "success": true,
        "statusCode": 200,
        "message": "Data retrieved!",
        "data": [
          {
            "id": 1,
            "name": "Project A",
            "status": 1,
            "attributes": [
              {
                "id": 1,
                "name": "Department",
                "value": "IT"
              }
            ]
          }
        ]
      }
      ```

## 2. **Get specific project**
- **Endpoint**: `GET /api/projects/{id}`
- **Description**: Retrieves a specific project by its ID along with its associated dynamic attributes.
- **URL Parameters**:
    - `id`: The ID of the project to retrieve.
- **Response**:
    - **Code**: 200
    - **Body**:
      ```json
      {
        "success": true,
        "statusCode": 200,
        "message": "Data retrieved!",
        "data": {
          "id": 1,
          "name": "Project A",
          "status": 1,
          "attributes": [
            {
              "id": 1,
              "name": "Department",
              "value": "IT"
            }
          ]
        }
      }
      ```

## 3. **Create new project**
- **Endpoint**: `POST /api/projects`
- **Description**: Creates a new project with a name, status, and optional dynamic attributes.
- **Request Body**:
    - `name`: The name of the project (required).
    - `status`: The status of the project (required).
    - `attributes`: A list of dynamic attributes for the project (optional).
        - `id`: The ID of the attribute (required).
        - `value`: The value of the attribute (required).
- **Response**:
    - **Code**: 201
    - **Body**:
      ```json
      {
        "success": true,
        "statusCode": 201,
        "message": "Project created successfully!!",
        "data": {
          "id": 1,
          "name": "Project A",
          "status": 1
        }
      }
      ```

## 4. **Update project**
- **Endpoint**: `PUT /api/projects/{id}`
- **Description**: Updates an existing project by its ID.
- **URL Parameters**:
    - `id`: The ID of the project to update.
- **Request Body**:
    - `name`: The new name of the project (required).
    - `status`: The new status of the project (required).
    - `attributes`: A list of dynamic attributes for the project (optional).
        - `id`: The ID of the attribute (required).
        - `value`: The value of the attribute (required).
- **Response**:
    - **Code**: 200
    - **Body**:
      ```json
      {
        "success": true,
        "statusCode": 200,
        "message": "Project updated successfully!",
        "data": {
          "id": 1,
          "name": "Project A",
          "status": 1
        }
      }
      ```

## 5. **Delete project**
- **Endpoint**: `DELETE /api/projects/{id}`
- **Description**: Deletes a project by its ID.
- **URL Parameters**:
    - `id`: The ID of the project to delete.
- **Response**:
    - **Code**: 200
    - **Body**:
      ```json
      {
        "success": true,
        "statusCode": 200,
        "message": "Project deleted successfully!",
        "Deleted Project ID": 1
      }
      ```

## 6. **Assign users to project**
- **Endpoint**: `POST /api/projects/{id}/assign-users`
- **Description**: Assigns users to a project.
- **URL Parameters**:
    - `id`: The ID of the project to which users will be assigned.
- **Request Body**:
    - `user_ids`: An array of user IDs to assign to the project.
- **Response**:
    - **Code**: 200
    - **Body**:
      ```json
      {
        "success": true,
        "statusCode": 200,
        "message": "User(s) assigned to the project successfully!!",
        "data": {
          "id": 1,
          "name": "Project A",
          "status": 1,
          "users": [
            {
              "id": 1,
              "first_name": "John",
              "last_name": "Doe",
              "email": "john.doe@example.com"
            }
          ]
        }
      }
      ```

## Error Responses

### Common Error Response Format:
```json
{
  "success": false,
  "statusCode": 400,
  "message": "Error message",
  "data": {}
}
```
# Timesheet

## 1. Get all timesheets
**Endpoint:**  
`GET /api/timesheets`

**Description:**  
Retrieve a list of timesheets with optional filtering.

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "message": "Data retrieved!",
  "data": []
}
```

---

## 2. Get a single timesheet
**Endpoint:**  
`GET /api/timesheets/{id}`

**Description:**  
Retrieve a single timesheet by ID.

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "message": "Data retrieved!",
  "data": { }
}
```

---

## 3. Create a timesheet
**Endpoint:**  
`POST /api/timesheets`

**Description:**  
Create a new timesheet.

**Request Body:**
```json
{
  "task_name": "string (required, max 255 chars)",
  "date": "date (required)",
  "hours": "number (required, min 0)",
  "user_id": "integer (required, must exist in users table)",
  "project_id": "integer (required, must exist in projects table)"
}
```

**Response:**
```json
{
  "success": true,
  "statusCode": 201,
  "message": "Timesheet created successfully!",
  "data": { }
}
```

---

### 4. Update a timesheet
**Endpoint:**  
`PUT /api/timesheets/{id}`

**Description:**  
Update an existing timesheet.

**Request Body (Any of the following fields can be updated):**
```json
{
  "task_name": "string (max 255 chars)",
  "date": "date",
  "hours": "number (min 0)",
  "user_id": "integer (must exist in users table)",
  "project_id": "integer (must exist in projects table)"
}
```

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "message": "Timesheet updated successfully!",
  "data": { }
}
```

---

## 5. Delete a timesheet
**Endpoint:**  
`DELETE /api/timesheets/{id}`

**Description:**  
Delete a timesheet by ID.

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "message": "Timesheet deleted successfully!",
  "Deleted Timesheet ID": "{id}"
}
```

---

# User

## 1. Get all users
**Endpoint:**  
`GET /api/users`

**Description:**  
Retrieve a list of users with optional filtering.

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "message": "Data retrieved!",
  "data": []
}
```

---

## 2. Get a single user
**Endpoint:**  
`GET /api/users/{id}`

**Description:**  
Retrieve a single user by ID.

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "message": "Data retrieved!",
  "data": { }
}
```

---

## 3. Create a user
**Endpoint:**  
`POST /api/users`

**Description:**  
Create a new user.

**Request Body:**
```json
{
  "first_name": "string (required, max 255 chars)",
  "last_name": "string (required, max 255 chars)",
  "email": "string (required, unique, email format)",
  "password": "string (required, min 8 chars, confirmed)"
}
```

**Response:**
```json
{
  "success": true,
  "statusCode": 201,
  "message": "User created successfully!",
  "data": { }
}
```

---

## 4. Update a user
**Endpoint:**  
`PUT /api/users/{id}`

**Description:**  
Update an existing user.

**Request Body (Any of the following fields can be updated):**
```json
{
  "first_name": "string (max 255 chars)",
  "last_name": "string (max 255 chars)",
  "email": "string (email format, unique)",
  "password": "string (min 8 chars, confirmed)"
}
```

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "message": "User updated successfully!",
  "data": { }
}
```

---

## 5. Delete a user
**Endpoint:**  
`DELETE /api/users/{id}`

**Description:**  
Delete a user by ID.

**Response:**
```json
{
  "success": true,
  "statusCode": 200,
  "message": "User deleted successfully!",
  "Deleted User ID": "{id}"
}
```

---

# Conclusion

Finally, the below credentials is the test user which can be used to log in and get the token to be used on other API end points.

```json
{
    "email": "gayan.muthukumarana@test.test",
    "password": "password#1234"
}
```
