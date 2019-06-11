<?php
//
// Tablet
// Copyright (c) 2019 Jovan Lanik
//

$lesson = $db->getLesson($sn->year.'-'.$sn->month.'-'.$_GET['i'], $_GET['n']);

?>
    <div class="formCont notes">
        <label for="notes" class="noteLab">Notes:</label>
    </div>