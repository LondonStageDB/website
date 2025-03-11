<!doctype html>
<html class="no-js" lang="en">

<head>
  <?php include_once('common/header.php'); ?>
  <title>About London Stage Database Project</title>
</head>

<body id="about">
  <?php include_once('common/nav.php'); ?>
  <div id="main" class="main grid-container">
    <div class="grid-x">
      <div class="small-12 page-heading">
        <h1>About</h1>
      </div>
      <div class="small-12 medium-4 large-3 about-nav" id="aboutNav">
        <nav class="show-for-small-only about-mobile-nav sticky-container" id="mobileNav" data-sticky-container>
          <div class data-sticky data-anchor="mobileNav" data-sticky-on="small">
            <ul class="menu" data-magellan>
              <li><a href="#Project">About</a></li>
              <li><a href="#Funding">Funding</a></li>
              <li><a href="#Team">Team</a></li>
              <li><a href="#Contact">Contact</a>
            </ul>
          </div>
        </nav>
        <nav class="sticky-container show-for-medium" data-sticky-container>
          <div class data-sticky data-anchor="aboutNav" data-sticky-on="medium">
            <h2>On This Page</h2>
            <ul class="vertical menu" data-magellan>
              <li><a href="#Project">Overview of the Project</a></li>
              <li><a href="#Funding">Funding</a></li>
              <li><a href="#Contact">Contact Us</a>
            </ul>
          </div>
        </nav>
      </div>
      <div id="top" class="small-12 medium-8 large-9 about-content">
        <div class="grid-x about-section">
          <div class="small-12">
            <p>This page offers a detailed account of the history, institutional framework, theoretical commitments, and limitations of the <i>London Stage Database</i> project. For a quick guide to interacting with the database, refer instead to the <a href="/guide.php">User Guide</a>.</p>
            ARCHIVE PAGE & ADD LINK
          </div>
        </div>
        <div class="grid-x about-section">
          <div id="Project" class="small-12" data-magellan-target="Project">
            <h2>Overview</h2>
            <p>The <i>London Stage Database</i> is the latest in a long line of projects that aim to capture and present the rich array of information available on the theatrical culture of
              London, from the reopening of the public playhouses following the English civil wars in 1660 to the end of the eighteenth century. On a given night, in each of the city’s playhouses,
              hundreds or even thousands of spectators gathered to experience richly varied performance events that included not only plays, but prologues and epilogues, short afterpieces and
              farces, pantomimes, instrumental music, singing, and dancing. These events, taken together, provide a wealth of information about the rhythms of public life and the texture of
              popular culture in long-eighteenth-century London.</p>
            <p>Our collective knowledge of theater in the period is hampered by gaps in the documentary record; for example, Judith Milhous and Robert Hume
              <a href="http://www.personal.psu.edu/hb1/London%20Stage%202001/preface.pdf">have calculated that</a> the information available for the years 1660-1700—before newspapers began
              printing daily advertisements for the major theaters—represents perhaps 7% of the performances that actually took place in London. The <i>London Stage Database</i> inherits not
              only the limitations of the archives on which the <i>London Stage</i> reference books were based, but all of the choices made (sometimes silently) by the editors of those books.
              For instance, the editors chose to represent the 1695 premiere of William Congreve’s <i>Love for Love</i> as twelve separate events, because they were able to date those performances.
              Yet the first season in which George Farquhar’s <i>The Constant Couple</i> was performed (1699-1700) includes only four records of performance for that play; although archival
              evidence suggests it may have been performed as many as fifty times that season, only these four can be even loosely dated. This kind of discrepancy poses obvious challenges to
              anyone hoping to gain quantitative insights into London theatrical culture before 1700. Even after 1700, the editors of <i>The London Stage</i> record manuscript notations on playbills,
              probably made by audience members, that contradict the cast lists printed in the daily papers, and many scholars have uncovered additional gaps and inconsistencies in the data.</p>
            <p>The <i>London Stage Database</i> also inherits the quirks of the damaged and incomplete data that Burkert was able to recover from the Lawrence University archives. The files
              associated with the <i>Information Bank</i> experienced significant bit rot and are characterized by numerous gaps and errors that cannot be fully explained. Large sections of the
              data are missing from the recovered files, including most or all of the performances thought to have taken place between September 1733 and September 1736; between June and September
              1770; between September 1781 and September 1786; and between October 1793 and September 1794. To approximate the missing data, Burkert and Advisory Board member Lauren Liebe hand-cleaned
              textual data (created using Optical Character Recognition, or OCR) from the <a href="https://catalog.hathitrust.org/Record/000200105">HathiTrust ebooks of <i>The London Stage</i>
              </a> and added tags that paralleled those in the <i>Information Bank</i> files, allowing both types of data to be parsed together. Furthermore, in the damaged files recovered from
              the <i>Information Bank</i> project, all the performance dates are misrepresented as a series of special characters, like unprintable words in a comic book (e.g. "?!*&%"). Advisory
              Board member Derek Miller discovered that this problem resulted from a systematic shift in the underlying hexadecimal code. When Burkert was unable to recover the original values
              forensically, Miller wrote a program to correct the problem algorithmically.</p>
            <p>In a variety of ways, then, the recovered data is riddled with errors and inconsistencies that the <i>London Stage Database</i> team has addressed to the best of our abilities,
              but at the necessary cost of fidelity to the 1970s project. The team has also added new features and functionality not present in the <i>Information Bank</i>, such as tables linking
              abbreviated theater codes from the recovered data to the actual names of the theaters, as well as information about the known or assumed authors of particular plays and entertainments
              (data collected painstakingly by Research Assistant Emma Hallock). In doing so, our work has no doubt introduced new forms of error and ambiguity.</p>
            <p>The user interface is designed to make the rich history of this data, as well as its many limitations, intuitively clear to those who interact with the site. The "Toggle Sphinx Query"
              button at the top of the search results page allows users to see exactly how their search results were translated into SQL queries and relayed via PHP to our server (for, as
              <a href="https://nyupress.org/9781479837243/algorithms-of-oppression/">Safiya Noble reminds us</a>, search algorithms are never intellectually or ideologically neutral). The image
              carousel on each event page makes it possible to view the reference book pages from which the data is taken, alongside the roughest form of the data (recovered from the archives at
              Lawrence or copied from the OCR’d pages of <i>The London Stage</i>) and the data as it looks after being run through our cleaning and parsing programs. The “Related Works” display
              that appears next to information about a particular performance challenges the desire to know exactly which play or entertainment was staged—our way of acknowledging the eighteenth
              century’s rich culture of revival and adaptation. Many different performance pieces went by the same name throughout the century, and it is not always clear which one was performed
              on a given evening; see, for example, <a href="https://digitalcommons.usu.edu/english_facpub/795/">Vareschi and Burkert’s discussion</a> of the many different versions of
              <i>Oroonoko</i> that coexisted in the 1760s, -70s, and -80s.</p>
            <p>We hope that visitors to the site will find this frank acknowledgment and foregrounding of the dataset’s history and limitations refreshing rather than frustrating. As
              <a href="http://www.digitalhumanities.org/dhq/vol/5/1/000091/000091.html">Johanna Drucker argues</a>, a better word than "data" (which means "given") might be "capta," a term reflecting
              the way that all structured information about the world is captured by particular people at particular places and times. The <i>London Stage Database</i> participates in a long history
              of capturing partial histories of performance. This does not mean it should not be as accurate and useful as possible, and we encourage you to <a href="#Contact">be in touch</a>
              about errors or bugs you discover. At the same time, we are committed to making visible the ways in which perceived "errors" may in fact be the necessary and unavoidable consequence
              of this information’s long history of transmission and transformation, something to recognize and investigate rather than to paper over. More broadly, we hope that interacting with
              the <i>London Stage Database</i> will inspire users to approach more critically all of the data with which they interact on a daily basis.</p>
          </div>
        </div>
        <!-- End Project -->
        <div class="grid-x about-section">
          <div id="Funding" class="small-12" data-magellan-target="Funding">
            <h2>Funding</h2>
            <p>This project is made possible by grants from the <a href="http://www.neh.gov/">NEH</a> <a href="https://www.neh.gov/divisions/odh">Office of Digital
              Humanities</a> (Awards <a href="https://apps.neh.gov/publicquery/AwardDetail.aspx?gn=HAA-258717-18">#HAA-258717-18</a> and <a href="https://www.neh.gov/sites/default/files/2024-08/NEH%20August%202024%20grants%20list%20state%20by%20state.pdf">#HAA-300527-24</a>). Any views, findings, conclusions, or recommendations expressed through this database or on this website do not necessarily represent those of the National Endowment for the Humanities.</p>
            <p>Additional funding comes from the <a href="https://research.uoregon.edu/">Office of the Vice President for Research and Innovation</a> at the University of Oregon, as well as <a href="https://is.uoregon.edu/">UO Information Services</a>, the <a href="https://cas.uoregon.edu/">College of Arts and Sciences</a>, <a href="https://library.uoregon.edu/">UO Libraries</a>, and the <a href="">Department of English</a>.</p>
			<p>Development work and hosting between 2018 and 2020 were supported by the <a href="http://english.usu.edu/">Department of English</a>, the <a href="https://chass.usu.edu/">College of Humanities and Social Sciences</a>, and the <a href="http://rgs.usu.edu/">Office of Research</a> at Utah State University.</p>
            <div class="logo-wrap grid-x">
              <div class="small-4 medium-6 large-3"><a href="http://www.neh.gov/"><img class="funding-logo" src="https://github.com/LondonStageDB/website/blob/main/images/national-endowment-vector.png?raw=true" alt="NEH Logo" /></a></div>
              <div class="small-4 medium-6 large-3"><a href="https://cas.uoregon.edu/"><img class="funding-logo" src="/images/UOregon-CAS-black.png" alt="University of Oregon, College of Arts and Sciences" /></a></div>
            </div>
            <div class="logo-wrap grid-x">
              <div class="small-4 medium-6 large-3"><a href="http://english.usu.edu/"><img class="funding-logo" src="/images/English-logo-bw.png" alt="USU English Logo" /></a></div>
              <div class="small-4 medium-6 large-3"><a href="https://chass.usu.edu/"><img class="funding-logo" src="/images/CHASS-logo-black.svg" alt="CHASS Logo" /></a></div>
              <div class="small-4 medium-6 large-3"><a href="http://rgs.usu.edu/"><img class="funding-logo" src="/images/ResearchBlackTower.png" alt="USU Research Logo" /></a></div>
            </div>
          </div>
        </div>
        <div class="grid-x about-section">
          <div id="Contact" class="small-12" data-magellan-target="Contact">
            <h2>Contact Us</h2>
            <p>We would love to hear from you! Feel free to <a href="mailto:londonstagedb@gmail.com">email us</a> your questions, comments, or bug reports. When reporting an issue with the site, please include as much information as possible about your operating system and web browser so that we can reproduce the problem.</p>
          </div>
        </div>
        <!-- End Contact -->
      </div>
    </div>
  </div>
  <?php include_once('common/footer.php'); ?>
</body>

</html>
