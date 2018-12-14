<?php
$prognozlist = $cal = '';
if (!isset($n)) $n = 1;
for ($i = 1; $i <= 24; $i++) {
  $prognozlist .= '<div id="tab-'.$i.'" class="multitabs"'.($i == $n ? '' : ' style="display:none"').'>
			<h5>Заголовок блока '.$i.'</h5>
Строка 1 блока '.$i.'
Строка 2 блока '.$i.'
Строка 3 блока '.$i.'
Строка 4 блока '.$i.'
		</div>';
  $cal .= '
<a href="javascript:void(0)" onClick="showTab('.$i.')">Ссылка на блок '.$i.'</a><br>';
}
echo '
<script>function showTab(i){$(".multitabs").hide();$("#tab-"+i).show();return false}</script>
<div class="d-flex">
	<div id="pl" class="monospace">' . $prognozlist . '</div>
	<div'.($closed ? ' id="mt"' : ' style="width: 200px"').'>Матчи тура:<br><br>' . $cal . '</div>
</div>';


?>