<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Mini-Aspire API

Mini-Aspire API is an app that allows authenticated users to go through a loan application. [Click here to jump to documentation](https://github.com/Nithiya99/loan-api#documentation)

> In this coding challenge, I have assumed the users which exist the in 'users' table are authenticated users. [^1] I would check if the user is authenticated through their session token in real world scenario.

[^1]: user_type = 'Client' -> Customer and user_type = 'Admin' => Admin

## Installation
Please follow the following steps to install this app.

### System Pre-requisites
1. PHP **version 8.0** or above. (https://www.apachefriends.org/download.html)
2. Composer (https://getcomposer.org/download/)
3. git - to colne the repository from command line
4. Postman - to test the APIs

### Project Installation Commands
```
git clone https://github.com/Nithiya99/loan-api.git
composer update
```
Add .env and update database name (`DB_DATABASE`)[I have used 'loanDB' as myu database name], username (`DB_USERNAME`) and password (`DB_PASSWORD`). <br>
Note: The format of the .env file can be copied from '.env.example' file.
```
php artisan migrate:fresh --seed
php artisan serve
```

## Documentation
This app has been developed with the **Laravel 9** framework. **MySQL** has been used as the backend database. [Click here](https://github.com/Nithiya99/loan-api/blob/master/Documentation/Mini-Aspire%20API%20Documentation.pdf) to view details such as database design and design choices.

![alt text](https://github.com/Nithiya99/loan-api/blob/master/Documentation/Images/QuickDBD-export.png "LoansDB Schema") <br>

Click here to get [All postman routes](https://www.getpostman.com/collections/e4ca0dce5efc0025e81f) 

Detailed information of all the APIs are provided here:
1. [Customer Submit Loan Request](https://github.com/Nithiya99/loan-api/blob/master/Documentation/CustomerCreateLoanAPI.md)
2. [Admin Approve Loan Request](https://github.com/Nithiya99/loan-api/blob/master/Documentation/AdminApproveLoanAPI.md)
3. [Customer View Loans that Belong to him](https://github.com/Nithiya99/loan-api/blob/master/Documentation/CustomerViewLoanAPI.md)
4. [Customer Repay Loan](https://github.com/Nithiya99/loan-api/blob/master/Documentation/RepayLoanAPI.md)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
