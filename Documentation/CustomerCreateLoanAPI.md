**Customer Create Loan**
----
  Authenticated customer can send a loan request. <br>
  &nbsp;&nbsp;&nbsp; If customer exists in 'users' table, the customer is an authenticated customer.

 **URL**: /loan

**Method:**:  `POST`
  
**URL Params**: None

* **Data Params**

  **Required:**
 
   `loan_amount=[decimal]` `loan_terms=[int]` `cust_id=[uuid]`

**Sample Call:**

  ```json
    {
        "loan_amt": "50",
        "loan_terms": 3,
        "cust_id": "8d68e317-d1fb-41ea-8007-a59cbcf0e25f"
    }
  ```

**Success Response:**

&nbsp;&nbsp;&nbsp **Message:**
```json
    {
        "loan_amt": "50",
        "loan_terms": 3,
        "loan_status": "PENDING",
        "cust_id": "8d68e317-d1fb-41ea-8007-a59cbcf0e25f",
        "loan_id": "cbad1dc3-77a9-40f8-9ee1-90cf1fecdfc1",
        "updated_at": "2022-03-13T02:30:51.000000Z",
        "created_at": "2022-03-13T02:30:51.000000Z"
    }
```
&nbsp;&nbsp;&nbsp **Error:** false <br />
&nbsp;&nbsp;&nbsp **Code:** 200 <br />
 
* **Error Response:**

    &nbsp;&nbsp;&nbsp**Validation Errors:** 
    ```json
        {
            "loan_amt": [
                "The loan amt must not be greater than 8 characters."
            ]
        }
    ```
    &nbsp;&nbsp;&nbsp**Error:** true <br />
    &nbsp;&nbsp;&nbsp**Code:** 400 <br />
    

  OR

    &nbsp;&nbsp;&nbsp**Validation Errors:** 
    ```SQL Error:....
    ```
    &nbsp;&nbsp;&nbsp**Error:** true <br />
    &nbsp;&nbsp;&nbsp**Code:** 500 <br />

