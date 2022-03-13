**Customer View Loan that Belongs to him**
----
  Customer can view all his loans that he has applied for. <br>

 **URL**: `http://<your domain>/api/loan/<user_id>`

**Method:**:  `GET`
  
**URL Params**

*  **Required:**
 
   `user_id=[string]`

**Request Body**

**Sample Call:**

  `http://<your domain>/api/loan/<user_id>`

**Success Response:**: Will return the follwoing if there are loans under that user.
```json
    {
        "Loan Found": [
            {
                "id": 12,
                "loan_id": "c5eb2674-8904-436a-a2c8-d5135704902c",
                "loan_amt": "5000.00",
                "loan_terms": 3,
                "loan_status": "PENDING",
                "cust_id": "82a58af5-9f55-4a68-a207-adeee6d9e9df",
                "loan_approve_id": null,
                "loan_approve_date": null,
                "created_at": "2022-03-13T03:58:59.000000Z",
                "updated_at": "2022-03-13T03:58:59.000000Z"
            }
        ],
        "error": false,
        "code": 200
    }
```
 
**Error Response:**

* If no such user exists or the user has no loans applied.

    ```json
        {
            "Loan Found": "None - Please check your ID",
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

