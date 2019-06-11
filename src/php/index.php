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
$db = new database();

include('./lib/user.php');

if(!$user->isValid) exit();

if(isset($_GET['nav']))
{
    switch($_GET['nav'])
    {
        case 'back':
            $sn->month = $sn->month - 1;
            if($sn->month < 1)
            {
                $sn->month = 12;
                $sn->year = $sn->year - 1;
            }
            break;
        case 'forward':
            $sn->month = $sn->month + 1;
            if($sn->month > 12)
            {
                $sn->month = 1;
                $sn->year = $sn->year + 1;
            }
            break;
        default:
            if(is_numeric($_GET['nav']) && $_GET['nav'] > 0)
            {
                if($_GET['nav'] < 13)
                    $sn->month = $_GET['nav'];
                else
                    $sn->year = $_GET['nav'];
            }
            break;
    }
}

if(isset($_GET['add']))
{
    $db->addLesson($sn->year.'-'.$sn->month.'-'.$_GET['add'], $_POST[num], $_POST[length], $user->username, $_POST[amount]);
    if(isset($_POST[note]) && $_POST[note] != '') $db->addNote($sn->year.'-'.$sn->month.'-'.$_GET['add'], $_POST[num], mb_strimwidth($_POST[note], 0, 4096, '...'));
}

if(isset($_GET['edit']))
{
    $db->removeLesson($sn->year.'-'.$sn->month.'-'.$_GET['edit'], $_POST[num]);
    $db->addLesson($sn->year.'-'.$sn->month.'-'.$_GET['edit'], $_POST[num], $_POST[length], $user->username, $_POST[amount]);
    if(isset($_POST[note]) && $_POST[note] != '') $db->addNote($sn->year.'-'.$sn->month.'-'.$_GET['edit'], $_POST[num], mb_strimwidth($_POST[note], 0, 4096, '...'));
}

if(isset($_GET['remove']))
{
    $db->removeLesson($sn->year.'-'.$sn->month.'-'.$_GET['remove'], $_GET[num]);
}

if(isset($_GET['show']))
{
    $sa = $_GET['show'];
    if($sa == 'true') $sa = true;
    else if($sa == 'false') $sa = false;
    $sn->showAll = $sa;
}

$_SESSION['s_session'] = $sn;

if(isset($_GET['end'])) {
    $user->logout();
    session_destroy();
}

?>