<?php
$tour = (isset($t) && $t) ? 'KONK' . $t : 'KONK39';
echo '    <h4 class="text-center">&nbsp;&nbsp;&nbsp;Форма отправки заявок на конкурс ' . $tour . '</h4>
';

/************************************************/
/* отправка email с преобразованием фидо-адреса */
/************************************************/

function send_email15($from, $name, $email, $subj, $body)
{
  $email = str_replace(',', ' ', $email);
  $amail = explode(' ', $email);
  foreach ($amail as $email) if ($email = trim($email))
  {
    $email = "$name <$email>";
    $subject = "=?UTF-8?B?" . base64_encode($subj) . "?=";
    @mail($email, $subject, $body,
'From: '.$from.'
MIME-Version: 1.0
Content-Type: text/plain;
        charset="utf-8"
Content-Transfer-Encoding: 8bit
X-Priority: 3
X-MSMail-Priority: Normal
X-Mailer: FP Informer 3.00.160822');
  }
}

/* main section */
if (isset($_SESSION['Coach_name'])) $name = $_SESSION['Coach_name'];
if (isset($_POST['submitpredict']))
{
  if (isset($_POST['name_str'])) $name = $_POST['name_str'];
  $pemail = $_POST['email_str'];
  $teamsout = $_POST['teams_out'];
  if (isset($_POST['teams_in'])) $teamsin = $_POST['teams_in'];
  $prognoz = trim($_POST['prognoz_str']);
}
if (!isset($teamsin)) $teamsin = '';
if (isset($_POST['submitpredict']) && $prognoz && $name && $pemail && strlen($prognoz) >= 30) { // отправка прогноза
  if ($pemail) {
    send_email('FPrognoz.org <fp@fprognoz.org>', $name, $pemail, "Konkurs", "FP_Prognoz\n$name\n$tour\n$prognoz\n- $teamsout\n+ $teamsin\n");
    echo "<h3>Прогноз принят. Копия прогноза отправлена на адрес $pemail<br></h3>";
    $email = str_replace(',', ' ', $pemail);
    $amail = explode(' ', $email);
    $replyto = '';
    foreach ($amail as $email)
      if ($email = trim($email))
        $replyto = "$name <$email>";
  }
  $mlist = array('fp@fprognoz.org');
  foreach ($mlist as $email) {
    @mail($email, 'Konkurs', "FP_Prognoz\n$name\n$tour\n$prognoz\n- $teamsout\n+ $teamsin\n$pemail\n",
'From: '.$name.' <fp@fprognoz.org>
MIME-Version: 1.0
Content-Type: text/plain;
        charset="utf-8"
Content-Transfer-Encoding: 8bit
X-Priority: 3
X-MSMail-Priority: Normal
X-Mailer: FP Informer 2.00.101208-'.$ip);
  }
  // write direct
  $tour_dir = $online_dir . 'konkurs/adds/';
  if (is_file($tour_dir . $tour)) {
    $timer = 0;
    while (is_file($tour_dir . 'lock') && $timer < 500000) {
      $timer++;
      time_nanosleep(0, 1000);
    }
    if ($timer < 500000) {
      touch($tour_dir . 'lock');
      $content = file_get_contents($tour_dir . $tour);
      file_put_contents($tour_dir . $tour, $content . mb_sprintf('%-22s', $name) . "$prognoz -$teamsout +$teamsin;$pemail\n");
      unlink($tour_dir . 'lock');
    }
  }
  else file_put_contents($tour_dir . $tour, mb_sprintf('%-22s', $name) . "$prognoz -$teamsout +$teamsin;$pemail\n");
}
else if (isset($_POST['submitpredict']))
   echo "<h3>Заявка не принята - проверьте заполнение полей</h3>";

/******/
/* UI */
/******/

$matches = file($online_dir . 'konkurs/programs/' . $tour);
$nm = count($matches);
echo "<script type=\"text/javascript\">//<![CDATA[
function newpredict()
{
  var i; var dd; var p=''; var ps=''; var min=0; var max=0;
  for(i=1; i<=$nm; i++) {
    dd = 'dice'+i;
    if (document.getElementById(dd).value) p=p+document.getElementById(dd).value;
    else p=p+'=';
  }
  document.getElementById('prognoz_str').value=p;
}
function predict(id,dice)
{
  document.getElementById(id).value=dice;
  newpredict();
}
//]]></script>
";
echo '<form name="tform" action="/?m=konk&t=' . $t . '" enctype="multipart/form-data" method="post">
<table class="mx-auto">
<tr><td align="right">укажите ваше имя и фамилию: </td><td><input type="text" name="name_str" value="'.(isset($name)?$name:'').'" size="50" /></td></tr>
<tr><td align="right">e-mail, на который отправлять информацию сервера: </td><td><input type="text" name="email_str" value="'.(isset($pemail)?$pemail:'').'" size="50" /></td></tr>
<tr><td align="right">команды, от которых откажетесь: </td><td><input type="text" name="teams_out" value="'.(isset($teamsout)?$teamsout:'').'" size="50" /></td></tr>
<tr><td align="right">команды, которые хотите получить: </td><td><input type="text" name="teams_in" value="'.(isset($teamsin)?$teamsin:'').'" size="50" /></td></tr>
<tr><td align="right">прогноз на конкурс: </td><td><input type="text" id="prognoz_str" name="prognoz_str" value="'.(isset($prognoz)?$prognoz:'').'" size="50" /></td></tr>
</table>
<p class="text-center"><input type="submit" name="submitpredict" value=" отправить прогноз " /></p>
<p class="small text-center">прогноз можно набрать как непосредственно в строке ввода, так и кликая на варианты исходов матчей (1 X 2) в таблице:</p>
';

    echo '<br />
<style>
.bet {
    width: 1.4em;
    height: 1.4em;
    vertical-align: middle;
    margin-bottom: 0.4em;
    color: whitesmoke;
    font-weight: bold;
    border: 1px solid black;
    border-radius: 50%;
}
.bet:active {
    border: 2px solid black;
    border-radius: 50%;
}
.pr_str {
    font-size: 1.2em;
    font-weight: bold;
    width: 0.8em;
    height: 1.1em;
    box-sizing: content-box;
}
</style>
<table class="table table-sm table-condensed table-striped p-table mx-auto">
';
    echo '<thead class="text-center"><tr><th>№</th><th width="65%">матч</th><th>страна</th><th>дата</th><th width="15%">прогноз</th></tr></thead>
<tbody style="line-height:1em">
';
    foreach ($matches as $line) if ($line = trim($line)) {
      if ($line[0] == '|') {
        $atemp = explode('|', $line);
	if ($cut = strpos($atemp[2], ' - ')) {
	  $n = rtrim(trim($atemp[1]), '.');
	  $d = trim($atemp[3]);
	  $ho = trim(substr($atemp[2], 0, $cut));
	  $aw = trim(substr($atemp[2], $cut + 3));
	  if (trim(substr($atemp[2], - 3))) {
	    $cut = strrpos($aw, ' ');
	    $t = substr($aw, $cut + 1);
	    $aw = trim(substr($aw, 0, $cut));
	  }
	  else
	    $t = '&nbsp;';

	  echo '
  <tr>
    <td class="text-end pt-2">'.$n.'</td>
    <td class="pt-2">'.$ho.' - '.$aw.'</td>
    <td class="text-center pt-2">'.$t.'</td>
    <td class="text-center pt-2">'.$d.'</td>
    <td>
      <button class="bet bg-primary" onClick="predict('."'dice$n','1'".'); return false" title="хозяева">1</button>
      <button class="bet bg-success" onClick="predict('."'dice$n','X'".'); return false" title="ничья">X</button>
      <button class="bet bg-danger"  onClick="predict('."'dice$n','2'".'); return false" title="гости">2</button>
      <input type="text" name="'."dice$n".'" value="" id="'."dice$n".'" class="pr_str" onchange="newpredict();">
    </td>
  </tr>
';
        }
      }
    }
    echo '</tbody>
</table>
';
echo '</form></center>
';
?>
