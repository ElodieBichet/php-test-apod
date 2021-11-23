# Documentation

Please read [Readme file](README.md) first to understand the context of this app.

[![Maintainability](https://api.codeclimate.com/v1/badges/d35c0ff821c2f6b1416d/maintainability)](https://codeclimate.com/github/ElodieBichet/php-test-apod/maintainability)

## Table of Contents
1.  __[Technologies](#technologies)__
  * [Server](#server)
  * [Framework and libraries](#framework-and-libraries)
2.  __[Installation](#installation)__
  * [Configure environment variables](#configure-environment-variables)
  * [Create the database](#create-the-database)
  * [Enjoy the app](#enjoy-the-app)
3.  __[Features](#features)__
  * [Astronomy Photo Of the Day](#astronomy-photo-of-the-day)
  * [Authentication and authorization](#authentication-and-authorization)
4.  __[Tests](#tests)__
  * [Configure PHP Unit](#configure-php-unit)
  * [Run the tests](#run-the-tests)

## __TECHNOLOGIES

### __Server__
You need a web server with PHP7 (> 7.2.5) and MySQL.  
Versions used in this project:
* Apache 2.4.46
* PHP 7.4.9
* MySQL 5.7.31

### __Framework and libraries__
Framework: __Symfony ^5.2.14__  
Dependencies manager: __Composer ^2.1.8__  

To run tests, you also need __PHPUnit__. See requirements in [PHPUnit documentation - EN](https://phpunit.readthedocs.io/en/latest/installation.html#requirements) / [FR](https://phpunit.readthedocs.io/fr/latest/installation.html#pre-requis)

ORM: Doctrine
Template engine: Twig  

---
## INSTALLATION

### __Configure environment variables__
After cloning the project, you need to configure at least these lines in both your environment file:
```env
...
###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
APOD_API_KEY=HereYourApodApikey
```
If you don't have an API key yet for APOD API, you can request one in this page: https://api.nasa.gov/#signUp

### __Create the database__
After running install composer command, if you are in a dev environment, you can create the database and fill it with fake users and few media with the following command:
```
$ composer prepare-database-dev
```
Here is the Database model:

  ![DB Model](DB-model.png)

### __Enjoy the app__
You need to login to see the photo of the day page. If you have used User fixtures to create fake users, you can login with these data for example:  
login: user1@email.com  
password: password

---
## FEATURES

### Astronomy Photo Of the Day

__General Operation__  
1. The Service (CallApodAPIService) makes a GET request to the Nasa Apod API and return the picture of the day in json format
2. The Service unserializes the Json Response to transform it a Media object (Entity)
3. The custom Command (AddApodInDbCommand) get the generated media for a given date (no given date = today) to save it in the database. This command can be run daily with CRON.
4. The Controller (ApodController) get the last image type media from the Database and displays it in the Twig template.

__Service__  
The Service can be called anywhere in the app. It proposes 2 methods:
* getMediaOfTheDay => make a request to the API to fetch the photo of the given date. If no date is given as argument, the date is today.
* createMediaFromApod => unserialize the json response of the API to match with Media entity format.

__Command__  
The command can be run manually with any expired date as argument (format: 'Y-m-d'), with or without the option --replace.   
Example:
```
$ app:add-apod-in-db 2021-11-01 --replace
```
In this example, the app will get the photo of the day 2021-11-01 from the Nasa API. Because the option --replace is used, if there is already a media with this date, it will be removed from the database and a new Media will be created instead (with a new ID).

  ![APOD Page](apod-page.png)

### Authentication and authorization

__Users__
Users of the site are saved in the database. There is no registration form, so you can use the fixtures to add users to the app.

__Login form__
To access to the page with the photo of the day, you need to login as a user, so you will be redirected to the login page. Enter a user's email address (example 'user1@email.com') and a password ('password'), and valid the form. If your data are valid, you access directly to the page and can admire the Astronomy Photo Of the Day!

  ![Login form](login-form.png)

---
## TESTS

### __Configure PHP Unit__
Unless you use SQlite for your tests, you can use this command to create the test database and fill it with fake users:
```
$ composer prepare-database-test
```
### __Run the tests__
To run all tests, use the following command:
```
$ php bin/phpunit
```
See more details and options about command-line test runner in [PHP Unit documentation - EN](https://phpunit.readthedocs.io/en/latest/textui.html) / [FR](https://phpunit.readthedocs.io/fr/latest/textui.html).

Currently there is only a functional test for the custom command, but the app is ready to easily add unit and functional tests, thanks to PHP Unit Bridge and Liip Test Fixtures Bundle.