# London Stage Database Website


This repository includes all the files needed to replicate the London Stage Database Website, both MySQL and Sphinx search, on your server.

You can choose either **MySQL** or **Sphinx** as the site search engine.

The live website can be viewed here: https://londonstagedatabase.uoregon.edu

## Site Setup with Sphinx

### Requirements
You will need a web server with MySQL, Sphinx and PHP already installed. 

### Installation
>*Instructions are for a Linux server*

#### Sphinx Installation
There are two ways to install Sphinx engine.
- Directly install the Sphinx engine on a server. Please follow the official instructions [here](http://sphinxsearch.com/docs/current.html#installing-debian).
- You can also use our Dockerfile to run the Sphinx container easily. The Dockerfile in this compose file is hosted at: [https://hub.docker.com/r/casit/sphinxsearch](https://hub.docker.com/r/casit/sphinxsearch).

#### Build-up Database
This step is identical to the MySQL search installation part in the **MySQL Search Setup** section below.

### Get started
Git check-out code in tags `v2.0` or `sphinx` or any tags later than `v2.0`. Then you will need to add the Sphinx connection into the db.php file in the `/includes` folder. Please paste the following contents (replacing the user/pass with your own Sphinx authentication):

``` php
<?php
  define("SPHINX_HOST", "localhost");
  define("SPHINX_NAME", "");
  define("SPHINX_USER", "");
  define("SPHINX_PASS", "");
  define("SPHINX_PORT", "9306");

  $sphinx_conn = new mysqli(SPHINX_HOST, SPHINX_USER, SPHINX_PASS, SPHINX_NAME, SPHINX_PORT);
?>
```
Now the site is running on the Sphinx engine.

##  Site Setup with MySQL


### Requirements
You'll need a web server with MySQL and PHP already installed. 


### Installation
>*Instructions are for a Linux server*


``` bash
# clone repo
git clone https://github.com/LondonStageDB/website.git
```


Included with the repo is a compressed export of the database called London.tgz. 
``` bash
# untar/zip the file
# this will extract a file called London.sql
tar -xzf London.tgz


# Import database
# replace <user> with your MySQL username
# after hitting enter, it will ask for your MySQL password
mysql -u <user> -p London < London.sql
```


### Getting started

Git check-out code in tags `v1.0`. Then you'll need to create a file in the /includes folder called db.php and paste in the following contents (replacing the user/pass with your own MySQL authentication):


``` php
<?php
  define("DB_HOST", "localhost");
  define("DB_NAME", "London");
  define("DB_USER", "yourUserName");
  define("DB_PASS", 'yourPassword');


  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
?>
```


  


### Files
A few quick explanations for some of the files/folders in the project.
``` bash
- /common
        Contains header, footer, and nav include files


- /images/pdfs
        Includes all the split up PDFs from each volume served on Event pages


- /get_[all]_[json/csv/xml].php files
        Used to generate the exported CSV/XML/JSON for search results or events


- /includes/[act/auth/perf/role].php files
        Used to generate auto-complete options for respective search fields


- /includes/functions.php
        Contains all functions used on the website


- /includes/Paginator.class.php
        Executes the search SQL query and stores the result data. Also generates
        pagination info based on the results.


- /includes/db.php
        Database config file (Not included in repo. You will need to create your own - see above)


- /PDFs
        Files and scripts used in the process of reading the dates from and splitting up the 
        PDFs. These files are not required to run the website and can be deleted if desired. 
```
