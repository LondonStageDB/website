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

  require_once __DIR__ . '/turnstile_config.php';

  // Not configured (empty keys): do nothing so the site works without Turnstile.
  if (TURNSTILE_SITE_KEY === '' || TURNSTILE_SECRET_KEY === '') {
    return;
  }

  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  // Already verified this session — let the request proceed to open the DB and run.
  if (!empty($_SESSION['ls_turnstile_verified'])) {
    return;
  }

  // A challenge was just solved: verify the token server-side, then redirect back
  // to the originally requested URL (GET) so the shareable results URL stays clean.
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cf-turnstile-response'])) {
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

  // Not verified: render the challenge and stop BEFORE any DB connection is opened.
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
  </style>
</head>
<body>
  <div class="turnstile-card">
    <p>Please verify you are human to continue to the search results.</p>
    <form method="POST" action="<?php echo $action; ?>" id="turnstile-form">
      <div class="cf-turnstile"
           data-sitekey="<?php echo $site_key; ?>"
           data-callback="onTurnstileSuccess"></div>
      <noscript><p>JavaScript is required to continue.</p></noscript>
    </form>
  </div>
  <script>
    function onTurnstileSuccess(token) {
      document.getElementById('turnstile-form').submit();
    }
  </script>
</body>
</html>
<?php
  exit;
?>
