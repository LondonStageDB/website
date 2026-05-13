<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php include_once('common/header.php'); ?>
    <title>Data London Stage Database Project</title>
</head>

<body id="data">
<?php include_once('common/nav.php'); ?>
<main class="main grid-container">
    <div class="grid-x data-wrap">
        <div class="small-12 page-heading">
            <h1>Data Downloads</h1>
        </div>
        <div class="small-12 medium-11 large-9 cell grid-x data-form">
            <h2>Export the Full Database</h2>
            <p>You can use any of these file formats to analyze and visualize the results that interest you. All of
                these file types are designed to be human-readable and can be opened in a text editor, such as Notepad
                or TextEdit. XML and JSON are best equipped for storing relational data of the kind used in the <i>London
                    Stage Database</i>, so exporting to and using one of these file formats will allow you to retain,
                access, and work with the most information from your results. CSV files are easier for users with less
                technical training to work with, as they can be opened and manipulated in spreadsheet software like
                Google Sheets or Microsoft Excel. However, CSV files are tabular rather than relational, so they do not
                represent the complexity of the data objects and relations as fully.</p>
            <h3>Data Formats</h3>
            <ul class="no-bullet">
                <li><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/London.sql.zip">SQL:</a> <b>S</b>tructured <b>Q</b>uery <b>L</b>anguage:
                    the database in its original format</li>
                <li><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/LondonStageFull.json.zip">JSON:</a> <b>J</b>ava<b>S</b>cript <b>O</b>bject <b>N</b>otation:
                    a data-interchange format that stores data objects as text
                </li>
                <li><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/LondonStageFull.xml.zip">XML:</a> e<b>X</b>tensible <b>M</b>arkup <b>L</b>anguage, a
                    markup language similar to HTML with a nested hierarchy
                </li>
                <li><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/LondonStageFull.csv.zip">CSV:</a> <b>C</b>omma <b>S</b>eparated <b>V</b>alues, a
                    tabular data file format in which values are delimited using the comma character</a></li>
            </ul>
        </div>
        <div class="small-12 medium-11 large-9 cell grid-x data-form">
            <h2>TCP Drama Corpus</h2>
            <p>The <a href=https://londonstage.blob.core.windows.net/lsdb-files/downloads/LSDB_TCP_Corpus-1.0.zip>
                    TCP Drama Corpus</a> is a subset of 935 dramatic texts from the XML files produced by
                <a href="https://www.textpartnership.net/pages/about-the-tcp.html">Text Creation Partnership</a>
                correlated with one or more performances on the eighteenth-century London stage.
                These are the "Print Witnesses" on the lists of Related Works. The corpus is divided into two
                corpora: texts that contain a single dramatic work—Plays—and texts that comprise collections of
                texts—Collections.
            </p>
            <p> The XML files from the TCP are in their original form. The metadata spreadsheets that accompany each
                corpus are populated by information extracted from the TEI headers of each file. We have not corrected
                the metadata from its original form, though we have ensured that each file has an
                <a href="https://datb.cerl.org/estc">ESTC identifier</a> for interoperability.</p>
        </div>
    </div>
</main>
<?php include_once('common/footer.php'); ?>
</body>

</html>