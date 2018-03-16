[![in2it PHP Bootcamp](https://www.in2it.be/wp-content/uploads/2017/01/in2it-php-bootcamp.png)](https://www.in2it.be/training-courses/php-bootcamp/)

# Zend Framework CRM

This is a CRM based on [Zend Framework 3]. Detailed component information on all Zend Framework components can be found at [Zend Framework GitHub Pages](https://zendframework.github.io), [Zend Framework Compoents](https://docs.zendframework.com/) or in the IRC channel #zftalk on [irc.freenode.net](http://irc.freenode.net).

## Getting started

In this exercise we're using the **[Integration-Manager Workflow](https://git-scm.com/book/en/v2/Distributed-Git-Distributed-Workflows)** to work on the project and create pull requests.

[![Integration-Manager Workflow](https://git-scm.com/book/en/v2/images/integration-manager.png)](https://git-scm.com/book/en/v2/images/integration-manager.png) 

### 1. Fork this repo

Because we love your participation, please fork this project to your own account. If you don't know how to accomplish this, please read [Fork a Repo - GitHub](https://help.github.com/articles/fork-a-repo/).

### 2. Clone your fork to your local machine

Before starting development, you need to clone this project onto your local machine. It should be something like:

```
git clone git@github.com:<username>/<repo>.git
```

Where `<username>` is your GitHub username and `<repo>` the name of the project.

Once cloned, you can `cd` into the project's root directory.

```
cd <repo>/
```

### 3. Add the blessed repository as a remote

Before starting development, you need to clone this project onto your local machine. It should be something like:
You should add the blessed repository from which you forked to your personal account as a remote. As a convention we use the term 'upstream'.
It should be something like:

```
git remote add upstream https://github.com/in2it-training/<repo>.git
```

Where `<repo>` the name of the project.

To check if this worked you can use the command:

```
git remote -v
```

If you see a remote to your own repo and one to the blessed repo you're in business.

### 4. Execute composer install

This project uses [Composer](https://getcomposer.org) packages, so please install the required packages.

```
composer install
```

### 5. Instantiate your database

In the directory `data/db` you will find several `.sql` files you need to execute on your local MySQL database.

1. Start off with `database.mysql.sql` to create the database and user that we're using in this application
2. Run `zfcrm.ddl.sql` to create the table schemas
3. Run `zfcrm.data.sql` to provision the table schemas with default data
4. Optionally execute `sampleDataGenerator.php` to provision dummy data to use in the assignments

```
mysql -h127.0.0.1 -uroot -p < data/db/database.mysql.sql 
mysql -h127.0.0.1 -uroot -p phpbootcamp_crm < data/db/zfcrm.ddl.sql 
mysql -h127.0.0.1 -uroot -p phpbootcamp_crm < data/db/zfcrm.data.sql
mysql -h127.0.0.1 -uroot -p phpbootcamp_crm < data/db/country.sql

php data/db/sampleDataGenerator.php
```

### 6. Configure your application

The easiest way to get started is to copy `db.global.php` to `db.local.php` and `linkedin.global.php` to `linkedin.local.php` in directory `config/autoload`. Please request your teacher for access keys for LinkedIn.

```
cp config/autoload/db.global.php config/autoload/db.local.php
cp config/autoload/linkedin.global.php config/autoload/linkedin.local.php
```

### 7. Launch the build-in PHP web server

Fire up the build-in webserver in PHP

```
php -S localhost:8000 -t public/ public/index.php
```

Point your browser to [localhost:8000](http://localhost:8000) to see the web application.

## PHPBootcamp

This training project is provided by [in2it](https://www.in2it.be) and is part of the [PHPBootcamp training course](https://www.in2it.be/training-courses/php-bootcamp/). This project MIT licensed, please see our [LICENCE](LICENSE.md) for details.

[Zend Framework 3]: https://framework.zend.com
