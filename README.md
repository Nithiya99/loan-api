<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Mini-Aspire API

Mini-Aspire API is an app that allows authenticated users to go through a loan application. This app contains REST APIs for the following features:
1. Customer create a loan
2. Admin approve loan
3. Fill in the rest

> In this coding challenge, I have assumed the users which exist the in 'users' table are authenticated users. [^1] I would check if the user is authenticated through their session token in real world scenario.

[^1]: user_type = 'Client' -> Customer and user_type = 'Admin' => Admin

## Installation
Please follow the following steps fto install this app.

### System Pre-requisites
1. PHP **version 8.0** or above. (https://www.apachefriends.org/download.html)
2. Composer (https://getcomposer.org/download/)
3. git - to colne the repository from command line
4. Postman - to test the APIs

### Project Installation Commands
```
git clone https://github.com/Nithiya99/loan-api.git
composer update
add .env and update database settings
php artisan migrate:fresh --seed
php artisan serve
```

## Documentation
This app has been developed with the **Laravel 9** framework. **MySQL** has been used as the backend database. [Click here]() to view details such as database design.

![alt text](https://github.com/Nithiya99/loan-api/blob/master/Documentation/Images/QuickDBD-export.png "LoansDB Schema")

Detailed information of all the APIs are provided here:
1. [Customer Submit Loan Request](https://github.com/Nithiya99/loan-api/blob/master/Documentation/CustomerCreateLoanAPI.md)
2. [Admin Approve Loan Request](https://github.com/Nithiya99/loan-api/blob/master/Documentation/AdminApproveLoanAPI.md)
3. [Customer View Loans that Belong to him](https://github.com/Nithiya99/loan-api/blob/master/Documentation/CustomerViewLoanAPI.md)
4. [Customer Repay Loan](https://github.com/Nithiya99/loan-api/blob/master/Documentation/Images/QuickDBD-export.png)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
