**Customer Create Loan**
----
  Authenticated customer can send a loan request. <br>
  &nbsp;&nbsp;&nbsp; If customer exists in 'users' table, the customer is an authenticated customer.

 **URL**: /loan

**Method:**:  `POST`
  
**URL Params**: None

**Data Params**

*  **Required:**
 
   `loan_amount=[decimal]` `loan_terms=[int]` `cust_id=[uuid]`

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

    ```json
    {
        "message": "No User Found",
        "error": true,
        "code": 400
    }
    ```
    

  OR

    ```
    {SQL Error:....}
    ```
*   **Error:** true <br />
*   **Code:** 500 <br />

