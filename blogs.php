<!doctype html>
<html class="no-js" lang="en">

<head>
  <?php 
    include_once('common/header.php');
    include_once('includes/rss.php');
  ?>
  <title>London Stage Database Project Blogs</title>
</head>

<body id="contact">
  <?php include_once('common/nav.php'); ?>
  <div id="main" class="main grid-container">
    <div class="grid-x contact-wrap">
      <div class="small-12 page-heading">
        <h1>Blogs</h1>
      </div>
      <div class="small-12 medium-11 large-9 cell grid-x">
        <?php
           $feedlist = new Rss("https://blogs.uoregon.edu/newmediaculture/posts-archive/feed/");
           echo $feedlist->display();
        ?>
      </div>
    </div>
  </div>
  <?php include_once('common/footer.php'); ?>
</body>

</html>
