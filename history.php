<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php include_once('common/header.php'); ?>
    <title>History London Stage Database Project</title>
</head>

<body id="history">
<?php include_once('common/nav.php'); ?>
<div id="main" class="main grid-container">
    <div class="grid-x">
        <div class="small-12 page-heading">
            <h1>History</h1>
        </div>
        <div class="small-12 medium-4 large-3 history-nav" id="historyNav">
            <nav class="show-for-small-only history-mobile-nav sticky-container" id="mobileNav" data-sticky-container>
                <div class data-sticky data-anchor="mobileNav" data-sticky-on="small">
                    <ul class="menu" data-magellan>
                        <li><a href="#curation">1960-8: Curation</a></li>
                        <li><a href="#digitization">1970-83: Digitization</a></li>
                        <li><a href="#recovery">2013-17: Recovery</a></li>
                        <li><a href="#remediation">2018-24: Remediation and Migration</a></li>
                        <li><a href="#extension">2024-Present: Extension</a></li>
                    </ul>
                </div>
            </nav>
            <nav class="sticky-container show-for-medium" data-sticky-container>
                <div class data-sticky data-anchor="historyNav" data-sticky-on="medium">
                    <h2>On This Page</h2>
                    <ul class="vertical menu" data-magellan>
                        <li><a href="#curation">1960-8: Curation</a></li>
                        <li><a href="#digitization">1970-83: Digitization</a></li>
                        <li><a href="#recovery">2013-17: Recovery</a></li>
                        <li><a href="#remediation">2018-24: Remediation and Migration</a></li>
                        <li><a href="#extension">2024-Present: Extension</a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <div id="top" class="small-12 medium-8 large-9 history-content">
            <div class="grid-x history-section">
                <div id="curation" class="small-12" data-magellan-target="curation">
                    <img alt="image of title page of The London Stage reference books"
                         style="float:right;padding-left:25px;width:225px;height:306.31px;"
                         src="images/timeline-image3.jpeg">
                    <h2>1960-8: Curation</h2>
                    <p>In the middle of the twentieth century, a team of theater historians created a calendar of
                        performances based on playbills and newspaper notices used to advertise performances,
                        as well as theater reviews, published gossip, playhouse records, and the diaries of people who
                        lived at the time. The result was <a href="https://catalog.hathitrust.org/Record/000200105"><i>The
                                London Stage, 1660-1800: A Calendar of Plays, Entertainments & Afterpieces, Together
                                with Casts, Box-Receipts and Contemporary Comment. Compiled from the Playbills,
                                Newspapers and Theatrical Diaries of the Period</i> (Southern Illinois University Press,
                            1960-1968)</a>.
                        This 8,000-page, eleven-book reference work was understood immediately as essential to
                        scholarly research and teaching about the period. It was also frustratingly difficult to use for
                        any kind of systematic inquiry.</p>
                </div>
            </div>
            <div class="grid-x history-section">
                <div id="digitization" class="small-12" data-magellan-target="digitization">
                    <hr/>
                    <img alt="newspaper photograph of editors in a classroom at work on the London State Information Bank. Caption states: Photos by Nancy. Editors MARC WEINBERGER, Ben Schneider, Joe Jacobs, can be found in MH-427 at almost any hour of the day or night."
                         style="float:left;padding-right:25px;width:275px;height:225px;"
                         src="images/timeline-image2.jpeg">
                    <h2>1970-83: Digitization</h2>
                    <p>In the 1970s, the editors of <i>The London Stage</i>
                        commissioned a computerized database of the information in their reference book. The <i>London
                            Stage Information Bank</i>, as it was then known, was created over the course of a
                        decade with the support from the National Endowment for the Humanities, the American Council
                        of Learned Societies, the American Philosophical Society, the Andrew Mellon Foundation,
                        the Billy Rose Foundation, and others. Regrettably, it fell into technological obsolescence
                        after only a few years, and it was long thought irretrievably lost. The only surviving
                        artifact of the project that remained in circulation was the <a
                                href="https://catalog.hathitrust.org/Record/000299859"><i>Index to the London
                                Stage</i></a>,
                        which was shelved alongside the original reference books in many research libraries.</p>
                </div>
            </div>
            <div class="grid-x history-section">
                <div id="recovery" class="small-12" data-magellan-target="recovery">
                    <hr/>
                    <img alt="Headline LU to house The London Stage data bank with accompanying photograph, March 1971, page 8."
                         style="float:right;padding-left:25px;width:300px;height:233.5px;"
                         src="images/timeline-image1.jpeg">
                    <h2>2013-17: Recovery</h2>
                    <p>In 2013, Mattie Burkert began investigating the history of the <i>Information Bank</i>,
                        drawing on the archives at Lawrence University, where the original project was housed.
                        She also got in touch with the people involved in the <i>Information Bank</i> project,
                        including
                        developers and research assistants who had helped to build it. The story she uncovered,
                        and the origins of her efforts to recover the lost database, are detailed in the essay <a
                                href="http://www.digitalhumanities.org/dhq/vol/11/3/000321/000321.html">"Recovering the
                            London Stage Information Bank: Lessons from an Early Humanities Computing Project" (<i>Digital
                                Humanities Quarterly</i> 11.3 [2017])</a>.</p>
                </div>
            </div>
            <div class="grid-x history-section">
                <div id="remediation" class="small-12" data-magellan-target="remediation">
                    <hr/>
                    <img alt="Headline LU to house The London Stage data bank with accompanying photograph, March 1971, page 8."
                         style="float:left;padding-right:25px;width:225px;height:310px;"
                         src="images/lsdb-neh-2019.jpg">
                    <h2>2018-24: Remediation and Migration</h2>
                    <p>From 2018 to 2019, with the support of <a href="/about.php#Funding">the National Endowment for
                            the Humanities and other funders</a>, Burkert and a <a href="/team.php#PastMembers">team
                            of researchers, developers, and advisors</a> worked to salvage the damaged data and code
                        from the <i>Information Bank</i> and to transform it into a modern relational database.</p>
                    <p>In 2020, Burkert moved to the University of Oregon and worked with developers there to
                        migrate the site to UO servers; the following spring, the team launched a major update with
                        improvements to the security and efficiency of the site, with a particular focus on the speed
                        and accuracy of searches. Users can use the <a href="/">keyword</a> or <a href="/search.php">advanced
                            search</a> pages to seek information about specific actors, theaters, play titles,
                        playwrights, etc., or visit the <a href="/legacy-search.php">legacy search</a> page
                        to reproduce queries run before May 2021. In addition, those who wish to download part or all of
                        the data and conduct exploratory analyses can do so using the freely available
                        assets (programs, data files, and documentation) in the team’s <a
                                href="https://github.com/LondonStageDB">GitHub repository</a>.</p>
                </div>
            </div>
            <div class="grid-x history-section">
                <div id="extension" class="small-12" data-magellan-target="extension">
                    <hr/>
                    <img
                            alt="University of Oregon logo in black"
                            style="float:right;padding-left:25px;width:225px;height:200px;"
                            src="images/UOSignature-STK-BLK.png">
                    <h2>2024 - Present: Extension</h2>
                    <p>These open access and open source values distinguish the <i>London Stage Database</i> from
                        related resources, such as the subscription-based <i>Eighteenth-Century Drama</i>
                        portal developed by publisher Adam Matthew. Furthermore, the media archaeological nature of
                        our project informs our team's commitment to transparency about our sources, our decisions,
                        and the limitations of our work. Like any resource of its kind, the <i>London Stage
                            Database</i> offers a useful starting point for research and teaching, but the data should
                        not be taken as a full, complete, or accurate picture of performance in London over a 140-year
                        period. Instead, we insist that it be understood as a representation of a particular
                        set of archival documents, transformed many times over by collectors of theater ephemera,
                        archivists, curators, editors, scholars, and developers.</p>
                    <p> To read more about our approach to the data in this project, visit the <a
                                href="about-data.php">About the Data</a> page.
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>
<?php include_once('common/footer.php'); ?>
</body>

</html>
