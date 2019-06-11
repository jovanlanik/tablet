<?php
//
// Tablet
// Copyright (c) 2019 Jovan Lanik
//

session_start();

include('./lib/user.php');

if($user->isValid)
{
	$icon = 'account_circle';
	$text = $user->username;
}
else
{
	$icon = 'lock';
	$text = 'Login';
}

?>
<i class="material-icons"><?=$icon?></i>
<span><?=$text?></span>
<i class="material-icons">expand_more</i>