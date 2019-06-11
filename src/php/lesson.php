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

include('./lib/user.php');

if(!$user->isValid)
{
	?>
		<i class="material-icons">error</i>
	<?php
	if($user->isInvalid)
	{
		?>
		<p>Wrong login.</p>
		<?php
	}
	else
	{
		
		?>
		<p>Please login.</p>
		<?php
	}
	exit();
}

$db = new database();
$sn = new session();
//$config = parse_ini_file('../cfg/view.ini');
$length = cal_days_in_month(CAL_GREGORIAN, $sn->month, $sn->year);
$t_min = 8;
$conf_max = 11;
if(isset($config['min'])) $t_min = $config['min'];
if(isset($config['max'])) $conf_max = $config['max'];
$t_max = $db->maxInMonth($sn->year, $sn->month);
if($t_max > $t_min) $t_min = $t_max;
if($sn->showAll == true) $t_min = $conf_max;
for($i = 1; $i <= $length; ++$i)
{
	$i_date = "$sn->year-$sn->month-".sprintf('%02d', $i);
	$t_date[$i] = date('d.m.Y', strtotime($i_date));
	$t_days[$i] = date('l', strtotime($i_date));
	for($n = 0; $n < $t_min; ++$n)
	{
		$lesson = $db->getLesson($i_date, $n);
		if($lesson['user'] == null)
		{
			$t_user[$i][$n] = '<i class="material-icons">add</i>';
			$t_class[$i][$n] = 'empty';
			$name[$i][$n] = 'Add lesson';
		}
		else
		{
			$t_user[$i][$n] = $lesson['user'];
			$t_colspan[$i][$n] = $lesson['length'];
			$t_skip[$i][$n] = $lesson['length']-1;
			$t_class[$i][$n] = 'full';
			$name[$i][$n] = 'Edit lesson';
		}
		$t_amount[$i][$n] = $lesson['amount'];
		$src[$i][$n] = "./src/php/lessonPopup.php?i=$i&n=$n";
	}
}

?>
<table>
<?php
for($i = 1; $i <= $length; ++$i)
{
?>
	<tr>
		<td><?=$t_date[$i]?></td>
		<td><?=$t_days[$i]?></td>
<?php
	for($n = 0; $n < $t_min; ++$n)
	{
?>
		<td colspan="<?=$t_colspan[$i][$n]?>" class="<?=$t_class[$i][$n]?>" onclick="openForm('lessonPopup', '<?=$name[$i][$n]?>', '<?=$src[$i][$n]?>')">
			<div class="lesson">
				<div><?=$t_user[$i][$n]; if($t_amount[$i][$n]) echo('</div><div class="num">'.$t_amount[$i][$n])?></div>
			</div>
		</td>
<?php
		$n += $t_skip[$i][$n];
	}
?>
	</tr>
<?php
}
?>
</table>
