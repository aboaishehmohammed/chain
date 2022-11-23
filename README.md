
## About task

Employee Board is a simple web application  that help hr to manage the employee
the system have one hr manager
## simple api documentation:
https://documenter.getpostman.com/view/12988868/2s8YsoyuHg
## Steps to run the project :
1. Clone the repo .  <br> <br>
2. Install composer
   ` composer install`  <br> <br>
4. Edit `.env` file with your database configuration .  <br> <br>
5. run migration command <br>
   `php artisan migrate --seed`
    * This will create :
        - hr user :
            - Email : `admin@admin.com`
            - Password : `12345678`  <br> <br>
6. `php artisan key:generate` <br> <br>
7. `php artisan serve`


