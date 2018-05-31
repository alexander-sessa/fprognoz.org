<?php
  $hq = '';
  if (isset($_SESSION['Coach_name']))
    foreach ($ahq_db as $ahq_cc => $ahq_nm)
      if (array_key_exists($_SESSION['Coach_name'], $ahq_nm))
        $hq = '-hq';
?>
<p class="text15b"><a href="/?m=hof">ЗАЛ СЛАВЫ</a></p>
<br/>
<p class="text15b"><a href="/?m=news">Новости</a></p>
<p class="text15b"><a href="/?m=reglament">Правила</a></p>
<p class="text15b"><a href="/?m=history">История ФП</a></p>
<p class="text15b"><a href="/?m=help<?=$hq?>">Инструкция</a></p>
<p class="text15b"><a href="/?m=konkurs">Конкурсы</a></p>
<p class="text15b"><a href="/?m=vacancy">Вакансии</a></p>
<p class="text15b"><a href="/?m=quota">Квоты</a></p>
<p class="text15b"><a href="http://forum.fprognoz.org" target="forum">Форум</a></p>
<p class="text15b"><a href="" onClick="document.getElementById('gb_toggle').submit(); return false;">Фан-зона &nbsp; <img src="images/<?=$gb_status?>.gif" border = "0" alt="<?=$gb_status?>" /></a></p>
<p class="text15b"><a href="/?m=real">Веб-сайты</a></p>
<p class="text15b"><a href="/?m=video">Трансляции</a></p>
<p class="text15b"><a href="/?m=live&amp;ls=<?=$fprognozls?>">Результаты</a></p>
<?php if ($m != 'main') echo '<p class="text15b"><a href="/?m=main">На главную</a></p>';?>
