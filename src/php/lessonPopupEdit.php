<?php
//
// Tablet
// Copyright (c) 2019 Jovan Lanik
//

$lesson = $db->getLesson($sn->year.'-'.$sn->month.'-'.$_GET['i'], $_GET['n']);

?>
<form id="lessonForm" action="./src/php/?edit=<?=$_GET['i']?>" method="post" target="frame">
    <input type="hidden" name="num" value="<?=$_GET['n']?>">
    <div>
        <div class="formCont sl">
            <label for="length" class="label">Length:</label>
            <input type="range" min="1" max="11" name="length" id="length" class="slider" sliderout="lengthOut" value="<?=$lesson['length']?>">
            <p class="sliderOut" id="lengthOut"><?=$lesson['length']?></p>
        </div>
        <div class="formCont sl">
            <label for="amount" class="label">Amount:</label>
            <input type="range" min="1" max="31" name="amount" id="amount" class="slider" sliderout="amountOut" value="<?=$lesson['amount']?>">
            <p class="sliderOut" id="amountOut"><?=$lesson['amount']?></p>
        </div>
    </div>
    <div class="formCont notes">
        <label for="notes" class="noteLab">Notes:</label>
        <textarea rows="5" cols="50" name="note" id="note" class="textarea"></textarea>
    </div>
</form>
<div class="buttonCont">
    <button class="button sh" onclick="getForm('lessonForm', 'table', 'lessonPopup')"><i class="material-icons">save</i>Save</button>
    <button class="button sh hot" onclick="fuckshit(<?=$_GET['i']?>, <?=$_GET['n']?>);"><i class="material-icons">delete</i>Delete</button>
</div>
<iframe style="display: none" name="frame" id="frame"></iframe>