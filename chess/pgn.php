<?php
require('chessParser/Board0x88Config.php');
require('chessParser/CHESS_JSON.php');
require('chessParser/MoveBuilder.php');
require('chessParser/PgnGameParser.php');
require('chessParser/FenParser0x88.php');
require('chessParser/GameParser.php');
require('chessParser/PgnParser.php');

function getGames($pgn) {
    $parser = new PgnParser('pgn/'.$pgn);
    return $parser->getGames();
}

function showGamesList($file, $games) {
    global $index;

    $html = '
            <li><a href="pgn/'.$file.'">'.$file.'</a>: <a href="pgn.php?del='.$file.'" class="text-danger">удалить</a>
                <ul>';
    foreach ($games as $game)
    {

            $html .= '
                    <li>'.$game['site'].' '.$game['event'].' '.$game['date'].' '.$game['round'].' '.$game['white'].' - '.$game['black'].'</li>';
    }
    $html .= '
                </ul>
            </li>';
    return $html;
}

if (isset($_POST['password']) && $_POST['password'] == 'Capablanca')
   setcookie('chess_auth', 'ok', time() + (86400 * 30), "/");

if (!isset($_COOKIE['chess_auth']) && (!isset($_POST['password']) || $_POST['password'] != 'Capablanca'))
{
    $html = '
        <form id="auth" method="POST">
            <div class="form-group row my-2">
                <label for="password" class="col-sm-1 col-form-label">Пароль: </label>
                <input type="password" class="col-sm-2 form-control" name="password">
                <button type="submit" class="col-sm-1 btn btn-warning mx-3"> вход </button>
            </div>
        </form>';
}
else
{
    if (isset($_GET['del']) && strpos($_GET['del'], '/') === false && strpos($_GET['del'], '..') === false && strpos($_GET['del'], '\\') === false)
    {
        unlink('pgn/'.$_GET['del']);
        header('Location: pgn.php');
    }
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'])
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
        if ($mime == 'application/x-chess-pgn' || $mime == 'text/plain')
        {
            $translit = [
                'А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E','Ё'=>'Yo','Ж'=>'Zh','З'=>'Z','И'=>'I','Й'=>'J','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'Ts','Ч'=>'Ch','Ш'=>'Sh','Щ'=>'Shch','Ъ'=>'','Ы'=>'Y','Ь'=>'','Э'=>'E','Ю'=>'Yu','Я'=>'Ya',
                'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'yo','ж'=>'zh','з'=>'z','и'=>'i','й'=>'j','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'ts','ч'=>'ch','ш'=>'sh','щ'=>'shch','ъ'=>'','ы'=>'y','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya',
                ','=>'.',' '=>'_','\''=>'','"'=>''
            ];
            move_uploaded_file($_FILES['file']['tmp_name'], 'pgn/'.strtr($_FILES['file']['name'], $translit));
            if (mb_detect_encoding(file_get_contents('pgn/'.$_FILES['file']['name']), 'utf-8', true) != 'UTF-8')
               file_put_contents('pgn/'.$_FILES['file']['name'], iconv("windows-1251", "UTF-8//IGNORE", file_get_contents('pgn/'.$_FILES['file']['name'])));

        }
        finfo_close($finfo);
    }

    $seen = $index = array();
    $dir = scandir('pgn');
    foreach ($dir as $file)
        if ($file[0] != '.')
        {
            $games = getGames($file);
            foreach ($games as $n => $game)
            {
                $hash = addslashes($game['white'].$game['white'].$game['black'].$game['date'].$file);
                if (!in_array($hash, $seen))
                {
                    $seen[] = $hash;
                    $index['namedate'][addslashes($game['white']).'|'.$game['date']][] = addslashes($file).'/'.$n;
                }
                $hash = addslashes($game['black'].$game['white'].$game['black'].$game['date'].$file);
                if (!in_array($hash, $seen))
                {
                    $seen[] = $hash;
                    $index['namedate'][addslashes($game['black']).'|'.$game['date']][] = addslashes($file).'/'.$n;
                }
                //$hash = addslashes($file.$game['event'].$game['site'].$game['date'].$game['white'].$game['black']);
                //if (!in_array($hash, $seen))
                //{
                //    $seen[] = $hash;
                    $index['file'][addslashes($file)][] = array(
                        'site' => addslashes($game['site'] ?? ''),
                        'event' => addslashes($game['event']),
                        'date' => addslashes($game['date']),
                        'round' => addslashes($game['round']),
                        'eco' => addslashes($game['eco']),
                        'white' => addslashes($game['white']),
                        'black' => addslashes($game['black'])
                    );
                //}
            }
        }

    ksort($index['namedate']);
    foreach ($index['namedate'] as $namedate => $matches)
    {
        $name = explode('|', $namedate)[0];
        foreach ($matches as $match)
            $index['name'][$name][] = $match;

    }
    unset($index['namedate']);
    file_put_contents('index.inc', var_export($index, true));

    eval('$index = '.file_get_contents('index.inc').';');
    $html = '        <h4>Управление файлами <a href="index.php" class="small text-success">[ список партий ]</a></h4>
        <form id="add_file" method="POST" enctype="multipart/form-data">
            <style>.custom-file-label::after{content:"выбрать";}</style>
            <div class="form-group row my-2">
                <div class="custom-file col-sm-6">
                    <input type="file" class="custom-file-input form-control" id="customFile" name="file" accept="application/x-chess-pgn">
                    <label class="custom-file-label text-left ml-3" for="customFile">выберите файл *.pgn</label>
                </div>
                <button type="submit" class="col-sm-2 btn btn-warning mx-3"> загрузить </button>
            </div>
        </form>
        <script>
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName)
            })
        </script>
        <ul>';
    foreach ($index['file'] as $file => $games)
        $html .= showGamesList($file, $games);

    $html .= '
        </ul>
';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=.75">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Шахматные партии</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/@chrisoakman/chessboardjs@1.0.0/dist/chessboard-1.0.0.min.css" integrity="sha384-q94+BZtLrkL1/ohfjR8c6L+A6qzNH9R2hBLwyoAfu3i/WCvQjzL2RQJ3uNHDISdU" crossorigin="anonymous">
    <!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<style>
A {
    color: black;
}
</style>
</head>
<body style="background-color: cornsilk">

    <!-- The Modal -->
    <div class="modal" id="Modal">
        <div class="modal-dialog">
            <div class="modal-content bg-light">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Редактирование комментария</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form id="comment_form" action="index.php" method="POST">
                        <input type="hidden" id="comment_board" name="board">
                        <input type="hidden" id="comment_move" name="move">
                        <textarea id="comment_text" name="comment" class="form-control" rows="8"></textarea>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onClick="writeComment()" data-dismiss="modal">Записать</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
<?=$html?>
    </div>
</body>
</html>