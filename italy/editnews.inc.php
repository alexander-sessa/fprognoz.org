    <p class="title text15b">Редактор новостей сезона</p>
<?php
if (isset($_POST['news_text']) && $_POST['news_text']) {
  file_put_contents($online_dir.$cca.'/'.$s.'/news', str_replace("\r", '', $_POST['news_text']));

  echo 'Новости сохранены';
}
else {
  $news = file_get_contents($online_dir.$cca.'/'.$s.'/news');
  $nrows = max(35, substr_count($news, "\n") + 1);
  if (mb_detect_encoding($news, 'UTF-8', true) === FALSE)
    $news = iconv('CP1251', 'UTF-8//IGNORE', $news);
  echo '<form action="" method="post">
<textarea name="news_text" rows="'.$nrows.'" cols="120">'.$news.'</textarea><br />
<input type="submit" name="upload" value="сохранить" />
</form>
';
}
?>