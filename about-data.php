<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php include_once('common/header.php'); ?>
    <title>About the Data</title>
</head>

<body id="about-data">
<?php include_once('common/nav.php'); ?>
<div id="main" class="main grid-container">
    <div class="grid-x about-data-wrap">
        <div class="small-12 page-heading">
            <h1>About the Data</h1>
        </div>
        <div method="post" class="small-12 medium-11 large-9 cell grid-x contact-form">
            <h2>Limitations</h2>
            <p>Like any resource of its kind, the <em>London Stage Database</em> offers a useful starting point for
                research and teaching, but the data should not be taken as a full, complete, or accurate picture of
                performance in London over a 140-year period. Instead, we insist that it be understood as a
                representation of a particular set of archival documents, transformed many times over by collectors of
                theater ephemera, archivists, curators, editors, scholars, and developers.</p>
            <p>Our collective knowledge of theater in the period is hampered by gaps in the documentary record; for
                example, Judith Milhous and Robert Hume <a
                        href="http://www.personal.psu.edu/hb1/London%20Stage%202001/preface.pdf" title="‌">have
                    calculated that</a> the information available for the years 1660-1700—before newspapers began
                printing daily advertisements for the major theaters—represents perhaps 7% of the performances that
                actually took place in London. The <em>London Stage Database</em> inherits not only the limitations of
                the archives on which the <em>London Stage</em> reference books were based, but all of the choices made
                (sometimes silently) by the editors of those books. For instance, the editors chose to represent the
                1695 premiere of William Congreve’s <em>Love for Love</em> as twelve separate events, because they were
                able to date those performances. Yet the first season in which George Farquhar’s <em>The Constant
                    Couple</em> was performed (1699-1700) includes only four records of performance for that play;
                although archival evidence suggests it may have been performed as many as fifty times that season, only
                these four can be even loosely dated. This kind of discrepancy poses obvious challenges to anyone hoping
                to gain quantitative insights into London theatrical culture before 1700. Even after 1700, the editors
                of <em>The London Stage</em> record manuscript notations on playbills, probably made by audience
                members, that contradict the cast lists printed in the daily papers, and many scholars have uncovered
                additional gaps and inconsistencies in the data.</p>
            <p>The <em>London Stage Database</em> also inherits the quirks of the damaged and incomplete data that
                Burkert was able to recover from the Lawrence University archives. The files associated with the <em>Information
                    Bank</em> experienced significant bit rot and are characterized by numerous gaps and errors that
                cannot be fully explained. Large sections of the data are missing from the recovered files, including
                most or all of the performances thought to have taken place between September 1733 and September 1736;
                between June and September 1770; between September 1781 and September 1786; and between October 1793 and
                September 1794. To approximate the missing data, Burkert and Advisory Board member Lauren Liebe
                hand-cleaned textual data (created using Optical Character Recognition, or OCR) from the <a
                        href="https://catalog.hathitrust.org/Record/000200105" title="‌">HathiTrust ebooks of <em>The
                        London Stage</em> </a>and added tags that paralleled those in the <em>Information Bank</em>
                files, allowing both types of data to be parsed together. Furthermore, in the damaged files recovered
                from the <em>Information Bank</em> project, all the performance dates are misrepresented as a series of
                special characters, like unprintable words in a comic book (e.g. “?!*&amp;%”). Advisory Board member
                Derek Miller discovered that this problem resulted from a systematic shift in the underlying hexadecimal
                code. When Burkert was unable to recover the original values forensically, Miller wrote a program to
                correct the problem algorithmically.</p>
            <p>In a variety of ways, then, the recovered data is riddled with errors and inconsistencies that the <em>London
                    Stage Database</em> team has addressed to the best of our abilities, but at the necessary cost of
                fidelity to the 1970s project. The team has also added new features and functionality not present in the
                <em>Information Bank</em>, such as tables linking abbreviated theater codes from the recovered data to
                the actual names of the theaters, as well as information about the known or assumed authors of
                particular plays and entertainments (data collected painstakingly by Research Assistant Emma Hallock).
                In doing so, our work has no doubt introduced new forms of error and ambiguity.</p>
            <p>The user interface is designed to make the rich history of this data, as well as its many limitations,
                intuitively clear to those who interact with the site. The “Toggle Sphinx Query” button at the top of
                the search results page allows users to see exactly how their search results were translated into SQL
                queries and relayed via PHP to our server (for, as <a href="https://nyupress.org/9781479837243/algorithms-of-oppression/" title="‌">Safiya Noble
                    reminds us</a>, search algorithms are never intellectually or ideologically neutral). The image
                carousel on each event page makes it possible to view the reference book pages from which the data is
                taken, alongside the roughest form of the data (recovered from the archives at Lawrence or copied from
                the OCR’d pages of <em>The London Stage</em>) and the data as it looks after being run through our
                cleaning and parsing programs. The “Related Works” display that appears next to information about a
                particular performance challenges the desire to know exactly which play or entertainment was staged—our
                way of acknowledging the eighteenth century’s rich culture of revival and adaptation. Many different
                performance pieces went by the same name throughout the century, and it is not always clear which one
                was performed on a given evening; see, for example, <a
                        href="https://digitalcommons.usu.edu/english_facpub/795/" title="‌">Vareschi and Burkert’s
                    discussion</a> of the many different versions of <em>Oroonoko</em> that coexisted in the 1760s,
                -70s, and -80s.</p>
            <p>We hope that visitors to the site will find this frank acknowledgment and foregrounding of the dataset’s
                history and limitations refreshing rather than frustrating. As <a
                        href="http://www.digitalhumanities.org/dhq/vol/5/1/000091/000091.html" title="‌">Johanna Drucker
                    argues</a>, a better word than “data” (which means “given”) might be “capta,” a term reflecting the
                way that all structured information about the world is captured by particular people at particular
                places and times. The <em>London Stage Database</em> participates in a long history of capturing partial
                histories of performance. This does not mean it should not be as accurate and useful as possible, and we
                encourage you to <a href="https://londonstagedatabase.uoregon.edu/about.php#Contact" title="‌">be in
                    touch</a> about errors or bugs you discover. At the same time, we are committed to making visible
                the ways in which perceived “errors” may in fact be the necessary and unavoidable consequence of this
                information’s long history of transmission and transformation, something to recognize and investigate
                rather than to paper over. More broadly, we hope that interacting with the <em>London Stage
                    Database</em> will inspire users to approach more critically all of the data with which they
                interact on a daily basis.
            </p>
        </div>
    </div>
</div>
<?php include_once('common/footer.php'); ?>
</body>

</html>
