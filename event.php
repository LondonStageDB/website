<?php
  include_once('includes/functions.php');

  $eventId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
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
              <h2><?php echo formatDate($event['EventDate']); ?></h2></div>
          </div>
        </div>
        <div class="cell small-12 medium-6 event-info">
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
            <?php echo namedEntityLinks($event['CommentC']); ?>
          </div>
          <div class="event-btns grid-x">
            <div class="work-nav small-12 medium-8">
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
            <div class="download-buttons small-12 medium-4">
              <h3>Event Downloads</h3>
              <a href="get_json.php?id=<?php echo $event['EventId']; ?>" class="button dwnld-btn">Download JSON</a>
              <a href="get_xml.php?id=<?php echo $event['EventId']; ?>" class="button dwnld-btn">Download XML</a>
            </div>
          </div>
        </div>
        <div class="cell small-12 medium-6 phases-wrap">
          <div id="carousel" class="flexslider">
            <span>Data Phases:</span>
            <ul class="slides">
              <li><a href="#">PDF</a></li>
              <li><a href="#">Original</a></li>
              <li><a href="#">Cleaned</a></li>
              <li><a href="#">Final</a></li>
            </ul>
          </div>
          <div id="slider" class="flexslider">
          <ul class="image-wrap2 slides">
            <li class="book-pdf2 responsive-embed2">
              <div class="responsive-embed">
              <?php if ($event['BookPDF'] && $event['BookPDF'] !== '') : ?>
              <object data="images/pdfs/<?php echo $event['BookPDF']; ?>" type="application/pdf" height="725px" width="532px">
                <p>Your web browser doesn't have a PDF plugin. Instead, <a href="images/pdfs/<?php echo $event['BookPDF']; ?>">click here to download the PDF file</a></p>
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
                  <?php echo $event['Phase1']; ?>
                </div>
              </div>
            </li>
            <li id="fixed" class="phase2 p-fixed2">
              <div class="phase-content">
                <h3>Cleaned Data</h3>
                <div class="phase-data">
                  <?php if ($event['Phase2']) echo $event['Phase2']; else echo 'Coming Soon'; ?>
                </div>
              </div>
            </li>
            <li id="phase3" class="phase2 p-final2">
              <div class="phase-content">
                <h3>Final Data</h3>
                <div class="phase-data">
                  <?php
                    $phase3 = getPhaseIII($event['EventId']);
                  ?>
                  <div class="phaseIII-section">
                    <span class="phaseIII-heading">Event: </span>
                    <?php echo $phase3['event']; ?>
                  </div>
                  <?php if (!empty($phase3['perfs'])) : ?>
                    <?php foreach($phase3['perfs'] as $perf) : ?>
                     <div class="phaseIII-section">
                       <span class="phaseIII-heading">Performance: </span>
                       <?php echo $perf['info']; ?>
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
                           <div class="phaseIII-cast"><?php echo $cast; ?></div>
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
                <div class="small-12 medium-7 large-8 perf-info-left">
                  <div class="perf-title perf-data"><span class="info-heading">Title:</span>
                    <a href="<?php echo linkedTitles($perf['PerformanceTitle']); ?>">
                      <?php echo cleanItalics($perf['PerformanceTitle']); ?>
                    </a>
                  </div>
                  <div class="perf-comments perf-data"><span class="info-heading">Comments:</span>
                    <?php echo namedEntityLinks($perf['CommentP']); ?>
                  </div>
                  <div class="perf-det-comment light-text perf-data"><span class="info-heading">Full Comment:</span>
                    <?php echo $perf['DetailedComment']; ?>
                  </div>
                  <div class="perf-cast perf-data"><span class="info-heading">Cast:</span>
                    <?php if (count($perf['cast']) > 0) : ?>
                    <ul class="no-bullet">
                      <?php foreach ($perf['cast'] as $cast) : ?>
                      <li class="grid-x"><span class="role cell small-4"><span class="info-heading">Role:</span>
                        <?php echo linkedSearches('role', $cast['Role']); ?> </span>
                        <span class="actor cell small-6"><span class="info-heading">Actor:</span>
                        <?php echo linkedSearches('actor', $cast['Performer']); ?> </span>
                      </li>
                      <?php endforeach; ?>
                    </ul>
                    <?php else : ?>
                    <i>None Listed</i>
                    <?php endif; ?>
                  </div>
                  <div class="perf-cast-as-listed light-text perf-data"><span class="info-heading">Cast as Listed:</span>
                    <?php echo $perf['CastAsListed']; ?>
                  </div>
                </div>
                <?php $works = getRelatedWorks($perf['PerfTitleClean']); ?>
                <div class="small-12 medium-5 large-4 related-works">
                  <h3>Related Works</h3>
                  <?php foreach ($works as $work) : ?>
                  <div class="work-info">
                    <div><span class="info-heading">Work Title:</span>
                      <a href="<?php echo linkedTitles((!empty($work['Title'])) ? $work['Title'] : $work['Title']); ?>">
                        <?php echo (!empty($work['Title'])) ? $work['Title'] : $work['Title']; ?>
                      </a>
                    </div>
                    <div><span class="info-heading">Publish Date:</span>
                      <?php echo $work['PubDate']; ?>
                    </div>
                    <?php if (array_filter($work['author'])) : ?>
                    <?php foreach ($work['author'] as $auth) : ?>
                    <div class="auth-info">
                      <div><span class="info-heading">Author: </span>
                        <?php echo linkedSearches('author', $auth['AuthName']); ?>
                      </div>
                      <div class="grid-x">
                        <div class="cell small-6"><span class="info-heading"><?php echo authDateType($auth['StartType']); ?></span>
                          <?php echo $auth['StartDate']; ?>
                        </div>
                        <div class="cell small-6"><span class="info-heading"><?php echo authDateType($auth['EndType']); ?></span>
                          <?php echo $auth['EndDate']; ?>
                        </div>
                      </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
          <!-- end perf-info-wrap -->
        </div>
        <?php endforeach; ?>
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
