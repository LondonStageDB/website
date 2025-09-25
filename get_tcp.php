<?php
include_once('includes/functions.php');
    # Strip special characters
    $fn = filter_input(INPUT_GET, 'fn', FILTER_SANITIZE_SPECIAL_CHARS);
    if (strlen($fn) > 18){ // Length of longest XML file in the batch
        return;
    }
    getTCPFile($fn);
?>

