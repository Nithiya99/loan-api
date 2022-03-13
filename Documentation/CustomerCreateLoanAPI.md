**Customer Create Loan**
----
  Authenticated customer can send a loan request. <br>
  &nbsp;&nbsp;&nbsp; For this app, it is assumed that if the customer exists in the 'users' table, he/she is authenticated. In normal scenario, the user will be checked if he is valid through their session token.

 **URL**: `http://<your domain>/api/loan/`

**Method:**:  `POST`
  
**URL Params**: None

**Request Body**

*  **Required:**
 
   `loan_amount=[decimal]` `loan_terms=[int]` `cust_id=[string]`

**Sample Call:**

  ```json
    {
        "loan_amt": "500",
        "loan_terms": 3,
        "cust_id": "8d68e317-d1fb-41ea-8007-a59cbcf0e25f"
    }
  ```

**Success Response:**
```json
    {
        "message": {
            "loan_amt": "500",
            "loan_terms": 3,
            "loan_status": "PENDING",
            "cust_id": "8d68e317-d1fb-41ea-8007-a59cbcf0e25f",
            "loan_id": "0f0b71a2-0a5e-4ba6-a754-6e12b97876a3",
            "updated_at": "2022-03-13T02:45:52.000000Z",
            "created_at": "2022-03-13T02:45:52.000000Z"
        },
        "error": false,
        "code": 200
    }
```
 
**Error Response:**

* Customer not found error

    ```json
        {
            "message": "No User Found",
            "error": true,
            "code": 400
        }
    ```
    OR

* Validation Errors: Will be returned if any of the required fields are null or if the `loan_amt` requested is more than $10,000000.00.

    ```json
        {
            "Validation Errors":{
                "loan_amt":[
                    "The loan amt must not be greater than 8 characters."
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

