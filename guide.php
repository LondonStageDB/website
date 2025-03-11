<!doctype html>
<html class="no-js" lang="en">

<head>
  <?php include_once('common/header.php'); ?>
  <title>How to Use The London Stage Database Project</title>
</head>

<body id="guide">
  <?php include_once('common/nav.php'); ?>
  <div id="main" class="main grid-container">
    <div class="grid-x guide-wrap">
      <div class="small-12 page-heading">
        <h1>User Guide</h1>
      </div>
      <div class="small-12 medium-4 large-3 guide-nav" id="guideNav">
        <nav class="show-for-small-only guide-mobile-nav sticky-container" id="mobileNav" data-sticky-container>
          <div class data-sticky data-anchor="mobileNav" data-sticky-on="small">
            <ul class="menu" data-magellan>
            <li><a href="#Search">Search</a>
            <li><a href="#Filters">Filters</a>
            <li><a href="#Queries">Queries</a>
            <li><a href="#Export">Export and Visualization</a>
            </ul>
          </div>
        </nav>
        <nav class="sticky-container guide-nav-sticky show-for-medium" data-sticky-container>
          <div data-sticky data-anchor="guideNav" data-sticky-on="medium">
            <h2>On This Page</h2>
            <ul class="vertical menu" data-magellan>
            <li><a href="#Search">Search</a>
            <li><a href="#Filters">Filters</a>
            <li><a href="#Queries">Queries</a>
            <li><a href="#Export">Export and Visualization</a>
            </ul>
          </div>
        </nav>
      </div>
      <div id="top" class="small-12 medium-8 large-9 guide-content">
        <div class="grid-x guide-section">
          <div class="small-12">
            <p>This page offers a quick guide to interacting with and understanding the information presented in the <i>London Stage Database</i>. For a fuller account of the data's history and limitations, as well as the project as a whole, visit our <a href="/about.php">About</a> page.</p>
          </div>
        </div>
        <div class="grid-x guide-section">
          <div id="Search" class="small-12" data-magellan-target="Search">
            <h2>Search</h2>
            <p>Beginning in May 2021, the <i>London Stage Database</i> is optimized using an open-source full-text search engine called <a href="http://sphinxsearch.com/">Sphinx</a>. (From July 2019 to May 2021, searches were conducted entirely using the database programming language SQL (Structured Query Language); that earier search ecosystem is preserved under "Advanced Search (Legacy).") This section of the user guide offers a few tips for making the most of your searches.</p>
            <ul>
              <li>Search results are never case-sensitive in the <i>London Stage Database</i>. A keyword search for "dryden", "Dryden", or "DRYDEN" will return the same results. Special characters, such as hyphens and apostrophes, are ignored, in order to accommodate the inconsistent punctuation of entries in <i>The London Stage, 1660-1800</i> and the <i>London Stage Information Bank</i>.</li>
              <li>To search for an exact word or phrase, you can place it in quotation marks, "like this." If you enter a multi-word search term without quotation marks, our algorithm will return entries that include any of the words in your phrase. If you enter "Marriage a la Mode" with quotation marks, you will only get hits for that play, but they will include entries in which the title is spelled "Marriage a la Mode" as well as entries in which the title is spelled "Marriage a-la-Mode." If you enter the same term without quotation marks, you will also get hits for performances of <i>The Man of Mode</i>, for example, because the two titles share the word "Mode."  Search results can be filtered by relevance on the results page, if desired, to prioritize more exact matches with the terms you entered.</li>
              <li>Boolean searching is possible for actors and for roles. To search for two or more actors who appear together in a single event, click the "+" button by the actor search box, and select the "AND" operation from the drop-down menu. This search will return events in which both actors appear together in the same play, as well as events in which Actor A appears in one performance (for example, the <b>mainpiece</b>) and Actor B appears in another (for example, the <b>afterpiece</b>). To search for events containing performances featuring either Actor A or Actor B, select the "OR" operation. Note that entering the words "AND" or "OR" into any of the search boxes will not trigger a boolean search.</li>
            </ul>
          </div>
        </div>
        <!-- End Search -->
        <div class="grid-x guide-section">
          <div id="Filters" class="small-12" data-magellan-target="Filters">
            <h2>Filters</h2>
            <p>On the Results page, you have the option to apply a number of filters, which correspond to the options on the Advanced Search page. These filters can help you narrow down your results. For example, if you search for all performances of "The Beggar's Opera," you can then use the date filter to limit your results to performances within a particular season or year. Entering dates into the search filters will trigger a new search that retains your original query for performances of "The Beggar's Opera;" it will return the same results as if you had searched for the title and dates all at once from the Advanced Search page. If, however, you enter new terms into a filter that corresponds to one of your previous search fields, you will overwrite your old query. For example, if you search for performances of "The Beggar's Opera" from the Advanced Search page, but then you filter by Performance Title = "Three Hours after Marriage," you will get the same results as if you had simply searched for "Three Hours after Marriage" from the Advanced Search page. Note that filters applied to the results of legacy searches will keep the user within the legacy search ecosystem, while filters applied to results arrived at from the main keyword or advanced search pages will use the faster Sphinx search engine.</p>
          </div>
        </div>
        <!-- End Filters -->
        <div class="grid-x guide-section">
          <div id="Queries" class="small-12" data-magellan-target="Queries">
            <h2>Queries</h2>
            <p>At the top left of the search results page appears a link that reads "Toggle Sphinx Query." Click this link to view the way that your search was translated into SphinxQL (Sphinx's Query Language, similar to SQL or Structured Query Language) in order to generate the results that you see. Relational databases like the <i>London Stage Database</i> are organized as a series of <b>tables</b> (imagine Excel spreadsheets) with <b>fields</b> (imagine the column labels within those spreadsheets). For example, the <i>London Stage Database</i> has a table called "Events," and within that table, it has a field called "EventDate" that holds the date for each event. When you perform a legacy search, your parameters are transformed into a series of commands that create links and interactions among our different tables (Events, Performances, Theatre, etc.). When you perform a search from the main keyword or advanced search pages, which use the Sphinx full text search server, your terms are instead searched against an index of the same relational database, allowing for much faster results.</p>
          </div>
        </div>
        <!-- End Queries -->
        <div class="grid-x guide-section">
          <div id="Export" class="small-12" data-magellan-target="Export">
            <h2>Export and Visualization</h2>
            <p>Near the top of the search results page, you have the option to export your results in a variety of formats:</p>
            <ul>
              <li><b>CSV</b>: <b>C</b>omma <b>S</b>eparated <b>V</b>alues, a tabular data file format in which values are delimited using the comma character
              <li><b>XML</b>: e<b>X</b>tensible <b>M</b>arkup <b>L</b>anguage, a markup language similar to HTML. Data is represented as text wrapped within tags
              <li><b>JSON</b>: <b>J</b>ava<b>S</b>cript <b>O</b>bject <b>N</b>otation: a data-interchange format that stores data objects as text
            </ul>
            <p>You can use any of these file formats to analyze and visualize the results that interest you. All of these file types are designed to be human-readable and can be opened in a text editor, such as Notepad or TextEdit. XML and JSON are best equipped for storing relational data of the kind used in the <i>London Stage Database</i>, so exporting to and using one of these file formats will allow you to retain, access, and work with the most information from your results. CSV files are easier for users with less technical training to work with, as they can be opened and manipulated in spreadsheet software like Google Sheets or Microsoft Excel. However, CSV files are tabular rather than relational, so they do not represent the complexity of the data objects and relations as fully.</p>
            <p>Data for individual events can be exported from the relevant Event page.</p>
          </div>
        </div>
        <!-- End Export -->
      </div>
    </div>
  </div>
  <?php include_once('common/footer.php'); ?>
</body>

</html>
