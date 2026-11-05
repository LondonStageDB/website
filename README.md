# London Stage Database Website

This repository includes all the files needed to replicate the [**London Stage Database website**]( https://londonstagedatabase.uoregon.edu) on your own server.

## Installation Requirements

To deploy a copy of the London Stage Database website, you will need a Linux web server 
with MySQL, PHP, and the Manticore Search engine. 

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

### Manticore Search Installation

The site previously used Sphinx 3.4.1. It now uses [Manticore Search](https://manticoresearch.com/),
the actively maintained successor, which speaks the same SphinxQL query language over the
MySQL protocol — so the PHP does not change, only the engine behind it.

There are two ways to install and use the Manticore engine.

- Directly install Manticore on a server. Please follow the official instructions on the
  [Manticore Search install page](https://manticoresearch.com/install/). On RHEL-family
  systems that is the two-step repo install:
  ```bash
  dnf install https://repo.manticoresearch.com/manticore-repo.noarch.rpm
  dnf install manticore manticore-language-packs
  ```
- Use the official Docker image, hosted at
  [https://hub.docker.com/r/manticoresearch/manticore](https://hub.docker.com/r/manticoresearch/manticore).

Note that Manticore's index files are not compatible with Sphinx 3.x index files. There is no
in-place conversion — the tables have to be built from scratch with `indexer`, which is what
the steps below do.

#### Files

In the `/manticore` directory of the repo you will find the files listed below. After installing
Manticore, copy the files to the locations indicated to set up Manticore.

##### Lemmatizer dictionaries

`morphology = lemmatize_en` needs the English lemmatizer dictionary. Install the
`manticore-language-packs` package, which puts the `.pak` files in Manticore's default
`lemmatizer_base` of `/usr/share/manticore`. Manticore ships its own dictionaries, so no
dictionary file needs to be copied out of this repo — do **not** reuse Sphinx's `en.pak`.

If you install the dictionaries somewhere else, update `lemmatizer_base` in the **common**
section of the `manticore.conf` file (not yet copied) to the path of the containing folder.

##### `stopwords.txt`

Copy this file to `/etc/manticoresearch/stopwords.txt`.

If you place it elsewhere, be sure to update the setting `stopwords` in the **table** sections
of the `manticore.conf` file (not yet copied). The setting should be the full path of the file.

##### `manticore.example.conf`

Copy this file to `/etc/manticoresearch/`. *Rename it `manticore.conf`*.

Read the commented lines in the file to help while updating the settings to match your set up.

Make sure the path for the `lemmatizer_base` and file location for `stopwords` are updated to match the locations of the files in the sections above.

#### Run the Indexer and Start Manticore

Once the configuration files are placed and updated, Manticore needs to index the database.

Update the `manticore.conf` location when executing the commands below.

```bash
indexer --all --rotate --config /etc/manticoresearch/manticore.conf
```

Now start serving requests.

```bash
searchd --config /etc/manticoresearch/manticore.conf
```
### (optional) Cloudflare Turnstile

High-volume bot/scraper traffic against the data-bearing endpoints (`sphinx-results.php`,
`event.php`, `search.php`, and the CSV/JSON/XML download scripts) can exhaust database
connections, because each of those endpoints opens DB connections and runs a query on every
request. To protect them, you can put a **Cloudflare Turnstile** gate in front of every
entry point that opens a DB connection.

When enabled, an unverified visitor is shown a one-time Turnstile challenge and the script
exits *before* any database connection is opened; bots that cannot solve the challenge
never reach the query. Once a visitor passes, a session flag lets all of their subsequent
searches, pagination, sorting, and downloads through untouched.

 - Create a Turnstile widget in the Cloudflare dashboard: [Turnstile Get Started](https://developers.cloudflare.com/turnstile/get-started/).
 - In the `/includes` folder, create a file named `turnstile_config.php` with your keys:

``` php
<?php
  define("TURNSTILE_SITE_KEY", "");     // Replace "" with your Cloudflare Turnstile site key in quotes.
  define("TURNSTILE_SECRET_KEY", "");   // Replace "" with your Cloudflare Turnstile secret key in quotes.
```

If the file is absent, or the keys are left empty, the gate does nothing and the site
behaves as before. Note: because search-engine crawlers cannot solve Turnstile, enabling this gate stops
the gated pages from being indexed by search engines.

### Create the `db.php` File

In the `/includes` folder, create a file named `db.php`. Use the code below as a template for the file.

The parameters for the search engine will depend on whether Manticore was installed locally or if it is running from a Docker container.

For both the MySql and Manticore `define()` statements, populate the host, port, user, 
password, and database configuration details.

`MANTICORE_PORT` must be Manticore's **SphinxQL/MySQL** listener, which is `9306` by default —
the same port Sphinx used. Manticore's other default listener, `9308`, is the HTTP/JSON API
and cannot be used here, because the site connects with PHP's `mysqli`.

``` php
<?php

  error_reporting(E_ERROR);

  define("DB_HOST", "host.example.com");
  define("DB_NAME", "London");
  define("DB_USER", "londonstagedbuser");
  define("DB_PASS", "areallysecurepassword1A!");

  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  // Manticore Search connection (SphinxQL over the MySQL protocol).
  define("MANTICORE_HOST", "localhost");
  define("MANTICORE_NAME", "");
  define("MANTICORE_USER", "");
  define("MANTICORE_PASS", "");
  define("MANTICORE_PORT", "9306");   // Manticore's SQL port, NOT the 9308 HTTP port.

  $manticore_conn = new mysqli(MANTICORE_HOST, MANTICORE_USER, MANTICORE_PASS, MANTICORE_NAME, MANTICORE_PORT);

?>
```

The site should now be installed and configured. 

#### Deploying the (Legacy) London Stage Database

If you would like to deploy the legacy version of the London Stage Database,
which has an alternative search feature that does not require Sphinx to be installed,
a [archived release bundled with installation instructions](https://github.com/LondonStageDB/website/releases/tag/v2.1) is available for download.

As [legacy search was deprecated for performance and security reasons](https://blogs.uoregon.edu/londonstage/2025/05/07/legacy/) we only
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


- /includes/turnstile_gate.php
        Cloudflare Turnstile gate. Included at the top of every entry point that
        opens a DB connection; rejects unverified visitors before any connection
        is opened. Reads its keys from turnstile_config.php.


- /includes/turnstile_config.php
        Cloudflare Turnstile keys (Not included in repo. You will need to create your own. Optional - see above)

```
