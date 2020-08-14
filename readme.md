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

* PHP >= 7.2.5
* BCMath PHP Extension
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
   * mysql -u root -p, if using Vagrant: mysql -u homestead -psecret    
   * From the projects root run cp .env.example .env
   * Configure your .env file
   * Run composer update from the projects root folder
   * Run npm install & npm run dev from the projects root folder
   * From the projects root folder run sudo chmod 777 -R .env 
   * From the projects root folder run sudo chmod 777 -R bootstrap/cache/
   * From the projects root folder run sudo chmod 777 -R storage
   * From the projects root folder run php artisan key:generate
   * From the projects root folder run php artisan migrate
   * From the projects root folder run php artisan db:seed
   * From the projects root folder run composer dump-autoload
   * Run sudo a2enmod rewrite
   * Run sudo service apache2 restart
    
**👉Database upload**
   * Create database and upload sql dump file from database/dump/latest_date.sql



**👉Local Development Server**

* If you have PHP installed locally and you would like to use PHP's built-in development server to serve your application, you may use the serve Artisan command. This command will start a development server at http://localhost:8000:


```bash
php artisan serve
```
