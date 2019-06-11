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

$db = new database();
$sn = new session();

include('./lib/user.php');

if($db->checkLesson($sn->year.'-'.$sn->month.'-'.$_GET['i'], $_GET['n']))
{
    $lesson = $db->getLesson($sn->year.'-'.$sn->month.'-'.$_GET['i'], $_GET['n']);
    if($user->username == $lesson['user'])
        include("./lessonPopupEdit.php");
    else
        include("./lessonPopupView.php");
}
else
    include("./lessonPopupAdd.php");

?>