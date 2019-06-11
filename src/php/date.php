<?php
//
// Tablet
// Copyright (c) 2019 Jovan Lanik
//

session_start();

function __autoload($className)
{
    include("./lib/$className.php");
}

$sn = new session();

$date = date('F Y', strtotime("$sn->year-$sn->month"));

?>
<?=$date?>