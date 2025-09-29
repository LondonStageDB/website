<?php
  include_once('includes/functions.php');

  $eventId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  $eventId = preg_replace('/[.+-]/', '', $eventId);
  $event = getEvent(($eventId !== '' && $eventId >= 0) ? $eventId : 1);
  $event['Performances'] = getPerformances($event['EventId']);

  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $referer = (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== $url) ? $_SERVER['HTTP_REFERER'] : '';
?>
  <!doctype html>
  <html class="no-js" lang="en">

  <head>
    <link rel="stylesheet" href="/css/flexslider2-7-2.css" type="text/css" />
    <?php include_once('common/header.php'); ?>
    <title>London Stage Event: <?php echo formatDate($event['EventDate'], true) . ' at ' . getTheatreName($event['TheatreId']); ?></title>
    <meta name="description" content="<?php echo formatDate($event['EventDate'], true); ?> performances of <?php echo implode(', ', array_column($event['Performances'], 'PerformanceTitle')); ?>" />
  </head>

  <body id="event">
    <?php include_once('common/nav.php'); ?>
    <div id="main" class="main grid-container">
      <div class="grid-x event-section">
        <div class="cell small-12 event-header-wrap">
          <?php if ($referer !== '') : ?>
          <div class="back-search"><a href="<?php echo $referer; ?>">&lt;&lt; Back to Search Results</a></div>
          <?php endif; ?>
          <div class="grid-x perf-type-wrap">
            <div class="cell small-12 text-center perf-type">
              <h2 title="<?php echo formatDate($event['EventDate'], true); ?>"><?php echo formatDate($event['EventDate']); ?></h2></div>
          </div>
        </div>
        <div class="cell small-12 medium-6 event-info">
          <span>Event Information</span>
          <div class="event-theatre"><span class="info-heading">Theatre:</span>
            <?php echo getTheatreName($event['TheatreId']); ?>
          </div>
          <div class="event-season"><span class="info-heading">Theatrical Season:</span>
            <?php echo $event['Season']; ?>
          </div>
          <div class="event-volume"><span class="info-heading">Volume:</span>
            <?php echo $event['Volume']; ?>
          </div>
          <div class="event-comments"><span class="info-heading">Comments:</span>
            <?php echo namedEntityLinks($event['CommentC'], TRUE); ?>
          </div>
          <div class="event-btns grid-x">
            <div class="work-nav small-12 medium-6 large-6">
              <h3>Performance List</h3>
              <ul class="no-bullet">
                <?php foreach ($event['Performances'] as $perf) : ?>
                <li>
                  <a href="<?php echo '#' . $perf['PerformanceId']; ?>">
                    <?php echo getPType($perf['PType']); ?>
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
            <div class="download-buttons small-12 medium-6 large 6">
              <h3>Event Downloads</h3>
              <a href="get_json.php?id=<?php echo $event['EventId']; ?>" class="button dwnld-btn">JSON</a>
              <a href="get_xml.php?id=<?php echo $event['EventId']; ?>" class="button dwnld-btn">XML</a>
              <a href="get_csv.php?id=<?php echo $event['EventId']; ?>" class="button dwnld-btn">CSV</a>
            </div>
          </div>
        </div>
        <div class="cell small-12 medium-6 phases-wrap">
          <div id="carousel" class="flexslider">
            <span>Data Phases</span>
            <ul class="slides">
              <li><a href="#">PDF</a></li>
              <li><a href="#">Original</a></li>
              <li><a href="#">Cleaned</a></li>
              <li><a href="#">Parsed</a></li>
            </ul>
          </div>
          <div id="slider" class="flexslider">
          <ul class="image-wrap2 slides">
            <li class="book-pdf2 responsive-embed2">
              <div class="responsive-embed">
              <?php if ($event['BookPDF'] && $event['BookPDF'] !== '') : ?>
              <object data="https://londonstage.blob.core.windows.net/lsdb-files/pdfs/<?php echo $event['BookPDF']; ?>" type="application/pdf" height="725px" width="532px">
                <p>Your web browser doesn't have a PDF plugin. Instead, <a href="https://londonstage.blob.core.windows.net/lsdb-files/pdfs/<?php echo $event['BookPDF']; ?>">click here to download the PDF file</a></p>
              </object>
              <?php else : ?>
              <span class="no-pdf" style="height: 720px">PDF Coming Soon</span>
              <?php endif; ?>
              </div>
            </li>
            <li id="orig" class="phase2 p-orig2">
              <div class="phase-content">
                <h3>Original Data</h3>
                <p class="orig-source">Source:
                  <?php if ($event['Hathi'] !== '') echo 'OCR from HathiTrust PDFs'; else echo 'London Stage Information Bank' ?>
                </p>
                <div class="phase-data">
                  <?php echo htmlentities($event['Phase1']); ?>
                </div>
              </div>
            </li>
            <li id="fixed" class="phase2 p-fixed2">
              <div class="phase-content">
                <h3>Cleaned Data</h3>
                <div class="phase-data">
                  <?php echo htmlentities($event['Phase2']); ?>
                </div>
              </div>
            </li>
            <li id="phase3" class="phase2 p-final2">
              <div class="phase-content">
                <h3>Parsed Data</h3>
                <div class="phase-data">
                  <?php
                    $phase3 = getPhaseIII($event['EventId']);
                  ?>
                  <div class="phaseIII-section">
                    <span class="phaseIII-heading">Event: </span>
                    <?php echo htmlentities($phase3['event']); ?>
                  </div>
                  <?php if (!empty($phase3['perfs'])) : ?>
                    <?php foreach($phase3['perfs'] as $perf) : ?>
                     <div class="phaseIII-section phaseIII-perf">
                       <span class="phaseIII-heading">Performance: </span>
                       <?php echo htmlentities($perf['info']); ?>
                       <?php if (!empty($perf['asSee'])) : ?>
                         <?php foreach($perf['asSee'] as $asSee) : ?>
                         <div class="phaseIII-section phaseIII-sub-section">
                           <span class="phaseIII-heading">AsSeeDate: </span>
                           <?php echo $asSee; ?>
                         </div>
                         <?php endforeach; ?>
                       <?php endif; ?>
                       <?php if (!empty($perf['cast'])) : ?>
                       <div class="phaseIII-section phaseIII-sub-section">
                         <span class="phaseIII-heading">Cast: </span>
                         <?php foreach($perf['cast'] as $cast) : ?>
                           <div class="phaseIII-cast"><?php echo htmlentities($cast); ?></div>
                         <?php endforeach; ?>
                       </div>
                       <?php endif; ?>
                     </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </li>
          </ul>
          <!--<div class="data-nav" aria-hidden="true">
            <span class="data-nav-title">View:</span>
            <a aria-hidden="true" id="origBtn" class="data-toggle-btn" onclick="toggleOrig()">Original Data</a>
            <a aria-hidden="true" id="fixedBtn" class="data-toggle-btn" onclick="toggleFixed()">Cleaned Data</a>
          </div>-->
          </div>
        </div>
      </div>
      <div class="grid-x perf-section">
        <?php foreach ($event['Performances'] as $perf) : ?>
        <div class="cell small-12 perf">
          <div class="grid-x perf-type-wrap">
            <div class="cell small-12 text-center perf-type" id="<?php echo $perf['PerformanceId'] ?>">
              <h2><?php echo getPType($perf['PType']) ?></h2></div>
          </div>
          <div class="grid-x perf-info-wrap">
            <div class="small-12 perf-info">
              <div class="grid-x">
                <div class="small-12 medium-6 large-7 perf-info-left">
                  <?php if(in_array($perf['PType'], ['p', 'a'])) : ?>
                  <div class="perf-title perf-data"><span class="info-heading">Title:</span>
                    <a href="<?php echo linkedTitles($perf['PerfTitleClean']); ?>">
                      <?php echo cleanItalics(cleanTitle($perf['PerfTitleClean'])); ?>
                    </a>
                  </div>
                  <div class="perf-comments perf-data"><span>Comments:</span><br />
                    <?php echo namedEntityLinks($perf['CommentP'], TRUE); ?>
                  </div>
                  <div class="perf-cast perf-data"><span>Cast:</span><br />
                    <?php if (count($perf['cast']) > 0) : ?>
                    <ul class="no-bullet">
                      <?php foreach ($perf['cast'] as $cast) : ?>
                      <li class="grid-x"><span class="role cell small-4"><span class="info-heading">Role:</span>
                        <?php echo linkedSearches('role[]', $cast['Role']); ?> </span>
                        <span class="actor cell small-6"><span class="info-heading">Actor:</span>
                        <?php echo linkedSearches('actor[]', $cast['Performer']); ?> </span>
                      </li>
                      <?php endforeach; ?>
                    </ul>
                    <?php else : ?>
                    <i>None Listed</i>
                    <?php endif; ?>
                  </div>
                  <?php else : ?>
                    <div class="perf-comments perf-data"><span class="info-heading">Comment:</span>
                      <?php echo namedEntityLinks($perf['DetailedComment'], TRUE); ?>
                    </div>
                  <?php endif; ?>
                </div>
                <!-- begin related works area -->
                  <?php
                  // Hide false positive related works for dances, songs, music, tricks
                  if (in_array($perf['PType'], ['d', 's', 'm', 't'])){
                      $works = array();
                  } else{
                      $works = getSphinxRelatedWorks($perf['PerfTitleClean'], $perf['WorkId']);
                  }?>
                <?php if(!empty($works) && count($works) > 0) : ?>
                    <div class="small-12 medium-6 large-5 related-works">
                      <h3>Related Works</h3>
                      <div class="work-info">
                        <details>
                          <summary>What's this?</summary>
                          <p>Dramatic works provisionally linked to this performance, including plays 
                        that may have been staged as well as their <a href="/authors.php">sources, adaptations, and sequels</a>. 
                        Works are identified, where possible, with their "print witnesses:" early published editions 
                        that have been digitized by the Text Creation Partnership. TCP metadata may overlap or conflict with 
                        records curated by the LSDB team. <a href="https://blogs.uoregon.edu/londonstage/2025/09/22/new-feature-print-witnesses/">
                          Read more about the provenance and limitations of the data</a>.</p></details></div>
                      <?php foreach ($works as $work) : ?>
                      <div class="work-info"><!-- begin light shaded block for work -->
                        <div><span class="info-heading">Work Title:</span>
                          <?php echo $work['title']; ?>
                        </div>
                        <div><span class="info-heading">Associated Date<span data-tooltip class="top l-tooltip" 
                        title="Typically the date of first publication, but sometimes the date of first known performance">?</span>:</span>
                          <?php if ($work['pubdate'] > 0) echo $work['pubdate']; ?>
                        </div>
                        <div><span class="info-heading">Associated Playwright(s)<span data-tooltip class="top l-tooltip" 
                        title="Includes modern attributions of what were often anonymous, collaborative, or contested works">?</span>:</span>
                          <?php if (array_filter($work['author'])) : ?>
                            <?php foreach ($work['author'] as $auth) : ?>
                              <?php if (in_array($auth['authtype'], ['Researched', 'Primary'])) : ?>
                                <div> 
                                    <span> &nbsp;&nbsp; <?php echo $auth['authname'] ?>
                                    <?php
                                        if ((array_key_exists('startdate', $auth))
                                            || (array_key_exists('enddate', $auth))){
                                            // Display dates if author has at least one known date
                                            $startdate = array_key_exists('startdate', $auth) ? $auth['startdate'] : '';
                                            $enddate = array_key_exists('enddate', $auth) ? $auth['enddate'] : '';
                                        echo "(" . $startdate . " - " .  $enddate .")" ; }?> </span>
                                </div> <!-- end author list item -->
                              <?php endif; ?> <!--resolves if authtype is not 'Researched', 'Primary' -->
                            <?php endforeach; ?> <!-- resolves for loop for each author -->
                          <?php endif; ?> <!-- resolves loop if work has no author -->
                        </div> <!-- end associated playwrights div -->
                        <!-- check if work has related witnesses -->
                          <?php
                            $witnesses = getRelatedWitnesses($work['workid']); ?>
                            <?php if((!empty($witnesses)) && (count($witnesses) > 0)) : ?>
                              <div><hr/><span class="info-heading">Print Witness(es)</span> 
                              <?php foreach ($witnesses as $witness) : ?>
                                <div class="auth-info"> 
                                    <div class="grid-x">  
                                      <div class="cell small-6 medium-8 large-9">
                                        <div><span class="info-heading">Title:</span><span><?php echo ($witness['witnessTitle']); ?></span></div> 
                                        <div><span class="info-heading">Author(s):</span><span><?php echo ($witness['witnessAuth']); ?></span></div> 
                                        <div><span class="info-heading">Publication Date:</span><span><?php echo ($witness['witnessDate']); ?></span></div> 
                                      </div>
                                      <div class="cell small-6 medium-4 large-3">
                                          <a href="get_tcp.php?fn=<?php echo $witness['witnessFile']; ?>" class="button xml-dwnld-btn" download>XML</a>
                                        </div>
                                    </div> <!-- end grid -->
                                  </div> <!-- end witness -->
                              <?php endforeach; ?>
                              </div> <!-- end related witnesses -->
                            <?php endif; ?>   
                        </div> <!-- end work info -->
                      <?php endforeach; ?> <!-- resolves when list of related works is complete -->
                </div> <!-- end related works panel -->
                <?php endif; ?> <!-- resolves if there are no related works -->
              </div>
            </div>
          </div>
          <!-- end perf-info-wrap -->
        </div>
        <?php endforeach; ?>
      </div>
      <div class="cite-wrap hide">
        <h4>Cite this page</h4>
        <div class="cite-chicago-wrap">
          <span>Chicago: </span><span id="citeChicago"></span>
        </div>
        <div class="cite-mla-wrap">
          <span>MLA: </span><span id="citeMla"></span>
        </div>
      </div>
    </div>
    <?php include_once('common/footer.php'); ?>
    <script src="/js/vendor/jquery.flexslider2-7-2-min.js"></script>
    <script>
      $('#carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 100,
        itemMargin: 10,
        asNavFor: '#slider'
      });
      $('#slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: "#carousel"
      });
    </script>
  </body>

  </html>
