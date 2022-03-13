**Customer Repay Loan**
----
  Customers pay their loans through this API. <br>

 **URL**: `http://<your domain>/api/repayloan/`

**Method:**:  `PUT`
  
**URL Params**: None

**Request Body**: Pass in the Admin's ID and the loans ID. If all checks passes, the loan's status will be changed to APPROVED.

*  **Required:**
 
   `user_id=[string]` `loan_id=[string]` `amount=[decimal]`

**Sample Call:**

  ```json
    {
        "user_id":"8d68e317-d1fb-41ea-8007-a59cbcf0e25f",
        "loan_id":"530b40a5-43b5-4e5c-ba58-a8cf8534e63f",
        "amount":"10"
    }
  ```

**Success Response:**
* If loan repayment is successful for exactly one term

    ```json
        {
            "Fully Paid Term(s)": " Term 1, ",
            "Partially Paid Term": " ",
            "Loan closed": " ",
            "error": false,
            "code": 200
        }
    ```
    OR

* If user sends money to pay of one term and partially the next term. The loan due from the next term will be deducted accordingly.

    ```json
        {
            "Fully Paid Term(s)": " Term 2, ",
            "Partially Paid Term": " Term 3",
            "Loan closed": " ",
            "error": false,
            "code": 200
        }
    ```

* If user sends money to pay off all the terms and still have some money remaining, the remaining money will be returned back to the user.

    ```json
        {
            "Fully Paid Term(s)": " Term 3, ",
            "Partially Paid Term": " ",
            "Loan closed": " Loan fully paid. Money Returned: $666.67",
            "error": false,
            "code": 200
        }
    ```
 
**Error Response:**

* If user_id and loan_id details do not match

    ```json
        {
            "message": "Please check your loanId and UserId. Details do not match.",
            "error": true,
            "code": 400
        }
    ```
    OR

* If repay money is less than 0

    ```json
        {
            "amount": [
                "The amount must be greater than 0."
            ]   
        }
    ```
    OR

* If loan has not been approved by Admin yet

    ```json
        {
            "message": "Loan status is PENDING. Please wait till loan is sanctioned.",
            "error": true,
            "code": 400
        }
    ```
    OR

* If the repay money is less than the repayment money for the term

    ```json
        {
            "0": {
                "message": "Loan repayment failed for term 2",
                "Loan payment due date": "2022-03-26 13:50:08",
                "loan due": "$16.34",
                "loan payment status": "PENDING"
            },
            "error": true,
            "code": 400
        }
    ```
    OR

* Validation Errors:

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

