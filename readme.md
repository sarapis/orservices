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
   * From the projects root folder run sudo chmod -R 755 ../orservices
   * From the projects root folder run php artisan key:generate
   * From the projects root folder run php artisan migrate
   * From the projects root folder run php artisan db:seed
   * From the projects root folder run composer dump-autoload
    
**👉Database upload**
   * Create database and upload sql dump file from database/dump/latest_date.sql



**👉Local Development Server**

* If you have PHP installed locally and you would like to use PHP's built-in development server to serve your application, you may use the serve Artisan command. This command will start a development server at http://localhost:8000:


```bash
php artisan serve
```

**👉Deploy to Microsoft Azure (App Service Deployment)**

To deploy the application to Microsoft Azure (https://azure.com) follow the below steps. The guide assumes you have signed up for Azure account, have an active subscription and basic Linux server administration skills.

*Create App Service* 
* Login to your Azure account and navigate to subscriptions page (https://portal.azure.com/#blade/Microsoft_Azure_Billing/SubscriptionsBlade). Azure gives a Free Trial subscription on signup.
* Once you have confirmed you have an active subscription, create a resource group. A resource group is a container that holds related resources. Resources could be applications e.g App service, databases e.g Azure Database for MySQL etc. Here is a guide on creating a resource group - https://docs.microsoft.com/en-us/azure/azure-resource-manager/management/manage-resource-groups-portal#create-resource-groups
* Navigate to App Services (https://portal.azure.com/#blade/HubsExtension/BrowseResource/resourceType/Microsoft.Web%2Fsites) and click the "+ Add" button
* Fill out the details and click "Review + create" button. Make sure to select the subscription you created ealier and the resource group. Provide the instance name (the name will form part of the application url e.g naming your service orservices-test, the application will be accessible via https://orservices-test.azurewebsites.net/). For publish select "Code", Runtime stack - select PHP 7.4, Region - select any (tip: select the region where most of the users using your application are located to reduce latency), for Linux Plan - leave as default. For Sku and size - leave as default.

*Create SQL Database*
1. Using Azure Database for MySQL servers (Recommended)
* Navigate to Azure Database for MySQL servers (https://portal.azure.com/#blade/HubsExtension/BrowseResource/resourceType/Microsoft.DBforMySQL%2Fservers) and click "+ Add" button.
* Choose between Single Server and Flexible server (Preview) - if you are testing OR services and on a limited budget, we recommened you use select Flexible server. For production and high workload environment, go with Single server.
* On Flexible server tab, click "Create"
* Fill out subscription details, resource group, server name, region and workload type. On "Compute + Storage", you can leave it as default, or click on configure server. Fill out the configuration for your optimal workload. Lastly fill out the username and password. Note these somewhere as you will use them to connect the application to the database.
* Next, click the "Next: Networking". Here, make sure to check the box "Allow public access from any Azure service within Azure to this server" to allow App Service to connect to the database. If you need to connect to the database remotely from another computer, under "Firewall rules", add the computers IP address.
* Lastly click "Review + Create". It will take sometime to provision the database server. Once the provisioning is done, note the database host (something like <YOUR_SERVER_NAME>.mysql.database.azure.com)
* Its important to note, the provisioning does not automatically create a database. To create a database, connect to the server (you can do this from App Service console). To connect to the server from App Service console, go to the console and 
```bash
   mysql --host=<YOUR_SERVER_NAME>.mysql.database.azure.com --user=<YOUR_USERNAME_NAME> -p
```
You will be prompted to type your password. Provide the password you used when creating the server. Once connected to server, you can now create a database by using
```bash
   create database <DATABASE_NAME>
```
Note somewhere the host (<YOUR_SERVER_NAME>.mysql.database.azure.com), database name (create in the command above), username and password (both supplied when creating the server). 

2. Using Azure SQL Databases
* Navigate to SQL Databases (https://portal.azure.com/#blade/HubsExtension/BrowseResource/resourceType/Microsoft.Sql%2Fservers%2Fdatabases) and click "+ Add" button.
* Fill out the details and click "Review + create" button. Make sure to select the subscription you created ealier and the resource group. Provide the database name, for Server, click create new and fill out the pop out form with server name, server admin login (this will be the database username), password and location (Its advisable to pick the same location as your App service). Save these details somewhere since you will need them to connect the application to the database. For Want to use SQL elastic pool? - select No. For Compute + storage - leave as default or configure based on your needs.

*Deploying the application*
* Navigate to the App Service we created in step one
* On the left side menu, under "Deployment", click Deployment Center. For this deployment, we shall FTP manual code upload. If you have a paid plan, you can link your Github/Bitbucket account and create CI/CD pipeline to automatically deploy code from your repo to the app service. Obtain FTP credentials and Fire up FTP service like FileZilla. 
* Upload the codebase. By default, the code base will be deployed to ```/home/site/wwwroot``` directory

*Final touches*
* On the left side menu of the App Service, navigate to "SSH" under Development Tools. This will open a web terminal. Navigate to web root:

```bash
   cd site/wwwroot
   ```
* Install composer (https://getcomposer.org/download/)
```bash
   php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
   php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
   php composer-setup.php
   php -r "unlink('composer-setup.php');"
   ```
* Install Laravel dependencies
```bash
   composer install
   ```
* Create .env file
```bash
   mv .env.example .env
   ```
* Generate application key
```bash
   php artisan key:generate
   ```

* Replace database variables as below. Make sure to change variables under <> to reflect your details (details from creating SQL database step)
(Use these if you went with Azure Database for MySQL)
```bash
   DB_CONNECTION=mysql
   DB_HOST=<YOUR_SERVER_NAME>.mysql.database.azure.com
   DB_PORT=3306
   DB_DATABASE=<YOUR_DATABASE_NAME>
   DB_USERNAME=<YOUR_DATABASE_USERNAME>
   DB_PASSWORD=<YOUR_DATABASE_PASSWORD>
   ```

(Use these if you went with Azure SQL Databases)
```bash
   DB_CONNECTION=sqlsrv
   DB_HOST="tcp:<YOUR_DATABASE_NAME>.database.windows.net,1433"
   DB_URL="sqlsrv:server = tcp:<YOUR_DATABASE_NAME>.database.windows.net,1433; Database = <YOUR_DATABASE_NAME>"
   DB_PORT=3306
   DB_DATABASE=<YOUR_DATABASE_NAME>
   DB_USERNAME=<YOUR_DATABASE_USERNAME>@<YOUR_DATABASE_NAME>.database.windows.net
   DB_PASSWORD="<YOUR_DATABASE_PASSWORD>"
   ```

* Load the database dump
```bash
   php artisan db:seed --class=SqlDumpSeeder
   ```

Finally you can access your application at https://<YOUR_APP_SERVICE_NAME>.azurewebsites.net/
