<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php include_once('common/header.php'); ?>
    <title>Data London Stage Database Project</title>
</head>

<body id="data">
<?php include_once('common/nav.php'); ?>
<main class="main grid-container">
    <div class="small-12 medium-11 large-9 cell grid-x data-form">
        <div class="small-12 page-heading">
            <h1>Data Downloads</h1>
        </div>
        <div class="grid-x data-section">
            <div id="full-database" class="small-12">
                <h2>Download the Full Database</h2>
            </div>
            <p>
                Users who are comfortable with SQL (Structured Query Language) can export the full relational database that powers this website. 
                A data dictionary and a diagram of our relational schema are <a href="https://github.com/LondonStageDB/data/tree/main/docs">available on GitHub</a>. 
            </p>
            <ul class="no-bullet">
                <li><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/London.sql.zip" class="button dwnld-btn">Download SQL</a></li>
            </ul>
        </div>
        <div class="grid-x data-section">
            <div id="event-records" class="small-12">
                <h2>Export Event Data</h2>
            </div>
            <p>
                All 52,000+ event records are available to download and analyze in several file formats. You can open these files in a text 
                editor, such as Notepad or TextEdit, or use more specialized software to analyze and visualize the results that interest you.
            </p>
            <div id="detailed-records" class="small-12">
                <h3>Detailed event records</h3>
            </div>
                <p>In creating these files, we tried to capture the most commonly requested information about LSDB events, 
                    including performance titles, cast lists, and connections to related dramatic works. 
                    These files represent the minimum reduction in complexity needed to present event records in two widely-used, human-readable formats:
                    JSON (Javascript Object Notation), a data-interchange format that stores data objects as text; and XML 
                    (eXtensible Markup Language), a hierarchical tagging language similar to HTML.</p>
                <ul class="no-bullet">
                    <li><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/LondonStageFull.json.zip" class="button dwnld-btn">Download JSON</a> 
                        </li>
                    <li><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/LondonStageFull.xml.zip" class="button dwnld-btn">
                         Download XML</a>
                    </li>
                </ul>
            <div id="simplified-records" class="small-12">
                <h3>Simplified event records</h3>
            </div>
                <p>CSV (Comma Separated Values) files are easy to work with in spreadsheet software like Google Sheets or Microsoft Excel. 
                Note the trade-off: this format further simplifies the data in order to present complex, relational information in a two-dimensional table.
                </p>
                <ul class="no-bullet">
                    <li><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/LondonStageFull.csv.zip" 
                    class="button dwnld-btn">Download CSV</a>
                    </li>
                </ul>
            </div>

        </div>
        <div class="small-12 medium-11 large-9 cell grid-x data-form">
        <div id="drama-corpus" class="small-12">    
            <h2>Drama Corpus</h2>
        </div>
            <p>The Drama Corpus is a subset of the TCP corpus produced by the 
                <a href="https://www.textpartnership.net/pages/about-the-tcp.html">Text Creation Partnership</a>. 
                Each of the 935 items in our Drama corpus is associated with one or more performances in the
                LSDB dataset, meaning it is listed as a "Print Witness" for one or more "Related Works."</p>
                <p><a href="https://blogs.uoregon.edu/londonstage/2025/09/22/new-feature-print-witnesses/">
                    Read more about Related Works and Print Witnesses on our blog.</a></p>
                <p>We have not modified the XML files; they are exact copies of those distributed
                    through the <a href="https://github.com/textcreationpartnership">TCP's GitHub</a>. 
                    We have simply made the files easier to work with as a coherent dataset in the following ways:</p>
                   <ul type="bullet">
                    <li>identified those texts in the larger TCP corpus that appear to be dramatic works relevant to the LSDB 
                        performance data</li>
                    <li>categorized each text as a single dramatic work or an anthology containing multiple dramatic works, 
                        and separated the corpus accordingly into two subcorpora: Plays and Collections</li>
                    <li>extracted cataloging information from the TEI headers of each file to create a metadata 
                        spreadsheet for each sub-corpus</li>
                    <li>ensured that the metadata spreadsheet includes an <a href="https://datb.cerl.org/estc">ESTC 
                        identifier</a> for each text to support interoperability</li>
                    </ul>
                    <p><a href="https://londonstage.blob.core.windows.net/lsdb-files/downloads/LSDB_TCP_Corpus-1.0.zip" class="button dwnld-btn">
                    Download the Drama Corpus</a></p>
        </div>
    </div>
</main>
<?php include_once('common/footer.php'); ?>
</body>

</html>