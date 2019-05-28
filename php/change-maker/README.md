## Simple Change Provider

An api endpoint that accepts parameters "total cost" and "amount provided", and will return the change to be given, broken down into individual denominations.  Supports multiple currencies (currently supported: US Dollars and Japanese Yen).


### Requirements

Here are the details of the code challenge.  Please complete 24 hours prior to your interview.  

Code Challenge!  We would like for you to write an API endpoint in PHP, which upon execution accepts two arguments.  

One of those arguments should be a “total cost” (in dollars and/or cents) and the other an “amount provided” (also in dollars and/or cents).   Return as output the change that should be provided, **by returning the count of each denomination of bills and/or coins**.  +points for object oriented and/or advanced concepts.  

As a reminder, our current tech stack is:  Centos, MySQL, PHP, Symfony, Doctrine, APIPlatform, Jenkins, PHPUnit, Behat.   Have fun with it!  


### Usage

__Endpoint:__

* Base: http://changer.peterg-webdeveloper.com/make-change.php
* Parameters
    * *total_cost*: integer 
    * *amount_provided*: integer
    * *ctype*: currency type (yen, usd, etc)
    * *pretty*: boolean, if true will return JSON in 'pretty-print' format

__Examples:__

* http://www.dev-changemaker.com/make-change.php?total_cost=601&amount_provided=800&ctype=yen
* http://www.dev-changemaker.com/make-change.php?total_cost=601&amount_provided=800&ctype=usd&pretty

### Structure

* make-change.php: API endpoint implementation
* Currency.php: Base class defining interface of currency objects and possible default implementations
* Usdollar.php, Yen.php, etc.: Implentation of specific currency that extends Currency class
* Changer.php: Class containing currency factory function, encapsulates implemenation.
