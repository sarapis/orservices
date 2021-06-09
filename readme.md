# ORServices
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

## Installation

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

`Pretty URLs` Laravel includes a public/.htaccess file that is used to provide URLs without the index.php front controller in the path. Before serving Laravel with Apache, be sure to enable the mod_rewrite module so the .htaccess file will be honored by the server.

If the .htaccess file that ships with Laravel does not work with your Apache installation, try this alternative:
```
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
```
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

**👉 Requirements:**
   * MySQL
   * npm
   * composer

**👉Configuration:**
   * Run git clone on this repo
   * Login as the root user and create a MySQL database for the project:
      ```
      mysql -u root -p
      CREATE DATABASE [database name];
      ```
   * Create required tables by uploading the latest sql dump file:
      ```
      mysql -u root -p [your database name] < database/dump/[latest date in folder].sql
      ```
   * Create a new `.env` config file from example file:
      ```
      cp .env.example .env
      ```
   * Add MySQL password and database name to the .env file:
      ```
      DB_DATABASE=[your database name]
      DB_USERNAME=root
      DB_PASSWORD=[your password]
      ```
   * Get PHP dependencies:
      ```
      composer update
      ```
   * Set up Node.js dependencies:
      ```
      npm install
      npm run dev
      ```
   * Modify file permissions of everything in the project:
      ```
      sudo chmod -R 755 ../orservices
      ```
      Git may read this as change in file permissions as a diff. To ignore this, run:
      ```
      git config core.fileMode false
      ```
   * Add `APP_KEY` to `.env` file:
      ```
      php artisan key:generate
      ```
   *  From the projects root folder run:
      ```
      php artisan db:seed
      ```
   * From the projects root folder run:
      ```
      composer dump-autoload
      ```

**👉Local Development Server**

If you have PHP installed locally and you would like to use PHP's built-in development server to serve your application, you may use the serve Artisan command. This command will start a development server at http://localhost:8000
```
php artisan serve
```

**👉GCloud App Engine Deployment**
   * Install [Googld Cloud SDK](https://cloud.google.com/sdk/docs/install)
   * Obtain a copy of the `env_variables.yaml` file from a dev and include in the root directory
   * Replace the APP_KEY in the `env_variables.yaml` file with the APP_KEY in the `.env` file
   * From the root directory run:
     ```
     gcloud beta app deploy --no-cache
     ```
