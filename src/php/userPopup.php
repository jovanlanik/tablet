<?php
//
// Tablet
// Copyright (c) 2019 Jovan Lanik
//

session_start();

include('./lib/user.php');

if($user->isValid)
{
	$header = "Welcome $user->firstname $user->lastname.";
	$image = "https://ui-avatars.com/api/?name=$user->firstname+$user->lastname&size=128";
	$message = "this account: ".$user->username;
	$icon = 'check_circle';
	$button = 'Okay';
}
else
{
	$header = "Login";
	$icon = 'lock';
	$button = 'Login';
	if($user->isInvalid)
	{
		$image = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcST-Wq1gRLT2ubROzL6GovxNlYH2nfOcg_3eBWShoJw8TLDL5FQ';
		$message = 'invalid login';
	}
	else
	{
		$image = 'http://icons.iconarchive.com/icons/hopstarter/soft-scraps/128/Lock-Lock-icon.png';
		$message = 'please login';
	}
}

?>
<h3><?=$header?></h3>
<p><?=$message?></p>
<img src="<?=$image?>">
<?php
if(!$user->isValid)
{
?>
<form id="loginForm" action="https://login.uzlabina.cz/projekt" method="post" target="frame">
    <input type="hidden" name="sid" value="<?=$user->sid?>">
    <label for="username">Username:</label>
	<input type="text" name="username">
    <label for="password">Password:</label>
	<input type="password" name="password">
</form>
<?php
}
else
{
?>
<form id="loginForm" target="frame"></form>
<?php
}
?>
<div class="buttonCont">
	<button class="button sh" onclick="getForm('loginForm', 'table', 'userPopup')"><i class="material-icons"><?=$icon?></i><?=$button?></button>
</div>
<iframe style="display: none" name="frame" id="frame"></iframe>