ORServices is a free, open source, smartphone-friendly directory application for health, human and social services that uses [Open Referral](http://openreferral.org/) data formats. It’s designed to be an easy to deploy, modify and maintain alternative to expensive proprietary nonprofit service directories often used by 2-1-1 systems in the USA.

You can use ORServices as an interface to manage all aspects of your service directory data, or you can manage your data using the [OR Airtable Template](https://airtable.com/universe/expwt9yr65lFGUJAr/social-services-directory-v20) and display it automatically via ORServices. We can also set up a custom publishing workflow for you whereby your data is [transformed](https://github.com/openreferral/hsds_transformer) from its existing format into an [Open Referral’s Human Service Data Standard (HSDS)](http://docs.openreferral.org/en/latest/hsds/) zip file and regularly imported into an ORServices application.

Features include:
* Web and mobile-friendly, geo-aware directory software 
* Human Service Data Standard (HSDS) compliant data model
* Manage any facet of HSDS data within the app
* Create and manage users, and assign them the ability to manage contact, service, location and organization information on an organization by organization basis
* Content management system for customizing home and about pages, headers and footers, site colors, icons, images and logos.
* Location-aware search and filtering
* Manage multiple service taxonomies
* Download PDFs and CSVs of individual and filtered lists of services and organizations.
* Track and manage user data entry sessions
* Import data from Airtable, HSDS CSVs, HSDS Zip and HSDS Zip API
* Export data as HSDS CSV, HSDS Zip and HSDS Zip API
* Integration with Google Translate to offer contents in dozens of different languages.
* Integrate with Google Analytics for tracking user flows, usage statistics and more


### Installation

**👉Server Requirements:**


The Laravel framework has a few system requirements. You will need to make sure your server meets the following requirements:

* PHP >= 8.4 (Laravel 13; tested on 8.4 and 8.5)
* BCMath PHP Extension
* Curl PHP Extension
* Zip PHP Extension
* GD PHP Extension (image uploads)
* FTP PHP Extension
* PDO MySQL PHP Extension (MySQL 8 or MariaDB 10.6+)
* Ctype PHP Extension
* JSON PHP Extension
* Fileinfo PHP extension
* Mbstring PHP Extension
* OpenSSL PHP Extension
* PDO PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension


**👉 Web Server Configuration:**


*Apache*

 * This step is option if code is not starting on server.

`Pretty URLs`
Laravel includes a public/.htaccess file that is used to provide URLs without the index.php front controller in the path. Before serving Laravel with Apache, be sure to enable the mod_rewrite module so the .htaccess file will be honored by the server.

If the .htaccess file that ships with Laravel does not work with your Apache installation, try this alternative:

```bash
Options +FollowSymLinks -Indexes
RewriteEngine On

RewriteCond %{HTTP:Authorization} .
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

*Nginx*

If you are using Nginx, the following directive in your site configuration will direct all requests to the index.php front controller:

```bash
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

**👉Configuration**
   * Run git clone https://github.com/sarapis/orservices.git
   * Create a MySQL database for the project
   * mysql -u root -p, if using Vagrant: mysql -u homestead -p secret    
   * From the projects root run cp .env.example .env
   * Configure your .env file
   * Run composer install from the projects root folder
   * Run npm install & npm run dev from the projects root folder
   * From the projects root folder run sudo chmod -R 755 /orservices
   * From the projects root folder run php artisan key:generate
   * From the projects root folder run composer dump-autoload
    
**👉Database upload**
   * Create a database and import the starter dump `database/dump/01052024.sql` (default settings, roles and one admin user).
   * Then run `php artisan migrate --force` to apply any migrations newer than the dump.
   * Add the DB configuration to your `.env` file.
   * **Log in as `admin@admin.com` and change that account's password (or create your own admin and delete it) before the site is reachable by anyone else.**

**👉Running in production**
   * Set `APP_ENV=production` and `APP_DEBUG=false` in `.env` (the defaults in `.env.example`). With `APP_DEBUG=true`, any error shows a full debug page including configuration values.
   * Don't run `php artisan config:cache`: the admin settings pages write mail/SMS keys into `.env` at runtime, and a cached config would ignore them.
   * The web server user must be able to write `storage/`, `bootstrap/cache/` and the upload/export folders under `public/` (`uploads`, `csv`, `xlsx`, `zip`, `newExport`, `HSDS`, `csv_export`, `import_source_file`). Each of those folders ships an `.htaccess` that refuses script files; on Nginx add the equivalent, e.g. `location ~* ^/(uploads|csv|xlsx|zip|newExport|HSDS|csv_export|import_source_file|storage)/.*\.(php[0-9]?|phtml|phar)(\.|/|$) { return 403; }`.
   * Schedule `php artisan schedule:run` every minute. It runs the hourly Airtable/import sync and the daily `db:backup` (gzipped dumps in `storage/app/backup`, newest 14 kept; it uses `mariadb-dump` or `mysqldump`).
   * Application logs are at `/log-viewer`, visible only to logged-in users whose role has the `log-viewer::logs.list` permission.

**👉Local Development Server**

* If you have PHP installed locally and you would like to use PHP's built-in development server to serve your application, you may use the serve Artisan command. This command will start a development server at http://localhost:8000:


```bash
php artisan serve
```
