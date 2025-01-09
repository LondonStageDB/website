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
              <li><a href="#Images">Credits</a></li>
              <li><a href="#Contact">Contact</a>
            </ul>
          </div>
        </nav>
        <nav class="sticky-container show-for-medium" data-sticky-container>
          <div class data-sticky data-anchor="aboutNav" data-sticky-on="medium">
            <h2>On This Page</h2>
            <ul class="vertical menu" data-magellan>
              <li><a href="#Project">About the Project</a></li>
              <li><a href="#Funding">Funding</a></li>
              <li><a href="#Team">Team</a></li>
              <li><a href="#Images">Image Credits</a></li>
              <li><a href="#Contact">Contact Us</a>
            </ul>
          </div>
        </nav>
      </div>
      <div id="top" class="small-12 medium-8 large-9 about-content">
        <div class="grid-x about-section">
          <div class="small-12">
            <p>This page offers a detailed account of the history, institutional framework, theoretical commitments, and limitations of the <i>London Stage Database</i> project. For a quick guide to interacting with the database, refer instead to the <a href="/guide.php">User Guide</a>.</p>
          </div>
        </div>
        <div class="grid-x about-section">
          <div id="Project" class="small-12" data-magellan-target="Project">
            <h2>About the Project</h2>
            <p>The <i>London Stage Database</i> is the latest in a long line of projects that aim to capture and present the rich array of information available on the theatrical culture of
              London, from the reopening of the public playhouses following the English civil wars in 1660 to the end of the eighteenth century. On a given night, in each of the city’s playhouses,
              hundreds or even thousands of spectators gathered to experience richly varied performance events that included not only plays, but prologues and epilogues, short afterpieces and
              farces, pantomimes, instrumental music, singing, and dancing. These events, taken together, provide a wealth of information about the rhythms of public life and the texture of
              popular culture in long-eighteenth-century London.</p>
            <p>In the middle of the twentieth century, a team of theater historians created a calendar of performances based on playbills and newspaper notices used to advertise performances,
              as well as theater reviews, published gossip, playhouse records, and the diaries of people who lived at the time. The result was <a href="https://catalog.hathitrust.org/Record/000200105">
              <i>The London Stage, 1660-1800: A Calendar of Plays, Entertainments & Afterpieces, Together with Casts, Box-Receipts and Contemporary Comment. Compiled from the Playbills, Newspapers
              and Theatrical Diaries of the Period</i> (Southern Illinois University Press, 1960-1968)</a>. This 8,000-page, eleven-book reference work was understood immediately as essential to
              scholarly research and teaching about the period. It was also frustratingly difficult to use for any kind of systematic inquiry. In the 1970s, the editors of <i>The London Stage</i>
              commissioned a computerized database of the information in their reference book. The <i>London Stage Information Bank</i>, as it was then known, was created over the course of a
              decade with the support from the National Endowment for the Humanities, the American Council of Learned Societies, the American Philosophical Society, the Andrew Mellon Foundation,
              the Billy Rose Foundation, and others. Regrettably, it fell into technological obsolescence after only a few years, and it was long thought irretrievably lost. The only surviving
              artifact of the project that remained in circulation was the <a href="https://catalog.hathitrust.org/Record/000299859"><i>Index to the London Stage</i></a>, which was shelved alongside
              the original reference books in many research libraries.</p>
            <p>In 2013, Mattie Burkert began investigating the history of the <i>Information Bank</i>, drawing on the archives at Lawrence University, where the original project was housed.
              She also got in touch with the people involved in the <i>Information Bank</i> project, including developers and research assistants who had helped to build it. The story she uncovered,
              and the origins of her efforts to recover the lost database, are detailed in the essay <a href="http://www.digitalhumanities.org/dhq/vol/11/3/000321/000321.html">"Recovering the
              London Stage Information Bank: Lessons from an Early Humanities Computing Project" (<i>Digital Humanities Quarterly</i> 11.3 [2017])</a>.</p>
            <p>From 2018 to 2019, with the support of <a href="#Funding">the National Endowment for the Humanities and other funders</a>, Burkert and a <a href="#Team">team of researchers,
              developers, and advisors</a> worked to salvage the damaged data and code from the <i>Information Bank</i> and to transform it into a modern relational database. In 2020, Burkert
              moved to the University of Oregon and worked with developers there to migrate the site to UO servers; the following spring, the team launched a major update with improvements
              to the security and efficiency of the site, with a particular focus on the speed and accuracy of searches. Users can use the <a href="/">keyword</a> or <a href="/search.php">
              advanced search</a> pages to seek information about specific actors, theaters, play titles, playwrights, etc., or visit the <a href="/legacy-search.php">legacy search</a> page
              to reproduce queries run before May 2021. In addition, those who wish to download part or all of the data and conduct exploratory analyses can do so using the freely available
              assets (programs, data files, and documentation) in the team’s <a href="https://github.com/LondonStageDB">GitHub repository</a>.</p>
            <p>These open access and open source values distinguish the <i>London Stage Database</i> from related resources, such as the subscription-based <i>Eighteenth-Century Drama</i>
              portal developed by publisher Adam Matthew. Furthermore, the media archaeological nature of our project informs our team's commitment to transparency about our sources, our decisions,
              and the limitations of our work. Like any resource of its kind, the <i>London Stage Database</i> offers a useful starting point for research and teaching, but the data should not
              be taken as a full, complete, or accurate picture of performance in London over a 140-year period. Instead, we insist that it be understood as a representation of a particular
              set of archival documents, transformed many times over by collectors of theater ephemera, archivists, curators, editors, scholars, and developers.</p>
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
              <div class="small-4 medium-6 large-3"><a href="http://www.neh.gov/"><img class="funding-logo" src="/images/NEH_h-logo_01_fullcolor.svg" alt="NEH Logo" /></a></div>
              <div class="small-4 medium-6 large-3"><a href="https://cas.uoregon.edu/"><img class="funding-logo" src="/images/UOregon-CAS-black.png" alt="University of Oregon, College of Arts and Sciences" /></a></div>
            </div>
            <div class="logo-wrap grid-x">
              <div class="small-4 medium-6 large-3"><a href="http://english.usu.edu/"><img class="funding-logo" src="/images/English-logo-bw.png" alt="USU English Logo" /></a></div>
              <div class="small-4 medium-6 large-3"><a href="https://chass.usu.edu/"><img class="funding-logo" src="/images/CHASS-logo-black.svg" alt="CHASS Logo" /></a></div>
              <div class="small-4 medium-6 large-3"><a href="http://rgs.usu.edu/"><img class="funding-logo" src="/images/ResearchBlackTower.png" alt="USU Research Logo" /></a></div>
            </div>
          </div>
        </div>
        <!-- End Funding -->
        <div class="grid-x about-section">
          <div id="Team" class="small-12" data-magellan-target="Team">
            <h2>Team</h2>
            <p>The <i>London Stage Database</i>, like any project of this scope, is the work of numerous individuals across multiple disciplines who have contributed a range of skills and expertise.</p>
            <h3>Principal Investigator and Project Director:</h3>
            <ul>
              <li><a href="http://www.mattieburkert.com">Mattie Burkert</a>, Associate Professor, Department of English, University of Oregon</li>
            </ul>
            <h3>Informationists:</h3>
			<ul>
			  <li>Franny Gaede, Associate Librarian, University of Oregon</li>
			  <li>Kate Thornhill, Associate Librarian, University of Oregon</li>
			  <li>Erin Winter, Assistant Librarian, University of Oregon</li>
			</ul>
			<h3>Linked Open Data Consultant:</h3>
			<ul>
			  <li>Lauren Liebe, Assistant Professor of Game Design, Digital Media, Arts, and Technology, Penn State Eerie, the Behrend College</li>
			</ul>
			<h3>Research Assistant:</h3>
            <ul>
              <li>Michele Pflug, Doctoral Candidate, Department of History, University of Oregon</li>
			</ul>
			<h3>UO Information Services Development Team:</h3>
            <ul>
              <li>Jesse Sedwick, Web Applications Developer and Administrator</li>
			  <li>Tyfanie Wineriter, Software Solutions and Development Manager</li>
			  <li>Derek Wormdahl, Senior Director of Solutions Development and Data Services</li>
              <li>John Zhao, Analyst Programmer</li>
            </ul>
            <h3>Advisory Board:</h3>
            <ul>
              <li>Misty Anderson, Professor of English and James R. Cox Professor, College of Arts and Sciences, University of Tennessee</li>
              <li>Michael Gamer, Professor of English and Comparative Literature, Department of English, University of Pennsylvania</li>
			  <li>Michelle J. Holman, Principal Genealogist, Family History Gifts</li>
			  <li>Todd Hugie, Director of Library Information Technology, Merrill-Cazier Library, Utah State University</li>
			  <li>Lauren Liebe, Assistant Teaching Professor of Game Design, Digital Media, Arts, and Technology, Penn State Eerie, the Behrend College</li>
              <li>Atsede Makonnen, Assistant Professor, Department of English, Carnegie Mellon University</li>
			  <li>Derek Miller, Professor, Department of English, Harvard University</li>
			  <li>Chelsea Phillips, Associate Professor of Theatre and Associate Director for Villanova Theatre, Department of Theatre and Studio Art, Villanova University</li>
              <li>Jeffrey S. Ravel, Professor Emeritus, Department of History, Massachusetts Institute of Technology</li>
			  <li>Fiona Ritchie, Associate Professor of Drama and Theatre, Department of English, McGill University</li>
			  <li>Jeremy Singer-Vine, Data Editor, <i>New York Times</i></li>
              <li>Mark Vareschi, Associate Professor, Department of English, University of Wisconsin-Madison</li>
			  <li>Jane Wessel, Associate Professor, Department of English, U.S. Naval Academy</li>
			  <li>Kalle Westerling, Research Application Manager, Alan Turing Institute</li>
            </ul>
            <h3>Past Team Members and Contributors:</h3>
			<ul>  
			  <li><a href="https://gitlab.com/cacology/JunicodeRX">JunicodeRX</a> Font Designer: James P. Ascher, University of Virginia</li>
			  <li>Advisory Board Members: Scott Enderle, University of Pennsylvania Libraries; Doug Reside, New York Public Library</li>
			  <li>Research Assistant: Emma Hallock, Utah State University ('20)</li>
			  <li>Software Design and Development (UO): Shirley Galloway, Daniel Mundra, Cameron Seright, Caden Williams</li>
			  <li>Software Design and Development (USU): Todd Hugie and Dustin Olson, with assistance from Clint Gillespie, Joe Kaili, Garth Mikesell, and Sam Phelps</li>
			</ul>
            <p>Finally, this project would not be possible without the help and support of Susan Barribeau, Melissa Bowers, Annette Cottle, Will Daland, Katie Dana, Steven Dast, Erin Dix, Mara Fields, Kathy Furrer, Clint Gillespie, Gabriele Hayden, John Karczewski, Jack Keel, Holly Lakey, Cal Lee, Christina Lujin, Bronwen Maseman, Mike Murashige, Angela Moore-Swafford, Betty Rozum, Dorothea Salo, Ben Ross Schneider III, Nick Schneider, Cindy Serikaku, Carl Stahmer, Annie Strickland-Neilson, Brianna Uzuner, Steel Wagstaff, Mark Whalan, Kam Woods, David Yorgeson, Angela Zaytsev, and Irene Zimmerman.</p>
          </div>
        </div>
        <!-- End Team -->
        <div class="grid-x about-section">
          <div id="Images" class="small-12" data-magellan-target="Images">
            <h2>Image Credits</h2>
            <p>The image collage that is displayed on the landing page and in the background of the search, results, and events pages was created by Dustin Olson, and is covered by a <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/">CC-BY-NC-SA 4.0 license</a>.</p>
            <p>Clockwise from top left:</p>
            <ul>
              <li><a href="https://www.britishmuseum.org/collection/object/P_Ee-4-80">“Scene from <i>School for Scandal</i> being performed in Drury Lane Theatre, London,”</a> 1778, © The Trustees of the British Museum, CC-BY-NC-SA 4.0 license</li>
              <li><a href="https://collections.britishart.yale.edu/vufind/Record/3653228">William Blake, “<i>Beggar’s Opera</i>, Act III,”</a> c.1790, Yale Center for British Art, Paul Mellon Collection, Public Domain</li>
              <li><a href="https://collections.britishart.yale.edu/vufind/Record/1670672">Thomas Rowlandson, “An Audience Watching a Play at Drury Lane Theatre,”</a> c.1785, Yale Center for British Art, Paul Mellon Collection, Public Domain</li>
              <li><a href="https://www.digitens.org/en/media/341"> Playbill from Burney Collection, “‘Theatrical Register.’ A collection of playbills of London theatres, chiefly of Drury Lane, Covent Garden and the Haymarket,”</a>                1774-1777, British Library, Public Domain</li>
              <li><a href="https://digitalcollections.folger.edu/img21902">John Lodge, “Mr. Garrick delivering his Ode at Drury Lane Theatre on dedicating a building & erecting a statue to Shakespeare,”</a>                Folger Shakespeare Library, CC-BY-SA 4.0 license</li>
              <li><a href="https://www.britishmuseum.org/research/collection_online/collection_object_details/collection_image_gallery.aspx?assetId=1613044433&objectId=3411178&partId=1">“Riot at Covent Garden Theatre, in 1763, in consequence of the Managers refusing to admit half-price in the Opera of <i>Artaxerxes</i>,”</a>                1763, © The Trustees of the British Museum, CC-BY-NC-SA 4.0 License</li>
              <li><a href="https://www.bl.uk/collection-items/portrait-of-aphra-behn-by-sir-peter-lely">Peter Lely, “Aphra Behn,”</a> 1670, © Yale Center for British Art, Public Domain</li>
              <li><a href="https://www.bl.uk/collection-items/third-edition-of-the-beggars-opera-by-john-gay-1729">John Gay, <i>The beggar's opera. As it is acted at the Theatre-Royal in Lincolns-Inn Fields</i>,</a> 1729, British Library, Public Domain</li>
              <li><a href="https://collections.britishart.yale.edu/catalog/tms:40620">Edward Fisher, “Miss Farren in the Character of Hermione,”</a> 1781, Yale Center for British Art, Paul Mellon Collection, Public Domain</li>
              <li><a href="https://www.britishmuseum.org/research/collection_online/collection_object_details.aspx?assetId=290264001&objectId=752512&partId=1">“Scene One of <i>The Necromancer or Harlequin Dr Faustus</i> which opened at Lincoln's Inn Fields 20 December 1723,”</a>                1724, © The Trustees of the British Museum, CC-BY-NC-SA 4.0 license</li>
              <li><a href="https://www.britishmuseum.org/research/collection_online/collection_object_details.aspx?objectId=3553320&partId=1&searchText=theatre+ticket&images=true&from=ad&fromDate=1660&to=ad&toDate=1800&page=3">Thomas Bewick, “Border for theatre-ticket; concert for the benefit of Mr Evans, at the Theatre Royal in Haymarket, London, on 11 April 1777,”</a>                1777, © The Trustees of the British Museum, CC-BY-NC-SA 4.0 license</li>
            </ul>
          </div>
        </div>
        <!-- End Images -->
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
