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

?>
<div class="year">
    <button class="button sh" onclick="getElement(null, './src/php/?nav=<?=$sn->year-1?>', ['table', 'date', 'datePopupCont'])">
        <i class="material-icons">arrow_back</i>
    </button>
    <h3><?=$sn->year?></h3>
    <button class="button sh" onclick="getElement(null, './src/php/?nav=<?=$sn->year+1?>', ['table', 'date', 'datePopupCont'])">
        <i class="material-icons">arrow_forward</i>
    </button>
</div>
<?php
for($i = 1; $i < 13; ++$i)
{
?>
<button class="button month<?php if($sn->month == $i) echo(" current")?>" onclick="getElement(null, './src/php/?nav=<?=$i?>', ['table', 'date']); closePopup('datePopup')">
    <span><?=$i?></span>
    <span><?=date("M", strtotime("$sn->year-$i"))?></span>
</button>
<?php
}
?>