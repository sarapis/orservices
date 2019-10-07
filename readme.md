This codebase allows you to pull data from this AirTable Template: https://airtable.com/universe/expwt9yr65lFGUJAr/open-referral-social-services-directory-v20

... into a web app that looks like this: http://orservices.sarapis.org

How does it work? The code pulls data from AirTable via you AirTable API Key and syncs it with a MySQL database. These imports can be triggers as fast as you'd like. 

The database is used (and controlled by) a Laravel/PHP app that renders apps using JavaScript frameworks. Our app uses Vue.JS for that but it could just as easily be React.

The result is an online directory app that is responsive, can deliver full search, multi-filter browsing and the mapping, charting and exporting of all data. We built this for Open Referral, but we’d love to adapt it to more use cases and also generalize the features so other people can use it too.

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
11. After login in admin panel, go to Datasync > Import and select whether you'd like to import data either from an Airtable following the Open Referral template, or from an HSDS Zip file. More information on the HSDS zip file coming soon.
