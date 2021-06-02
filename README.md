# London Stage Database Website

This repository includes all the files needed to replicate the London Stage Database website on your server.

You can choose either **MySQL** or **Sphinx + MySQL** as the site search engine.

The live website can be viewed here: https://londonstagedatabase.uoregon.edu

*Instructions are for a Linux server*

## Installation

You will need a web server with MySQL and PHP installed.

For faster search performance, you can install the Sphinx engine.

### Clone the Project Repo

``` bash
git clone https://github.com/LondonStageDB/website.git
```

If you choose to use Sphinx, check-out the git tag `sphinx`, `v2.0`, or any tag later than `v2.0`.

If not using Sphinx, checkout the `v1.0` git tag.

### Import the Database into MySQL

Included with the repo is a compressed export of the database called London.tgz.

``` bash
# this will extract a file called London.sql
tar -xzf London.tgz

# Replace <user> with your MySQL username.
# After hitting enter, it will ask for your MySQL password
mysql -u <user> -p London < London.sql
```

### Sphinx (optional)

If you do not wish to use Sphinx, skip down to the section "Create the db.php file".

#### Installation

There are two ways to install and use the Sphinx engine.

- Directly install the Sphinx engine on a server.
  Please follow the official instructions on the [Sphinx Search Engine website](http://sphinxsearch.com/docs/current.html#installing-debian).
- Use our Dockerfile to run the Sphinx container easily.
  The Dockerfile in this compose file is hosted at: [https://hub.docker.com/r/casit/sphinxsearch](https://hub.docker.com/r/casit/sphinxsearch).

#### Files

In the `/sphinx` directory of the repo you will find the files listed below. After installing Sphinx, copy the files to the locations indicated to set up Sphinx.

##### `en.pak`

Copy this file to somewhere in the Sphinx installation directory structure.

When it has been placed, be sure to update the `lemmatizer_base` in the **common** section of the `sphinx.conf` file (not yet copied). The setting should be the path of the containing folder of the file.

##### `stopwords.txt`

Copy this file to somewhere in the Sphinx installation directory structure.

When it has been placed, be sure to update the setting `stopwords` in all 3 **index** sections of the `sphinx.conf` file (not yet copied). The setting should be the full path of the file.

##### `sphinx.example.conf`

Copy this file to somewhere in the Sphinx installation directory structure. *Rename it `sphinx.conf`*.

Read the commented lines in the file to help while updating the settings to match your set up.

Make sure the path for the `lemmatizer_base` and file location for `stopwords` are updated to match the locations of the files in the sections above.

#### Run the Indexer and Start Sphinx

Once the configuration files are placed and updated, Sphinx needs to index the database.

Update the `sphinx.conf` location when executing the commands below.

```bash
indexer --all --rotate --config /path/to/sphinx/conf/sphinx.conf
```

Now start serving requests.

```bash
searchd --config /path/to/sphinx/conf/sphinx.conf
```

### Create the `db.php` File

In the `/includes` folder, create a file named `db.php`. Use the code below as a template for the file.

The Sphinx configuration is only needed if the Sphinx search engine was installed. The configuration for Sphinx may not need to change if Sphinx is installed on the web server.

For both the db (MySQL) and Sphinx `define()` statements, replace the host, port, user, password, and database configuration details.

``` php
<?php

  error_reporting(E_ERROR);

  define("DB_HOST", "host.example.com");
  define("DB_NAME", "London");
  define("DB_USER", "londonstagedbuser");
  define("DB_PASS", "areallysecurepassword1A!");

  // Only keep the following lines if using Sphinx.

  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  define("SPHINX_HOST", "localhost");
  define("SPHINX_NAME", "");
  define("SPHINX_USER", "");
  define("SPHINX_PASS", "");
  define("SPHINX_PORT", "9306");

  $sphinx_conn = new mysqli(SPHINX_HOST, SPHINX_USER, SPHINX_PASS, SPHINX_NAME, SPHINX_PORT);
?>
```

Now the site is installed and configured.

## Files

A few quick explanations for some files/folders in the project.

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


- /includes/Paginator.class.php and /includes/SphinxPaginator.class.php
        Executes the search SQL query and stores the result data. Also generates
        pagination info based on the results.


- /includes/db.php
        Database config file (Not included in repo. You will need to create your own - see above)


- /PDFs
        Files and scripts used in the process of reading the dates from and splitting up the 
        PDFs. These files are not required to run the website and can be deleted if desired. 
```
