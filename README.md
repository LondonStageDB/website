# London Stage Database Website


This repository includes all the files needed to replicate the London Stage Database Website on your server.


The live website can be viewed here: http://londonstagedatabase.usu.edu


## Setup


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


You'll need to create a file in the /includes folder called db.php and paste in the following contents (replacing the user/pass with your own MySQL authentication):


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
        Database config file (Not included in repo. You'll need to create your own - see above)


- /PDFs
        Files and scripts used in the process of reading the dates from and splitting up the 
        PDFs. These files are not required to run the website and can be deleted if desired. 
```
