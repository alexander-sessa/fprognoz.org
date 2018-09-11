<?php
function fp_auth($cc, $name, $password) {
  $access = file('/home/fp/data/auth/.access');
  foreach($access as $access_str) {
    $ta = explode(';', $access_str);
    if ($ta[0] == $name && $ta[5] && md5($password) == $ta[5])
        return 'player';

  }
  return 'badlogin';
}

$ccs = array(
'ENG' => array('Англия', 'England'),
'BLR' => array('Беларусь', 'Belarus'),
'GER' => array('Германия', 'Germany'),
'NLD' => array('Голландия', 'Netherlands'),
'ESP' => array('Испания', 'Spain'),
'ITA' => array('Италия', 'Italy'),
'RUS' => array('Россия', 'Russia'),
'UKR' => array('Украина', 'Ukraine'),
'FRA' => array('Франция', 'France'),
'PRT' => array('Португалия', 'Portugal'),
'SCO' => array('Шотландия', 'Scotland'),
'UEFA' => array('УЕФА', 'UEFA'),
);
$online_dir = '/home/fp/data/online/';
@header("Content-type: text/vnd.wap.wml");
$out = '';
if (isset($_POST['team'])) {
  $prognoz = $_POST['predict'];
  $team_code = $_POST['team'];
  $password = $_POST['pass'];
  $country_code = $_POST['cc'];
  $tour = $_POST['tour'];

  if (fp_auth($country_code, $team_code, $password) == 'badlogin')
    $out .= "bad password<br/>\n";
  else
  {
    $mlist = array('fp@fprognoz.org');
    if (is_file($online_dir.$country_code.'/emails'))
    {
      $atemp = file($online_dir.$country_code.'/emails');
      foreach ($atemp as $line)
        $mlist[] = substr($line, 0, strpos($line, ':'));
    }
    foreach ($mlist as $email)
    {
      @mail($email, strtoupper($ccs[$country_code][1]), "FP_Prognoz\n$team_code\n$tour\n$prognoz\n",
'From: '.$team_code.' <fp@fprognoz.org>
MIME-Version: 1.0
Content-Type: text/plain;
        charset="koi8-r"
Content-Transfer-Encoding: 8bit
X-Priority: 3
X-MSMail-Priority: Normal
X-Mailer: FP Informer 2.00.100925');
      $out .= "sent to $email<br/>\n";
    }
  }
}
print '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml><card id="fp_prognoz" title="fp_prognoz">'.$out.'
<p>
<a href="blr.php">Belarus</a><br />
<a href="eng.php">England</a><br />
<a href="fra.php">France</a><br />
<a href="ger.php">Germany</a><br />
<a href="ita.php">Italy</a><br />
<a href="nld.php">Netherlands</a><br />
<a href="prt.php">Portugal</a><br />
<a href="rus.php">Russia</a><br />
<a href="sco.php">Scotland</a><br />
<a href="esp.php">Spain</a><br />
<a href="ukr.php">Ukraine</a><br />
</p>
</card></wml>';
?>
