
### Installation Instructions
1. Run `git clone https://github.com/sarapis/orservices`
2. Create a MySQL database for the project
    * ```mysql -u root -p```, if using Vagrant: ```mysql -u homestead -psecret```
    * ```create database nycconnection;```
    * ```\q```
3. From the projects root run `cp .env.example .env`
4. Configure your `.env` file
5. Run `composer update` from the projects root folder
6. From the projects root folder run `sudo chmod -R 755 ../orservices`
7. From the projects root folder run `php artisan key:generate`
8. From the projects root folder run `php artisan migrate`
9. From the projects root folder run `php artisan db:seed`
10. From the projects root folder run `composer dump-autoload`
11. After login in admin panel, try synchronize of Data from Airtable.