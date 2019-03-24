<?php
if (isset($_POST['token']))
{
    spl_autoload_register(function ($class) {
        $class = str_replace('\\', '/', $class);
        $path = dirname(__FILE__).'/'.$class.'.php';
        require_once $path;
    });
    $secret = '6Lc4kH0UAAAAAN_wtsNEuyQWzXg_eDMHXCadUCoa';
    $recaptcha = new \ReCaptcha\ReCaptcha($secret);
    $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                      ->setExpectedAction('registration')
                      ->setScoreThreshold(0.5)
                      ->verify($_POST['token'], $_SERVER['REMOTE_ADDR']);
    echo '
Это данные, которые возвращает Google:<br>
здесь важен показатель score, принимающий значение от 0 (100% бот) до 1 (100% человек),<br>
success - показатель принадлежности проверочного токена нашему сайту,<br>
остальные поля - тоже для контроля, чтобы не подсунули чужой проверочный токен.<br>
<pre>'.var_export($resp->toArray(), true).'</pre>';
}
?>
    <form method="POST">
        Здесь размещаются всякие поля формы, пока их заполняют, Google анализирует поведение посетителя.<br>
        Через пару секунд после завершения анализа активируется кнопка отправки формы.<br>
        Уверен, что ты даже не успел заметить, что она была неактивной :)<br>
        <br>
        <input class="token" type="hidden" name="token" value="">
        <input class="submit" type="submit" disabled>
    </form>
    <script src="https://www.google.com/recaptcha/api.js?render=6Lc4kH0UAAAAALmCN36bK9fY9FB-Pj4zhrgGXazW"></script>
    <script>
        grecaptcha.ready(function(){
            grecaptcha.execute('6Lc4kH0UAAAAALmCN36bK9fY9FB-Pj4zhrgGXazW',{action:'registration'}).then(function(token){
                document.querySelector('.token').value = token
                document.querySelector('.submit').removeAttribute('disabled')
            })
        })
    </script>
