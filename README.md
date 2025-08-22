# London Stage Database Website

This repository includes all the files needed to replicate the [**London Stage Database website**]( https://londonstagedatabase.uoregon.edu) on your own server.

## Installation Requirements

To deploy a copy of the London Stage Database website, you will need a Linux web server 
with MySQL, PHP, and the Sphinx engine. 

### Clone the Project Repo

``` bash
git clone https://github.com/LondonStageDB/website.git
```

### Import the Database into MySQL

Download a zipped version of the SQL database and save it to the `website` folder.

You can either download it from the [London Stage Database website](https://londonstagedatabase.uoregon.edu/data.php) 
or from [GitHub](https://github.com/LondonStageDB/data/blob/main/London.sql.zip) by clicking **View Raw**.

Extract the `London.sql.zip` folder.
``` bash
# This will extract a file called London.sql in the current directory
unzip London.sql.zip
```

Import `London.sql` into MySQL.
```bash
# Replace <user> with your MySQL username.
# After hitting enter, it will ask for your MySQL password
mysql -u <user> -p London < London.sql
```

### Sphinx Installation

There are two ways to install and use the Sphinx engine.

- Directly install the Sphinx engine on a server.
  Please follow the official instructions on the [Sphinx Search Engine website](http://sphinxsearch.com/docs/current.html#installing-debian).
- Use our Dockerfile to run the Sphinx container easily.
  The Dockerfile in this compose file is hosted at: [https://hub.docker.com/r/casit/sphinxsearch](https://hub.docker.com/r/casit/sphinxsearch).

#### Files

In the `/sphinx` directory of the repo you will find the files listed below. After installing Sphinx, copy the files to the locations indicated to set up Sphinx.

##### `en.pak`

Copy this file to the Sphinx installation directory.

When it has been placed, be sure to update the `lemmatizer_base` in the **common** section of the `sphinx.conf` file (not yet copied). 
The setting should be the path of the containing folder of the file.

##### `stopwords.txt`

Copy this file to the Sphinx installation directory.

When it has been placed, be sure to update the setting `stopwords` in all 3 **index** sections of the `sphinx.conf` file (not yet copied). 
The setting should be the full path of the file.

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
### (optional) Google reCAPTCHA v3

To prevent bots which do not respect robots.txt and protect DDOS attackig on downloading, you can set up **Google reCAPTCHA v3** for your site.
 - First, follow this guide to create your reCAPTCHA in Google Cloud platform: [reCAPTCHA v3 Guides](https://developers.google.com/recaptcha/docs/v3).
 - Then, provide site key and secret key in the `db.php` file.

### Create the `db.php` File

In the `/includes` folder, create a file named `db.php`. Use the code below as a template for the file.

The parameters for the Sphinx engine will depend on whether Sphinx was installed locally or if it is running from a Docker container.

For both the MySql and Sphinx `define()` statements, populate the host, port, user, 
password, and database configuration details.

``` php
<?php

  error_reporting(E_ERROR);

  define("DB_HOST", "host.example.com");
  define("DB_NAME", "London");
  define("DB_USER", "londonstagedbuser");
  define("DB_PASS", "areallysecurepassword1A!");

  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  define("SPHINX_HOST", "localhost");
  define("SPHINX_NAME", "");
  define("SPHINX_USER", "");
  define("SPHINX_PASS", "");
  define("SPHINX_PORT", "9306");

  $sphinx_conn = new mysqli(SPHINX_HOST, SPHINX_USER, SPHINX_PASS, SPHINX_NAME, SPHINX_PORT);

  define("GOOGLE_RECAPTCHA_SITE_KEY", "");
  define("GOOGLE_RECAPTCHA_SECRET_KEY", "");

?>
```

The site should now be installed and configured. 

#### Deploying the (Legacy) London Stage Database

If you would like to deploy the legacy version of the London Stage Database,
which has an alternative search feature that does not require Sphinx to be installed,
a [archived release bundled with installation instructions](https://github.com/LondonStageDB/website/releases/tag/v2.1) is available for download.

As [legacy search was deprecated for performance and security reasons]
(https://blogs.uoregon.edu/londonstage/2025/05/07/legacy/) we only
recommend this option for users who want to precisely replicate older search behavior.

## Code Structure

``` bash
- /common
        Contains header, footer, and nav include files


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

```
