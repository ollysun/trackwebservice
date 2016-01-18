TNT Service
==========

### Install Composer
You can use the links below to get [Composer](https://getcomposer.org) installed locally
- [Installing Composer](https://getcomposer.org/doc/00-intro.md)
- [Installing Composer on OSX](http://www.abeautifulsite.net/installing-composer-on-os-x/)

Run the following command in tnt-service project folder

`composer install`

### Install Phalcon
See install instructions here [here](https://docs.phalconphp.com/en/latest/reference/install.html)


### Install Redis
*Windows*
The Redis project does not officially support Windows. However, the Microsoft Open Tech group develops and maintains this Windows port targeting Win64.
Download the latest redis binary from [here](https://github.com/MSOpenTech/redis/releases)

*Debian Linux*

```bash
sudo apt-get update
sudo apt-get upgrade 
sudo apt-get install redis-server
```

*Mac*

```bash
brew install redis
```

### Install Beanstalkd
See how to install [here](http://kr.github.io/beanstalkd/download.html)

### Install WkHtmlToPdf 
See installation [here](http://wkhtmltopdf.org/downloads.html)
For ubuntu server see [here](http://askubuntu.com/questions/556667/how-to-install-wkhtmltopdf-0-12-1-on-ubuntu-server)

### Setup Virtual Host
*Windows*
[Link 1](http://foundationphp.com/tutorials/apache_vhosts.php)
[Link 2](https://www.kristengrote.com/blog/articles/how-to-set-up-virtual-hosts-using-wamp)

*Mac*
[Link 1](http://coolestguidesontheplanet.com/set-virtual-hosts-apache-mac-osx-10-9-mavericks-osx-10-8-mountain-lion/)
[Link 2](http://coolestguidesontheplanet.com/set-virtual-hosts-apache-mac-osx-10-10-yosemite/)

*Debian Linux*
[Link 1](https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts)
[Link 2](http://www.unixmen.com/setup-apache-virtual-hosts-on-ubuntu-15-04/)


**Sample Virtual Host Config for Apache**
```apache
<VirtualHost *:80>
    ServerAdmin yemi@cottacush.com
    DocumentRoot "<WebServer Directory>/tnt-service/public"
    ServerName local.courierplus.tntservice
    ServerAlias local.courierplus.tntservice.com
    SetEnv AWS_KEY *************
    SetEnv AWS_SECRET **********
    SetEnv BEANSTALKD_HOST 127.0.0.1
    SetEnv BEANSTALKD_PORT 11300
    ErrorLog "/var/log/apache2/local.courierplus.tntservice.error"
    CustomLog "/var/log/apache2/local.courierplus.tntservice.access.log" common
    <Directory "<WebServer Directory>/tnt-service/public">
        AllowOverride All
    </Directory>
</VirtualHost>
```
**Modify Hosts File**

Add the following line to your hosts file 

`127.0.0.1   local.courierplus.tntservice`


**Restart your WebServer**


### Setup Database

**Create Database**

Create `tnt` database

**Run Migration Commands**
`php vendor/bin/phinx migrate`

### Workers
- Parcel Creation Worker
This worker executes bulk parcel creation jobs.

Start by running the following command from the project root:  

`BEANSTALKD_HOST=<host> BEANSTALKD_PORT=<port> TNT_DB_HOST=<db_host>TNT_DB_USERNAME=<db_user> TNT_DB_PASSWORD=<db_password> TNT_DBNAME=<db_name> nohup php app/cli.php worker start ParcelCreationWorker &`

Stop by running the following command from the project root:  

`BEANSTALKD_HOST=<host> BEANSTALKD_PORT=<port> TNT_DB_HOST=<db_host>TNT_DB_USERNAME=<db_user> TNT_DB_PASSWORD=<db_password> TNT_DBNAME=<db_name> php app/cli.php worker stop ParcelCreationWorker`

- Bulk Waybill Printing Worker 
This worker executes bulk waybill printing jobs. The jobs generate pdf waybills for multiple shipments, uploads to S3 and then sends the link to the user's email.

Start by running the following command from the project root:  

`APPLICATION_ENV=<local|staging|production> AWS_KEY=<aws_key> AWS_SECRET='<aws_secret>' BEANSTALKD_HOST=<host> BEANSTALKD_PORT=<port> TNT_DB_HOST=<db_host>TNT_DB_USERNAME=<db_user> TNT_DB_PASSWORD=<db_password> TNT_DBNAME=<db_name> nohup php app/cli.php worker start WaybillPrintingWorker &`

Stop by running the following command from the project root:  

`BEANSTALKD_HOST=<host> BEANSTALKD_PORT=<port> TNT_DB_HOST=<db_host>TNT_DB_USERNAME=<db_user> TNT_DB_PASSWORD=<db_password> TNT_DBNAME=<db_name> php app/cli.php worker stop WaybillPrintingWorker`

Contributors
------------
Adegoke Obasa <goke@cottacush.com>
Adeyemi Olaoye <yemi@cottacush.com>
Akintewe Rotimi <akintewe.rotimi@gmail.com>
Boyewa Akindolani <boye@cottacush.com>
Olawale Lawal <wale@cottacush.com>
Rahman Shitu <rahman@cottacush.com>


