<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php include_once('common/header.php'); ?>
    <title>Data London Stage Database Project</title>
</head>

<body id="data">
<?php include_once('common/nav.php'); ?>
<div id="main" class="main grid-container">
    <div class="grid-x data-wrap">
        <div class="small-12 page-heading">
            <h1>Data Downloads</h1>
        </div>
        <div method="post" class="small-12 medium-11 large-9 cell grid-x data-form">
            <p>You can use any of these file formats to analyze and visualize the results that interest you. All of
                these file types are designed to be human-readable and can be opened in a text editor, such as Notepad
                or TextEdit. XML and JSON are best equipped for storing relational data of the kind used in the <i>London
                    Stage Database</i>, so exporting to and using one of these file formats will allow you to retain,
                access, and work with the most information from your results. CSV files are easier for users with less
                technical training to work with, as they can be opened and manipulated in spreadsheet software like
                Google Sheets or Microsoft Excel. However, CSV files are tabular rather than relational, so they do not
                represent the complexity of the data objects and relations as fully.</p>
        </div>
        <div method="post" class="small-12 medium-11 large-9 cell grid-x data-form">
            <h2>Export the Full Dataset</h2>
            <ul class="no-bullet">
                <li><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/London.sql.zip">SQL:</a> <b>S</b>tructured <b>Q</b>uery <b>L</b>anguage</li>
                <li><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/LondonStageFull.json.zip">JSON:</a> <b>J</b>ava<b>S</b>cript <b>O</b>bject <b>N</b>otation:
                    a data-interchange format that stores data objects as text
                </li>
                <li><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/LondonStageFull.xml.zip">XML:</a> e<b>X</b>tensible <b>M</b>arkup <b>L</b>anguage, a
                    markup language similar to HTML. Data is represented as text wrapped within tags
                </li>
                <li><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/LondonStageFull.csv.zip">CSV:</a> <b>C</b>omma <b>S</b>eparated <b>V</b>alues, a
                    tabular data file format in which values are delimited using the comma character</a></li>
            </ul>
            <p>Data for individual events can be exported from the relevant Event page.</p>
        </div>
    </div>
</div>
<?php include_once('common/footer.php'); ?>
</body>

</html>