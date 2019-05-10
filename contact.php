<!doctype html>
<html class="no-js" lang="en">

<head>
  <?php include_once('common/header.php'); ?>
  <title>Contact London Stage Database Project</title>
</head>

<body id="contact">
  <?php include_once('common/nav.php'); ?>
  <div id="main" class="main grid-container">
    <div class="grid-x contact-wrap">
      <div class="small-12 page-heading">
        <h1>Contact Us</h1>
      </div>
      <form method="post" class="small-12 medium-11 large-9 cell grid-x contact-form" action="/cgi-bin/FormMail.pl">
        <input type="hidden" name="recipient" value="londonstagedb@gmail.com" />
        <input type="hidden" name="redirect" value="/confirm.php" />
        <input type="hidden" name="subject" value="London Stage Database Feedback" />
        <p class="contact-desc">We would love to hear from you! Feel free to <a href="mailto:londonstagedb@gmail.com">email us</a> your questions, comments, or bug reports. When reporting an issue with the site, please include as much information as possible about your operating
          system and web browser so that we can reproduce the problem.</p>
        <div class="small-12 medium-6 columns form-section">
          <label for="name">Your Name
            <input name="name" id="name" size=60 type="text">
          </label>
        </div>
        <div class="small-12 medium-6 columns form-section">
          <label for="email">Your Email
            <input name="email" id="email" size=60 type="text">
          </label>
        </div>
        <div class="small-12 medium-6 columns form-section">
          <label for="os">Operating system (e.g. Windows, Android, iOS, macOS)
            <input name="os" id="os" size=60 type="text">
          </label>
        </div>
        <div class="small-12 medium-6 columns form-section">
          <label for="browser">Web browser (e.g. Chrome, Safari, Firefox, Edge)
            <input name="browser" id="browser" size=60 type="text">
          </label>
        </div>
        <div class="small-12 columns form-section">
          <label for="comments">Comments
            <textarea name="comments" id="comments" cols="46" rows="10"></textarea>
          </label>
        </div>
        <div class="small-12 columns form-section">
          <label for="url">If comments are relevant to a specific page, please provide the URL
            <input name="url" id="url" size=80 type="text">
          </label>
        </div>
        <div class="small-12 columns form-btn">
          <input class="button" name="Submit Information" type="submit" id="SubmitInformation" value="Submit" />
        </div>
      </form>
    </div>
  </div>
  <?php include_once('common/footer.php'); ?>
</body>

</html>
