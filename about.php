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
        <!-- End Funding -->
        <div class="grid-x about-section">
          <div id="Team" class="small-12" data-magellan-target="Team">
            <h2>Team</h2>
<p class="has-line-data" data-line-start="2" data-line-end="3">The London Stage Database, like any project of this scope, is the work of numerous individuals across multiple disciplines and institutions who have contributed a range of skills and expertise.</p>
<h3 class="code-line" data-line-start=4 data-line-end=5 ><a id="Principal_Investigator_and_Project_Director_4"></a>Principal Investigator and Project Director</h3>
  <h4 class="code-line" data-line-start=6 data-line-end=7 ><a id="Mattie_Burkert_PhD_6"></a><strong>Mattie Burkert</strong>, PhD</h4>
  <img alt="Headshot of Mattie Burkert outside" style="float:right;padding-left:25px;width:190px;height:245.88px;" src="https://github.com/LondonStageDB/website/blob/main/images/MattieBurkert.jpeg?raw=true">
    <p class="has-line-data" data-line-start="7" data-line-end="8"><a href="https://cas.uoregon.edu/directory/english/all/mburkert">Associate Professor, Department of English, University of Oregon</a></p>
    <p class="has-line-data" data-line-start="9" data-line-end="10">As project director since 2018, <a href="https://mattieburkert.com/">Mattie Burkert</a> has overseen the transformation of an early humanities computing project into a modern relational database, the <em>London Stage Database</em>. With the support of a second grant from the National Endowment for the Humanities, Burkert now leads the next phase of the project to expand the database’s content, functionality, and sustainability, bringing a nuanced understanding of eighteenth-century theatrical culture to a broader user base. She is the author of <a href="https://www.upress.virginia.edu/title/5420/"><em>Speculative Enterprise: Public Theaters and Financial Markets in London, 1688-1763</em></a> and has published widely on the topics of digital humanities, new media studies, early modern and eighteenth-century literature, and theater and performance.</p> 
      <h3 class="code-line" data-line-start=11 data-line-end=12 ><a id="Informationists_11"></a>Informationists</h3>
<h4 class="code-line" data-line-start=13 data-line-end=14 ><a id="Franny_Gaede_MSIS_13"></a><strong>Franny Gaede</strong>, MSIS</h4>
<img alt="Headshot of Franny Gaede in front of blue-gray background" style="float:right;padding-left:25px;width:175px;height:210px;" src="https://github.com/LondonStageDB/website/blob/main/images/FrannyGaede.jpeg?raw=true">
<p class="has-line-data" data-line-start="14" data-line-end="15"><a href="https://library.uoregon.edu/directory/gaede">Strategic Projects Librarian, University of Oregon</a></p>
<p class="has-line-data" data-line-start="16" data-line-end="17">At UO Libraries, <a href="https://www.mfgaede.com/">Gaede</a> provides leadership and support for digital collections, digital preservation, scholarly communication, digital research support, and library-led open access publishing. Gaede has served as Project Team Leader of the <a href="https://cef.uoregon.edu/pnw-just-futures-institute/">PNW Just Futures Institute</a> and as Principal Investigator on major Mellon- and IMLS-funded projects promoting open digital scholarship across disciplines. As part of the LSDB team, Franny advises on project sustainability, digital rights management, and intellectual property.</p>
<h4 class="code-line" data-line-start=18 data-line-end=19 ><a id="Kate_Thornhill_MLS_18"></a><strong>Kate Thornhill</strong>, MLS</h4>
<img alt="Headshot of Kate Thornhill outside" style="float:right;padding-left:25px;width:180.55px;height:233.75px;" src="https://github.com/LondonStageDB/website/blob/main/images/KateThornhill.jpeg?raw=true">
<p class="has-line-data" data-line-start="19" data-line-end="20"><a href="https://library.uoregon.edu/directory/thornhill">Public Scholarship Librarian, University of Oregon</a></p>
<p class="has-line-data" data-line-start="21" data-line-end="22"><a href="https://katethornhill.carrd.co/">Thornhill</a> specializes in the development and implementation of digital projects; the promotion of ethical, open, and participatory knowledge sharing; and the cultivation of information literacies within and beyond the classroom. She has been a key practitioner and leader in several NEH- and Mellon-funded projects that focus on community and civic outreach, including Oregon Digital, LearnStatic, PlacePress, and the PNW Just Futures Institute’s <a href="https://jfi.uoregon.edu/initiatives/afroindigenous-healing/">Afroindigenous Healing Project</a>. As community engagement specialist, Thornhill leads the LSDB’s public outreach efforts, with the aim to grow the project’s user communities.</p>
  <h4 class="code-line" data-line-start=23 data-line-end=24 ><a id="Erin_Winter_MS_MSLS_23"></a><strong>Erin Winter</strong>, MS, MSLS</h4>
<p class="has-line-data" data-line-start="24" data-line-end="25"><a href="https://library.uoregon.edu/directory/winter">Research Data Management Librarian, University of Oregon</a></p>
<p class="has-line-data" data-line-start="26" data-line-end="27">With degrees in both Computer Science and Library Science, Winter consults with researchers on topics such as data manipulation and cleaning, version control, and project management for computational research. While Winter collaborates with faculty, staff, and students across disciplines, she has a particular interest and depth of experience in digital humanities, having worked on sponsored DH projects at the University of Wisconsin, UNC-Chapel Hill, and Duke University. Winter collaborates with other LSDB team members on the curation of new data and metadata, focusing on Linked Open Data and interoperability efforts.</p>
<h3 class="code-line" data-line-start=29 data-line-end=30 ><a id="Linked_Open_Data_Consultant_29"></a>Linked Open Data Consultant</h3>
  <h4 class="code-line" data-line-start=31 data-line-end=32 ><a id="Lauren_Liebe_PhD_31"></a><strong>Lauren Liebe</strong>, PhD</h4>
  <img alt="Headshot of Lauren Liebe in front of green background" style="float:right;padding-left:25px;width:225px;height:225px;" src="https://github.com/LondonStageDB/website/blob/main/images/LaurenLiebe.jpeg?raw=true">
<p class="has-line-data" data-line-start="32" data-line-end="33"><a href="https://behrend.psu.edu/person/lauren-liebe">Assistant Teaching Professor of Game Design, Digital Media, Arts, and Technology, Penn State Eerie, the Behrend College</a></p>
<p class="has-line-data" data-line-start="34" data-line-end="35">A specialist in early modern and Restoration English drama, Liebe is the project developer and general editor of Digital Restoration Drama, an open access database of seventeenth-century play texts. Previously, Liebe served as a Postdoctoral Fellow at the Center of Digital Humanities Research at Texas A &amp; M and as a Project Manager for the Advanced Research Consortium, a hub promoting DH research discovery, peer review, and data aggregation. For the LSDB, Liebe is currently working to link named entities to their VIAF identifiers, enhancing the interoperability of the site.</p>
<h3 class="code-line" data-line-start=36 data-line-end=37 ><a id="UO_Information_Services_Team_36"></a>UO Information Services Team</h3>
<p class="has-line-data" data-line-start="38" data-line-end="39">The London Stage Database is maintained by a team of dedicated developers and programmers at UO. Currently, the software development team is working to improve site functionality, ensure long-term preservation of the project, and support the ingestion of new primary source material.  The team provides crucial technical support and oversight of the database as the LSDB continues to enhance user experience and expand the range of search capabilities.</p>
    <h4 class="code-line" data-line-start=40 data-line-end=41 ><a id="Jesse_Sedwick_BS_40"></a><strong>Jesse Sedwick</strong>, BS</h4>
    <p class="has-line-data" data-line-start="41" data-line-end="42">Web Applications Developer &amp; Administrator</p>
<p class="has-line-data" data-line-start="43" data-line-end="44">Sedwick has over twenty years of experience in custom web application development. He has designed and implemented a wide range of web applications to streamline workflows for university schools and departments.</p>
    <h4 class="code-line" data-line-start=45 data-line-end=46 ><a id="Tyfanie_Wineriter_BS_45"></a><strong>Tyfanie Wineriter</strong>, BS</h4>
<p class="has-line-data" data-line-start="46" data-line-end="47">Software Solutions &amp; Development Manager</p>
<p class="has-line-data" data-line-start="48" data-line-end="49">Wineriter leads the Custom Software Development team at UO, where she specializes in employee engagement, project management, resource management and planning, and development standards.</p>
    <h4 class="code-line" data-line-start=50 data-line-end=51 ><a id="Derek_Wormdahl_BS_50"></a><strong>Derek Wormdahl</strong>, BS</h4>
<p class="has-line-data" data-line-start="51" data-line-end="52">Senior Director of Solutions Development and Data Services</p>
<p class="has-line-data" data-line-start="53" data-line-end="54">Wormdahl has over 25 years of industry experience developing, hosting and maintaining software applications in both the private and public sector fields. Wormdahl now occupies a leadership role at UO, overseeing IT staff that develop and maintain several of the university’s web applications.</p>
    <h4 class="code-line" data-line-start=55 data-line-end=56 ><a id="John_Zhao_MS_55"></a><strong>John Zhao</strong>, MS</h4>
<p class="has-line-data" data-line-start="56" data-line-end="57">Analyst Programmer</p>
<p class="has-line-data" data-line-start="58" data-line-end="59">Zhao has twenty years of experience in web and software development. He has expertise in Drupal, WordPress, and Docker, as well as several back-end technologies, such as Angular, .NET Core, Azure, and API.</p>
    <h4 class="code-line" data-line-start=60 data-line-end=61 ><a id="Dennis_Pipes_MEd_60"></a><strong>Dennis Pipes</strong>, MEd</h4>
<p class="has-line-data" data-line-start="61" data-line-end="62">Analyst Programmer</p>
<p class="has-line-data" data-line-start="63" data-line-end="64">Pipes has worked in both K-12 and higher education for over twenty years, serving as an instructor, support specialist, and web developer. He also has over ten years of experience in the private sector as a trainer and tech generalist.</p>
<h3 class="code-line" data-line-start=55 data-line-end=56 ><a id="Research_Assistants_20242025_55"></a>Research Assistants 2024-2025</h3>
  <h4 class="code-line" data-line-start=57 data-line-end=58 ><a id="Michele_Pflug_MA_57"></a><strong>Michele Pflug</strong>, MA</h4>
  <img alt="Headshot of Michele Pflug outside" style="float:right;padding-left:25px;width:225px;height:225px;" src="https://github.com/LondonStageDB/website/blob/main/images/MichelePflug.jpeg?raw=true">
<p><a id="Doctoral_Candidate_Department_of_History_University_of_Oregonhttpscasuoregonedudirectorysocialsciencesallmpflug_58"></a><a href="https://cas.uoregon.edu/directory/social-sciences/all/mpflug">Doctoral Candidate, Department of History, University of Oregon</a></p>
<p class="has-line-data" data-line-start="60" data-line-end="61">Pflug’s research interests lie at the intersection of the history of science, women’s studies, collections management, and media studies. Her doctoral work has been recognized with an <a href="https://www.acls.org/fellow-grantees/michele-d-pflug/">ACLS Dissertation Innovation Fellowship</a> and awards from the Bibliographic Society of America, the American Society for Eighteenth-Century Studies, and the Linda Hall Library. A former rare books curator, she has contributed to and supported the development of various digital projects hosted by galleries, libraries, museums, and archives. Along with LSDB team member Erin Winter, Pflug will develop data and metadata curation protocols that expand the range of sources available to LSDB users.</p>
<h4 class="code-line" data-line-start=72 data-line-end=73 ><a id="Emma_Kaisner_72"></a><strong>Emma Kaisner</strong><img alt="Headshot of Emma Kaisner in front of brick wall" style="float:right;padding-left:25px;width:210px;height:248.1px;" src="https://github.com/LondonStageDB/website/blob/main/images/EmmaKaisner.jpeg?raw=true"></h4>
<p class="has-line-data" data-line-start="73" data-line-end="74"><a href="https://www.linkedin.com/in/emma-kaisner">BA Student, Economics &amp; English, University of Oregon</a></p>
<p class="has-line-data" data-line-start="75" data-line-end="76">Kaisner is a current Senior at the University of Oregon and a student in the Robert D. Clark Honors College. As an Economics and English student, Kaisner is interested in the interactions between literature, economics, and data science. During her time as a LSDB team member, Kaisner will assist in data collection and the implementation of a communications strategy for the project. Kaisner will also develop her own line of research inquiry to further engage with her own research interests and demonstrate the impact of the LDSB.</p>
<h3 class="code-line" data-line-start=77 data-line-end=78 ><a id="Advisory_Board_77"></a>Advisory Board</h3>
<p class="has-line-data" data-line-start="79" data-line-end="80">The advisory board—a diverse group of digital humanities practitioners, theater researchers, teachers, dramaturgs, software engineers, data journalists, and genealogists—offers expert guidance on the current expansion of the LSDB project. Together, their areas of expertise reflect the LSDB’s goal to engage and grow user communities by making relevant data accessible and interoperable.</p>
<h4 class="code-line" data-line-start=81 data-line-end=82 ><a id="Misty_Anderson_PhD_81"></a><strong>Misty Anderson</strong>, PhD</h4>
<img alt="Headshot of Misty Anderson outside" style="float:right;padding-left:25px;width:178.6px;height:205.92px;" src="https://github.com/LondonStageDB/website/blob/main/images/MistyAnderson.jpeg?raw=true">
<p class="has-line-data" data-line-start="82" data-line-end="83"><a href="https://english.utk.edu/misty-anderson/">Professor of English, James R. Cox Professor of Arts and Sciences, University of Tennessee</a></p>
<p class="has-line-data" data-line-start="84" data-line-end="85">Anderson is the author of <a href="https://www.press.jhu.edu/books/title/10606/imagining-methodism-eighteenth-century-britain"><em>Imagining Methodism in Eighteenth-Century Britain: Enthusiasm, Belief, and the Borders of the Self and Female Playwrights</em></a> and <a href="https://link.springer.com/book/10.1057/9780312292751"><em>Eighteenth-Century Comedy: Negotiating Marriage on the London Stage</em></a>. As one of the founders of the <a href="https://www.r18collective.org/">R/18 Collective</a>—an international consortium of theater scholars and practitioners—she is committed to re-imagining the Restoration and eighteenth-century theatrical repertoire in ways that address the deep histories of race, gender, empire, sexuality, and class that have shaped the modern world.</p>
<h4 class="code-line" data-line-start=86 data-line-end=87 ><a id="Michael_Gamer_PhD_86"></a><strong>Michael Gamer</strong>, PhD</h4>
<img alt="Headshot of Michael Gamer" style="float:right;padding-left:25px;width:178.6px;height:205.92px;" src="https://github.com/LondonStageDB/website/blob/main/images/MichaelGamer.jpeg?raw=true">
<p class="has-line-data" data-line-start="87" data-line-end="88"><a href="https://www.english.upenn.edu/people/michael-gamer">Professor of English and Comparative Literature, University of Pennsylvania</a></p>
<p class="has-line-data" data-line-start="89" data-line-end="90">Gamer’s research investigates how context shapes media ecologies and impacts aesthetic forms in 18th-Century and 19th-Century British Literature. He is the author of two monographs: <a href="https://www.cambridge.org/core/books/romanticism-selfcanonization-and-the-business-of-poetry/A4E251B28216C843A6445C2C2D56B966"><em>Romanticism, Self- Canonization, and the Business of Poetry</em></a> and <a href="https://www.cambridge.org/core/books/romanticism-and-the-gothic/26A366D2DBE3505FDD75EADAB3CF706B"><em>Romanticism and the Gothic: Genre, Reception, and Canon Formation</em></a>. He is currently working on a book on melodrama and an associated digital project asking what playbills read en masse can tell us.</p>
<h4 class="code-line" data-line-start=91 data-line-end=92 ><a id="Michelle_J_Holman_MLIS_91"></a><strong>Michelle J. Holman</strong>, MLIS</h4>
<p class="has-line-data" data-line-start="92" data-line-end="93">Principal Genealogist, <a href="https://www.familyhistorygifts.co.uk/">Family History Gifts</a></p>
<p class="has-line-data" data-line-start="94" data-line-end="95">A trained genealogist, reference librarian, and music cataloguer, Holman has a particular interest in theatrical personalities and hereditary theater families of the eighteenth century. Her current book project, <em>Recording the Nations</em>, is a study of the work and lives of nineteenth-century census enumerators. A regular user of the London Stage Database, Holman has become an important advocate of the project in genealogy and music history circles, referencing it in blog posts, podcast appearances, and articles for popular outlets like <em>Family Tree Magazine</em>.</p>
<h4 class="code-line" data-line-start=96 data-line-end=97 ><a id="Todd_Hugie_MLIS_BS_96"></a><strong>Todd Hugie</strong>, MLIS, BS</h4>
<img alt="Headshot of Todd Hugie in front of dark curtain" style="float:right;padding-left:25px;width:185px;height:160px;" src="https://github.com/LondonStageDB/website/blob/main/images/ToddHugie.jpeg?raw=true">
<p class="has-line-data" data-line-start="97" data-line-end="98"><a href="https://library.usu.edu/about/staff_directory/staff_files/toddhugie">Director of Library Information Technologies, Merrill-Cazier Library, Utah State University</a></p>
<p class="has-line-data" data-line-start="99" data-line-end="100">Hugie manages technology projects, standards, and processes for the Merrill-Cazier Library. During the first iteration of LSDB, Hugie designed the database’s relational structure and helped recover damaged files from the London Stage Information Bank; he continues to serve as a source of institutional memory during the next phase of LSDB’s expansion.</p>
<h4 class="code-line" data-line-start=101 data-line-end=102 ><a id="Atesede_Makonnen_PhD_101"></a><strong>Atesede Makonnen</strong>, PhD</h4>
<img alt="Headshot of Atesede Makonnen in front of white background" style="float:right;padding-left:25px;width:165px;height:196.112px;" src="https://github.com/LondonStageDB/website/blob/main/images/AtesedeMakonnen.jpeg?raw=true">
<p class="has-line-data" data-line-start="102" data-line-end="103"><a href="https://www.cmu.edu/dietrich/english/about-us/faculty/bios/atesede-makonnen.html">Assistant Professor, Department of English, Carnegie Mellon University</a></p>
<p class="has-line-data" data-line-start="104" data-line-end="105">Makonnen’s research focuses race in eighteenth and nineteenth-century Britain. Her articles have appeared in journals including <em>European Romantic Review</em>, <em>Studies in Romanticism</em>, and <em>Victorian Studies</em>, and she has contributed to the collections <em>The Cambridge Companion to Romanticism and Race</em>, and <em>The Visual Life of Romantic Theatre, 1770-1830</em>. Her current book project is titled <em>Sensing Blackness in Nineteenth-Century British Culture</em>.</p>
<h4 class="code-line" data-line-start=106 data-line-end=107 ><a id="Derek_Miller_PhD_106"></a><strong>Derek Miller</strong>, PhD</h4>
<img alt="Black and white headshot of Derek Miller outside" style="float:right;padding-left:25px;width:200px;height:175px;" src="https://github.com/LondonStageDB/website/blob/main/images/DerekMiller.jpeg?raw=true">
<p class="has-line-data" data-line-start="107" data-line-end="108"><a href="https://english.fas.harvard.edu/people/derek-miller">Professor, Department of English, Harvard University</a></p>
<p class="has-line-data" data-line-start="109" data-line-end="110"><a href="https://derek.visualizingbroadway.com/">Miller</a> is the author of <a href="https://www.cambridge.org/core/books/copyright-and-the-value-of-performance-17701911/B3A054044A6BDF8D4E1F051F3C07F19F"><em>Copyright and the Value of Performance, 1770-1911</em></a> and has published numerous essays at the intersection of theater history and digital humanities. His data-driven investigations of the Broadway repertory, known collectively as <a href="https://www.visualizingbroadway.com/">Visualizing Broadway</a>, have positioned him as a public expert on show business in mainstream media outlets like CNBC, Playbill, Marketplace, and the New York Times. An enthusiastic contributor to the LSDB project from its earliest days, Miller’s forensic investigations and creative Python scripting made it possible to recover data from the archival remains of its 1970s predecessor.</p>
<h4 class="code-line" data-line-start=111 data-line-end=112 ><a id="Chelsea_Phillips_MFA_PhD_111"></a><strong>Chelsea Phillips</strong>, MFA, PhD</h4>
<img alt="Headshot of Chelsea Phillips in front of bookcase" style="float:right;padding-left:25px;width:200px;height:262.5px;" src="https://github.com/LondonStageDB/website/blob/main/images/ChelseaPhillips.jpeg?raw=true">
<p class="has-line-data" data-line-start="112" data-line-end="113"><a href="https://villanovatheatre.org/chelsea-phillips/">Associate Professor, Theatre Department, Villanova University</a></p>
<p class="has-line-data" data-line-start="114" data-line-end="115">Phillips is a professional dramaturg and theatre historian who has published widely on the topics of women and eighteenth-century theatre; her monograph, <a href="https://udpress.udel.edu/book-title/carrying-all-before-her/"><em>Carrying All Before Her: Celebrity Pregnancy and the London Stage 1689-1800</em></a> reconstructs the histories of six celebrity women to investigate how pregnancy impacted theatrical culture. As a dramaturg, Phillips has worked with various theatre companies, artists, and writers, including the Manhattan Shakespeare Project, Uncut Pages Theatre, and the Royal Shakespeare Company. She is a proud member of R/18, which provides advocacy and dramaturgical support for Restoration and eighteenth-century drama in performance.</p>
<h4 class="code-line" data-line-start=116 data-line-end=117 ><a id="Jeffrey_S_Ravel_PhD_116"></a><strong>Jeffrey S. Ravel</strong>, PhD</h4>
<img alt="Headshot of Jeffrey Ravel in front of window" style="float:right;padding-left:25px;width:200px;height:197.33px;" src="https://github.com/LondonStageDB/website/blob/main/images/JeffreyRavel.jpeg?raw=true">
<p class="has-line-data" data-line-start="117" data-line-end="118"><a href="https://history.mit.edu/people/jeffrey-s-ravel/">Professor Emeritus, Department of History, Massachusetts Institute of Technology</a></p>
<p class="has-line-data" data-line-start="119" data-line-end="120">Ravel specializes in French and European history from the seventeenth through the nineteenth centuries. He is the author of two books: <a href="https://www.cambridge.org/core/journals/law-and-history-review/article/jeffrey-s-ravel-the-wouldbe-commoner-a-tale-of-deception-murder-and-justice-in-seventeenthcentury-france-boston-and-new-york-houghton-mifflin-company-2008-pp-320-25-isbn-9780618197316/1044160B26B07D4C711A4792A7BD5F59"><em>The Would-Be Commoner: A Tale of Deception, Murder, and Justice in Seventeenth Century France</em></a>; and <a href="https://www.cornellpress.cornell.edu/book/9780801485411/the-contested-parterre/"><em>The Contested Parterre: Public Theater and French Political Culture, 1680-1791</em></a>. Since 2008, he has co-directed the <a href="https://cfregisters.org/#!/">Comédie Française Registers Project (CFRP)</a> and has contributed to several DH projects and initiatives focused on French theatre and culture. He is a powerful advocate for interdisciplinary, collaborative, data-driven approaches to theater history.</p>
<h4 class="code-line" data-line-start=121 data-line-end=122 ><a id="Fiona_Ritchie_PhD_121"></a><strong>Fiona Ritchie</strong>, PhD</h4>
<img alt="Headshot of Fiona Ritchie in front of curtain" style="float:right;padding-left:25px;width:225px;height:200px;" src="https://github.com/LondonStageDB/website/blob/main/images/FionaRitchie.jpeg?raw=true">
<p class="has-line-data" data-line-start="122" data-line-end="123"><a href="https://www.mcgill.ca/english/staff/fiona-ritchie">Associate Professor of Drama and Theatre, Department of English, McGill University</a></p>
<p class="has-line-data" data-line-start="124" data-line-end="125">Ritchie is the author of two books–<a href="https://www.bloomsbury.com/us/shakespeare-in-the-theatre-sarah-siddons-and-john-philip-kemble-9781350073289/"><em>Shakespeare in the Theatre: Sarah Siddons and John Philip Kemble</em></a> and <a href="https://www.cambridge.org/core/books/women-and-shakespeare-in-the-eighteenth-century/F8FBCDDD908A51F0ECC965AA4DBE75DF"><em>Women and Shakespeare in the Eighteenth Century</em></a>. Her current project, “Women and Regional Theatre in Britain and Ireland, 1642-1832,&quot; has been supported by the Social Sciences and Humanities Research Council of Canada and fellowships at Jesus College and Christ Church, University of Oxford. Ritchie served as a beta tester for the launch of LSDB and provided expert commentary on the project in <em>ABO: Interactive Journal of Women in the Arts</em> and the <em>Economist</em>.</p>
<h4 class="code-line" data-line-start=126 data-line-end=127 ><a id="Jeremy_SingerVine_126"></a><strong>Jeremy Singer-Vine</strong></h4>
<img alt="Headshot of Jeremy Singer Vine in front of white background" style="float:right;padding-left:25px;width:225px;height:200px;" src="https://github.com/LondonStageDB/website/blob/main/images/JeremySingerVine.jpeg?raw=true">
<p class="has-line-data" data-line-start="127" data-line-end="128"><a href="https://www.nytco.com/press/jeremy-singer-vine-joins-the-times-as-data-editor/">Data Editor, New York Times</a></p>
<p class="has-line-data" data-line-start="129" data-line-end="130"><a href="https://www.jsvine.com/">Jeremy Singer-Vine</a> is an award-winning journalist, data analyst, and computer programmer who has worked for various news outlets, including BuzzFeed News, the Wall Street Journal, and The New York Times. Currently, he runs the <a href="https://www.data-liberation-project.org/">Data Liberation Project</a>, an initiative to identify, obtain, reformat, clean, document, publish, and disseminate government datasets of public interest. Singer-Vine also publishes a weekly newsletter, <a href="https://www.data-is-plural.com">Data is Plural</a>, where he shares and explains a range of pertinent datasets to a large public following. Through podcasts, newsletters, and editorial work, Singer-Vine has played a key role in bringing LSDB to the attention of the data visualization community.</p>
<h4 class="code-line" data-line-start=131 data-line-end=132 ><a id="Mark_Vareschi_PhD_131"></a><strong>Mark Vareschi</strong>, PhD</h4>
<img alt="Headshot of Mark Vareschi outside" style="float:right;padding-left:25px;width:225px;height:133.203px;" src="https://github.com/LondonStageDB/website/blob/TeamBioUpdates/images/MarkVareschi2.jpg?raw=true">
<p class="has-line-data" data-line-start="132" data-line-end="133"><a href="https://english.wisc.edu/staff/vareschi-mark/">Associate Professor, Department of English, University of Wisconsin-Madison</a></p>
<p class="has-line-data" data-line-start="134" data-line-end="135"><a href="https://mvareschi.wordpress.com/">Vareschi</a> is the author of <a href="https://www.upress.umn.edu/9781517904074/everywhere-and-nowhere/"><em>Everywhere and Nowhere: Anonymity and Mediation in Eighteenth-Century England</em></a> and co-editor of <a href="https://uwpress.wisc.edu/books/5809.htm"><em>Intermediate Horizons: Book History and the Digital Humanities</em></a>. His current book project, <em>Figures of Surveillance</em>, locates the origins of the 21st-century surveillance subject in early modern theories of memory, sympathy, and identity. Vareschi brings to the board expertise both in the practical uses and theoretical problems of data-driven approaches to the study of theatrical and print cultures, particularly in how digital resources tend to reinscribe authorial attribution with vexed histories.</p>
<h4 class="code-line" data-line-start=136 data-line-end=137 ><a id="Jane_Wessel_PhD_136"></a><strong>Jane Wessel</strong>, PhD</h4>
<p class="has-line-data" data-line-start="137" data-line-end="138"><a href="https://hcommons.org/members/janewessel/">Associate Professor, Department of English, U.S. Naval Academy</a></p>
<img alt="Headshot of Jane Wessel outside" style="float:right;padding-left:25px;width:225px;height:160px;" src="https://github.com/LondonStageDB/website/blob/main/images/JaneWessel.jpg?raw=true">
<p class="has-line-data" data-line-start="139" data-line-end="140">Wessel specializes in eighteenth-century British theater history, performance studies, and the rise of the modern celebrity. Her book <a href="https://press.umich.edu/Books/O/Owning-Performance-Performing-Ownership"><em>Owning Performance | Performing Ownership: Literary Property and the Eighteenth-Century British Stage</em></a>, sheds new light on the legal and financial strategies that shaped dramatic literature and theatrical culture between 1710 and 1833. Wessel has held research fellowships at the Folger Shakespeare Library, Chawton House, Houghton Library, and Lewis Walpole Library, affording her in-depth knowledge of the key archives of eighteenth-century British theater.</p>
<h4 class="code-line" data-line-start=141 data-line-end=142 ><a id="Kalle_Westerling_PhD_141"></a><strong>Kalle Westerling</strong>, PhD</h4>
<img alt="Headshot of Kalle Westerling in front of tan curtain" style="float:right;padding-left:25px;width:208px;height:219.4px;" src="https://github.com/LondonStageDB/website/blob/main/images/KalleWesterling.jpeg?raw=true">
<p class="has-line-data" data-line-start="142" data-line-end="143"><a href="https://www.turing.ac.uk/people/researchers/kalle-westerling">Research Application Manager, Alan Turing Institute</a></p>
<p class="has-line-data" data-line-start="144" data-line-end="145">An experienced Digital Humanities technologist and project manager with a particular interest in sustainability and capacity-building, Westerling previously oversaw <a href="https://www.dhinstitutes.org"><em>Digital Humanities Research Institutes: Expanding Communities of Practice</em></a>, an Institute for Advanced Topics in DH funded by two NEH grants. They also worked as a digital humanities software engineer on the <a href="https://livingwithmachines.ac.uk/team/">Living with Machines</a> project, an interdisciplinary research initiative that explores the impact of the Industrial Revolution on ordinary people. Westerling’s expertise lies at the intersection of DH and theater studies.</p>
<h3 class="code-line" data-line-start=147 data-line-end=148 ><a id="Past_Members_147"></a>Past Members</h3>
<p class="has-line-data" data-line-start="149" data-line-end="150">This project would not be possible without the help of those who supported its earlier stages, including Susan Barribeau, Will Daland, Steven Dast, Erin Dix, Jack Keel, Cal Lee, Bronwen Maseman, Ben Ross Schneider III, Nick Schneider, Cindy Serikaku, Carl Stahmer, Dorothea Salo, Brianna Uzuner, Steel Wagstaff, Kam Woods, Angela Zaytsev, Irene Zimmerman, Dustin Olson, Caden Williams, Emma Hallock, Scott Enderle, Doug Reside, James Ascher, Annette Cottle, Katie Dana, Clint Gillespie, Garth Mikesell, Angela Moore-Swafford, Sam Phelps, Betty Rozum, Annie Strickland-Neilson, Cameron Seright, and Joe Kaili.</p>
<p class="has-line-data" data-line-start="151" data-line-end="152"></p>
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
              <li><a href="https://collections.britishart.yale.edu/catalog/tms:51853">Peter Lely, “Aphra Behn,”</a> 1670, © Yale Center for British Art, Public Domain</li>
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
