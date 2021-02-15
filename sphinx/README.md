# Full-Text Search Enhanced by Sphinx


This project can utilize the [Sphinx](http://sphinxsearch.com/about/sphinx/) open source, full text search server to enhance the search feature performance and improve relevance ranking of results. 

The live website employs the enhanced search because of the significant speed improvements for searches.


## Setup


### Requirements
Sphinx can be installed on the same web server that serves your MySQL and PHP already. 


### Installation
Go to the [downloads page of the Sphinx website](http://sphinxsearch.com/downloads/) to download the latest installer for your server.

A package manager based installation may be available for your server, as well.

### Configuration

In this directory is `example_sphinx.conf` which defines the data source, index, and sphinx searchd settings. It can be copied and used as the configuration file for your installation of Sphinx. Be sure to update the mysql host, user, password, and port as needed in the file.

### Indexing

After installation and setting up the configuration file, Sphinx must build an index of the fields on which full text searches will be performed. After indexing, the search provider should be launched.

In the command below, update the location of the `.conf` file to point to your server's location.
``` bash
# Create initial index.
indexer --all --rotate --config /opt/sphinx/conf/sphinx.conf

# Launch searchd.
searchd --nodetach --config /opt/sphinx/conf/sphinx.conf
```

### Update `db.php`

Edit the file `/includes/db.php` to add the configuration for the Sphinx database connection below the other file contents.

**Note**: it is likely that the database, username, and password for Sphinx should all be left blank.
```` PHP
define("SPHINX_HOST", "localhost"); // Update to the correct server name.
define("SPHINX_NAME", ""); // The Database Name, likely blank.
define("SPHINX_USER", ""); // The Sphinx username if one exists. Probably blank.
define("SPHINX_PASS", ""); // The Sphinx password if one exists. Probably blank.
define("SPHINX_PORT", "9306"); // The port Sphinx searchd is listening on.

$sphinx_conn = new mysqli(SPHINX_HOST, SPHINX_USER, SPHINX_PASS, SPHINX_NAME, SPHINX_PORT);
````

### File Renames

The site was built without Sphinx search and can still be run out-of-the-box without it.

Once Sphinx is set up, the files listed below should be renamed to utilize Sphinx.

Files with these names already exist, so replace them while renaming.

```` bash
- /sphinx-results.php
  => results.php

- /sphinx-search-home.php
  => index.php
````
