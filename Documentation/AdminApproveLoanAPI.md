**Admin Approve Loan**
----
  Admin can approve the loan requests submitted by users through this API. <br>

 **URL**: http://<\your domain>/api/loan

**Method:**:  `PUT`
  
**URL Params**: None

**Request Body**: Pass in the Admin's ID and the loans ID. If all checks passes, the loan's status will be changed to APPROVED.

*  **Required:**
 
   `user_id=[string]` `loan_id=[string]`

**Sample Call:**

  ```json
    {
        "user_id":"91e2104f-1185-456e-8ff8-8883f20d76e8",
        "loan_id": "530b40a5-43b5-4e5c-ba58-a8cf8534e63f"
    }
  ```

**Success Response:**
```json
    {
        "message": {
            "id": 9,
            "loan_id": "cbad1dc3-77a9-40f8-9ee1-90cf1fecdfc1",
            "loan_amt": "50.00",
            "loan_terms": 3,
            "loan_status": "APPROVED",
            "cust_id": "8d68e317-d1fb-41ea-8007-a59cbcf0e25f",
            "loan_approve_id": "91e2104f-1185-456e-8ff8-8883f20d76e8",
            "loan_approve_date": "2022-03-13T03:46:31.566151Z",
            "created_at": "2022-03-13T02:30:51.000000Z",
            "updated_at": "2022-03-13T03:46:31.000000Z"
        },
        "error": false,
        "code": 200
    }
```
 
**Error Response:**

* If user_id is not an Admin

    ```json
        {
            "message": "No User Found",
            "error": true,
            "code": 400
        }
    ```
    OR

* If loan is not found

    ```json
        {
            "message": "Loan does not exist",
            "error": true,
            "code": 400
        }
    ```
    OR

* If loan is already PAID or APPROVED

    ```json
        {
            "message": "Loan status is already PAID or APPROVED",
            "error": true,
            "code": 400
        }
    ```
    OR

* Validation Errors: Will be returned if any of the required fields are null or if the `loan_amt` requested is more than $10,000000.00.

    ```json
        {
            "Validation Errors":{
                "loan_id": [
                    "The loan id field is required."
                ]
            },
            "error": true,
            "code": 400
        }
        
    ```

    OR    

* If there is any error with the database, an SQL Exception Error will be returned.

    ```json
        {
            "SQL Exception": "SQLSTATE[42S22]: Column not found: 1054 Unknown column 'loan_' in 'field list' (SQL: insert into `loans` (`loan_`, `loan_terms`, `loan_status`, `cust_id`, `loan_id`, `updated_at`, `created_at`) values (5000, 3, PENDING, 8d68e317-d1fb-41ea-8007-a59cbcf0e25f, 105856bc-3d19-4bb7-9808-dccb5fa1f19c, 2022-03-13 02:56:53, 2022-03-13 02:56:53))",
            "error": true,
            "code": 500
        }
    ```

