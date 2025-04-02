<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php include_once('common/header.php'); ?>
    <title>Search Tips London Stage Database Project</title>
</head>

<body id="tips">
<?php include_once('common/nav.php'); ?>
<div id="main" class="main grid-container">
    <div class="grid-x tips-wrap">
        <div class="small-12 page-heading">
            <h1>Search Tips</h1>
        </div>
        <div class="small-12 medium-4 large-3 tips-nav" id="tipsNav">
            <nav class="show-for-small-only tips-mobile-nav sticky-container" id="mobileNav" data-sticky-container>
                <div class data-sticky data-anchor="mobileNav" data-sticky-on="small">
                    <ul class="menu" data-magellan>
                        <li><a href="#Search">Keyword Search</a>
                        <li><a href="#Filters">Advanced Filters</a>
                    </ul>
                </div>
            </nav>
            <nav class="sticky-container tips-nav-sticky show-for-medium" data-sticky-container>
                <div data-sticky data-anchor="tipsNav" data-sticky-on="medium">
                    <h2>On This Page</h2>
                    <ul class="vertical menu" data-magellan>
                        <li><a href="#Search">Keyword Search</a>
                        <li><a href="#Filters">Advanced Filters</a>
                    </ul>
                </div>
            </nav>
        </div>
        <div id="top" class="small-12 medium-8 large-9 tips-content">
            <div class="grid-x tips-section">
                <p>Beginning in May 2021, the <i>London Stage Database</i> is optimized using an open-source
                    full-text search engine called <a href="http://sphinxsearch.com/">Sphinx</a>. (From July 2019 to
                    May 2021, searches were conducted entirely using the database programming language SQL
                    (Structured Query Language); that earlier search ecosystem is preserved under "Advanced Search
                    (Legacy).")</p>
                <p>At the top left of the search results page appears a link that reads "Toggle Sphinx Query." Click
                    this link to view the way that your search was translated into SphinxQL (Sphinx's Query
                    Language, similar to SQL or Structured Query Language) in order to generate the results that you
                    see. Relational databases like the <i>London Stage Database</i> are organized as a series of <b>tables</b>
                    (imagine Excel spreadsheets) with <b>fields</b> (imagine the column labels within those
                    spreadsheets). For example, the <i>London Stage Database</i> has a table called "Events," and
                    within that table, it has a field called "EventDate" that holds the date for each event.</p>
            </div>
            <div class="grid-x tips-section">
                <div id="Search" class="small-12" data-magellan-target="Search">
                    <h2>Keyword Search</h2>
                    <p>Search results are never case-sensitive in the <i>London Stage Database</i>. A keyword
                        search for "dryden", "Dryden", or "DRYDEN" will return the same results. Special characters,
                        such as hyphens and apostrophes, are ignored, in order to accommodate the inconsistent
                        punctuation of entries in <i>The London Stage, 1660-1800</i> and the <i>London Stage
                            Information Bank</i>.</p>
                    <p>To search for an exact word or phrase, you can place it in quotation marks, "like this." If
                        you enter a multi-word search term without quotation marks, our algorithm will return
                        entries that include any of the words in your phrase. If you enter "Marriage a la Mode" with
                        quotation marks, you will only get hits for that play, but they will include entries in
                        which the title is spelled "Marriage a la Mode" as well as entries in which the title is
                        spelled "Marriage a-la-Mode." If you enter the same term without quotation marks, you will
                        also get hits for performances of <i>The Man of Mode</i>, for example, because the two
                        titles share the word "Mode." Search results can be filtered by relevance on the results
                        page, if desired, to prioritize more exact matches with the terms you entered.
                    </p>
                </div>
            </div>
            <div class="grid-x tips-section">
                <div id="Filters" class="small-12" data-magellan-target="Filters">
                    <h2>Advanced Search and Filters</h2>
                    <p>Boolean searching is possible for actors and for roles. To search for two or more actors who
                        appear together in a single event, click the "+" button by the actor search box, and select
                        the "AND" operation from the drop-down menu. This search will return events in which both
                        actors appear together in the same play, as well as events in which Actor A appears in one
                        performance (for example, the <a href='/glossary.php#mainpiece'>mainpiece</a>) and Actor B
                        appears in another (for example, the <a href='/glossary.php#afterpiece'>afterpiece</a>). To
                        search for events containing performances featuring either Actor A or Actor B, select the
                        "OR" operation. Note that entering the words "AND" or "OR" into any of the search boxes will
                        not trigger a boolean search.</p>
                    <p>On the Results page, you have the option to apply a number of filters, which correspond to the
                        options on the Advanced Search page. These filters can help you narrow down your results. For
                        example, if you search for all performances of "The Beggar's Opera," you can then use the date
                        filter to limit your results to performances within a particular season or year. Entering dates
                        into the search filters will trigger a new search that retains your original query for
                        performances of "The Beggar's Opera;" it will return the same results as if you had searched for
                        the title and dates all at once from the Advanced Search page. If, however, you enter new terms
                        into a filter that corresponds to one of your previous search fields, you will overwrite your
                        old query. For example, if you search for performances of "The Beggar's Opera" from the Advanced
                        Search page, but then you filter by Performance Title = "Three Hours after Marriage," you will
                        get the same results as if you had simply searched for "Three Hours after Marriage" from the
                        Advanced Search page.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('common/footer.php'); ?>
</body>

</html>
