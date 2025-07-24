<?php
include_once('includes/functions.php');
    # Strip special characters
    $fn = filter_input(INPUT_GET, 'fn', FILTER_SANITIZE_SPECIAL_CHARS);

    # Initialize curl
    $tcp_url = 'https://londonstage.blob.core.windows.net/lsdb-files/tcp/P4/' . $fn;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $tcp_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => "Content-Type: text/xml",
    ));

    # Get XML file from Azure, close the connection
    $xml = curl_exec($ch);
    curl_close($ch);

    # Prepare headers that indicate the file should be downloaded
    header('Content-description: file transfer');
    header('Content-disposition: attachment; filename=' . $fn);
    header('Content-type: text/xml');
    echo $xml;
    exit;
?>

