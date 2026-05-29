<?php
  /**
   * Cloudflare Turnstile session gate.
   *
   * Include this at the very top of an entry point, BEFORE includes/functions.php
   * (which opens the database connections). An unverified visitor is shown a
   * one-time Turnstile challenge and the script exits here, so no DB connection
   * is opened for bot/scraper traffic. Once a visitor solves the challenge, a
   * session flag lets every subsequent request through untouched.
   */

  // Load the config if present. If the file is missing, treat it the same as
  // empty keys: the gate does nothing and the site keeps working.
  $turnstile_config = __DIR__ . '/turnstile_config.php';
  if (file_exists($turnstile_config)) {
    require_once $turnstile_config;
  }

  if (!defined('TURNSTILE_SITE_KEY') || !defined('TURNSTILE_SECRET_KEY')
      || TURNSTILE_SITE_KEY === '' || TURNSTILE_SECRET_KEY === '') {
    return;
  }

  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  // Already verified this session — let the request proceed to open the DB and run.
  if (!empty($_SESSION['ls_turnstile_verified'])) {
    return;
  }

  // A POST means the visitor (or the widget's auto-submit callback) is attempting
  // to verify. Any non-success outcome — siteverify error, wrong secret, expired
  // or duplicate token, or no token at all (widget failed to produce one, e.g.
  // bad site key) — drops into the retry UI. We never silently re-render the
  // auto-submitting challenge: that would loop.
  $verify_failed = false;
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['cf-turnstile-response'])) {
      $context = stream_context_create([
        'http' => [
          'method'  => 'POST',
          'header'  => 'Content-Type: application/x-www-form-urlencoded',
          'content' => http_build_query([
            'secret'   => TURNSTILE_SECRET_KEY,
            'response' => $_POST['cf-turnstile-response'],
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
          ]),
          'timeout' => 10,
        ],
      ]);
      $verify = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);
      $data   = json_decode($verify);

      if ($data && !empty($data->success)) {
        $_SESSION['ls_turnstile_verified'] = true;
        // REQUEST_URI is a same-origin path (header() rejects CRLF), so this is not an open redirect.
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
      }
    }
    $verify_failed = true;
  }

  // Not verified: render the challenge (or the manual-retry error page) and stop
  // BEFORE any DB connection is opened.
  $action   = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
  $site_key = htmlspecialchars(TURNSTILE_SITE_KEY, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">
  <title>Verifying your browser&hellip;</title>
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: rgb(41, 33, 18);
      color: #fff;
      font-family: sans-serif;
    }
    .turnstile-card {
      text-align: center;
      padding: 2rem;
    }
    .turnstile-card p {
      margin-bottom: 1.5rem;
      font-size: 1.1rem;
    }
    .cf-turnstile {
      display: inline-block;
    }
    .retry-btn {
      padding: 0.6rem 1.5rem;
      margin-top: 1rem;
      font-size: 1rem;
      background-color: #6e2424;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="turnstile-card">
    <p id="msg-fresh"<?php if ($verify_failed) echo ' style="display:none"'; ?>>Please verify you are human to continue to the search results.</p>
    <div id="msg-failed"<?php if (!$verify_failed) echo ' style="display:none"'; ?>>
      <p><strong>Verification failed.</strong></p>
      <p>Please solve the challenge below, then click <em>Try again</em>.</p>
    </div>
    <form method="POST" action="<?php echo $action; ?>" id="turnstile-form">
      <div class="cf-turnstile"
           data-sitekey="<?php echo $site_key; ?>"
           data-error-callback="onTurnstileError"
           <?php if (!$verify_failed): ?>data-callback="onTurnstileSuccess"<?php endif; ?>></div>
      <p id="retry-button-wrap"<?php if (!$verify_failed) echo ' style="display:none"'; ?>>
        <button type="submit" class="retry-btn">Try again</button>
      </p>
      <noscript><p>JavaScript is required to continue.</p></noscript>
    </form>
  </div>
  <script>
    function onTurnstileError(code) {
      document.getElementById('msg-fresh').style.display = 'none';
      document.getElementById('msg-failed').style.display = 'block';
      document.getElementById('retry-button-wrap').style.display = 'block';
    }
    <?php if (!$verify_failed): ?>
    function onTurnstileSuccess(token) {
      document.getElementById('turnstile-form').submit();
    }
    <?php endif; ?>
  </script>
</body>
</html>
<?php
  exit;
?>
