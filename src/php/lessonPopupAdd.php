<!--
// Tablet
// Copyright (c) 2019 Jovan Lanik
-->
<form id="lessonForm" action="./src/php/?add=<?=$_GET['i']?>" method="post" target="frame">
    <input type="hidden" name="num" value="<?=$_GET['n']?>">
    <div>
        <div class="formCont sl">
          <label for="length" class="label">Length:</label>
          <input type="range" min="1" max="11" name="length" id="length" class="slider" sliderout="lengthOut" value="1">
           <p class="sliderOut" id="lengthOut">1</p>
       </div>
       <div class="formCont sl">
           <label for="amount" class="label">Amount:</label>
           <input type="range" min="1" max="31" name="amount" id="amount" class="slider" sliderout="amountOut" value="31">
           <p class="sliderOut" id="amountOut">31</p>
       </div>
    </div>
    <div class="formCont notes">
        <label for="notes" class="noteLab">Notes:</label>
        <textarea rows="5" cols="50" name="note" id="note" class="textarea"></textarea>
    </div>
</form>
<div class="buttonCont">
    <button class="button sh" onclick="getForm('lessonForm', 'table', 'lessonPopup')"><i class="material-icons">add</i>Add</button>
</div>
<iframe style="display: none" name="frame" id="frame"></iframe>