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

function showMove($m, $move, $n=0) {
    $turn = 1 + floor($m / 2);
    $side = $m % 2 ? 'black' : 'white';
    $html = '
                    '
    . ($side == 'white' ? '<li>' : '') . '
                        <span id="move_'.$m.'_'.$n.'" class="game_'.$n.' move '.$side.' px-1" data-from="'.$move['from'].'" data-to="'.$move['to'].'" data-fen="'.$move['fen'].'"><strong>' . $move['m'] . '</strong></span>'
    . (isset($_COOKIE['chess_auth']) ? '
                        <span id="edit_'.$m.'_'.$n.'" class="comment-edit text-danger small" data-toggle="modal" data-target="#Modal">&copy;</span>' : '') . '
                        <span id="comment_'.$m.'_'.$n.'" class="comment">' . (isset($move['comment']) ? $move['comment'] : '') . '</span>
                    '
    . ($side == 'black' ? '</li>' : '');
    return $html;
}

function showGame($game, $n=0) {
    $html = '
        <div class="row">
            <div class="col-sm-12 text-black"><hr></div>
        </div>
        <div id="title_'.$n.'" class="row">
            <div class="col-sm-5 h3 text-center">
                <a name="'.urlencode($game['white']).'"></a>
                <a name="'.urlencode($game['black']).'"></a>
                '.$game['white'].' - '.$game['black'].'
            </div>
            <div class="col-sm-6 h4 mt-1 ml-4">'.$game['event'].' '.$game['site'].' '.$game['date'].' '.$game['result'].'</div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <div id="board_'.$n.'"></div>
                <div class="m-1 text-center">
                    <button id="start_'.$n.'" class="btn btn-warning btn-small start" title="начало партии">&laquo;</button>
                    <button id="prev_'.$n.'" class="btn btn-warning btn-small prev" title="предыдущий ход">&lt;</button>
                    <button id="next_'.$n.'" class="btn btn-warning btn-small next" title="следующий ход">&gt;</button>
                    <button id="final_'.$n.'" class="btn btn-warning btn-small final" title="конец партии">&raquo;</button>
                    <button id="flip_'.$n.'" class="btn btn-warning btn-small flip" title="развернуть">↻</button>
                    <button id="turn_'.$n.'" class="btn btn-warning btn-small turn" title="к нотации">0</button>
                </div>
            </div>
            <div class="col-sm-7">
                <ol>';
    foreach ($game['moves'] as $m => $move)
        $html .= showMove($m, $move, $n);

    $init = isset($game['fen']) ? '"'.$game['fen'].'"' : '';
    $html .= '
                </ol>
            </div>
        </div>
        <script>
            board['.$n.'] = null
            init['.$n.'] = '.$init.'
            last['.$n.'] = '.$m.'
            game['.$n.'] = new Chess('.$init.')
            move['.$n.'] = -1
            board['.$n.'] = Chessboard("board_'.$n.'", {position:"start",onMoveEnd:onMoveEnd})
            board['.$n.'].position(game['.$n.'].fen())
        </script>
';
    return $html;
}

function showGamesList($name, $files) {
    global $index;
    global $last;

    $html = '';
    foreach ($files as $file)
    {
        foreach ($index['file'][$file] as $game)
            if ($name == $game['white'] || $name == $game['black'])
                break;

        if ($file != $last)
        {
            $last = $file;
            $html .= '
            <li><a href="index.php?pgn='.$file.'#'.urlencode($name).'">'.$name.': '.$game['event'].' '.$game['site'].' '.$game['date'].' '.$game['white'].' - '.$game['black'].'</a></li>';
        }
    }
    return $html;
}

eval('$index = '.file_get_contents('index.inc').';');
$options = '';
foreach ($index['name'] as $name => $files)
    $options .= '
                <option value="'.$name.'"></option>';

$html = '
        <form id="player_select" action="index.php" method="POST">
        <div class="form-group row mt-3">
            <label for="player" class="col-sm-3 col-form-label text-right">Фильтр по имени: </label>
            <input type="text" class="col-sm-5 form-control" id="player" name="player" list="players">
            <datalist id="players">'.$options.'
            </datalist>
            <button type="submit" class="col-sm-1 btn btn-warning mx-3"> выбрать </button>
            <div class="col-sm-2 pt-2"><a href="index.php" class="text-black">все партии</a></div>
        </div>
        </form>';

if (isset($_GET['pgn']))
{
    $games = getGames($_GET['pgn']);
    foreach ($games as $n => $game)
        $html .= showGame($game, $n);

}
else
{
    $last = '';
    if (isset($_POST['player']) && $_POST['player'])
    {
        $name = $_POST['player'];
        if (sizeof($index['name'][$name]) > 1)
            $html .= '        <h4>Выберите партию из списка: '.(isset($_COOKIE['chess_auth']) ? '<a href="pgn.php" class="small text-danger">[ yправление файлами ]</a>' : '').'</h4>
        <ul>' . showGamesList($name, $index['name'][$name]) . '
        </ul>
';
        else
        {
            $games = getGames($index['name'][$name][0]);
            foreach ($games as $n => $game)
                $html .= showGame($game, $n);

        }
    }
    else
    {
        $html .= '        <h4>Выберите партию из списка: '.(isset($_COOKIE['chess_auth']) ? '<a href="pgn.php" class="small text-danger">[ управление файлами ]</a>' : '').'</h4>
        <ul>';
        foreach ($index['name'] as $name => $files)
            $html .= showGamesList($name, $files);

        $html .= '
        </ul>
';
    }
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
    <script src="https://unpkg.com/@chrisoakman/chessboardjs@1.0.0/dist/chessboard-1.0.0.min.js" integrity="sha384-8Vi8VHwn3vjQ9eUHUxex3JSN/NFqUg3QbPyX8kWyb93+8AC/pPWTzj+nHtbC5bxD" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chess.js/0.10.2/chess.js"></script>
<style>
A {
    color: black;
}
.btn-small {
    width: 44px;
    font-weight: bold;
    border: 1px solid black;
}
.comment-edit,
.move {
    cursor: pointer;
}
.move.white.current {
    background-color: white;
    border: 1px solid black;
}
.move.black.current {
    color: white;
    background-color: black;
    border: 1px solid white;
}
.highlight-white {
    box-shadow: inset 0 0 3px 3px yellow;
}
.highlight-black {
    box-shadow: inset 0 0 3px 3px blue;
}
</style>
<script>
var board = [], init = [], last = [], game = [], move = []
var activeBoard = 0
var squareClass = 'square-55d63'
var squareToHighlight = null
var colorToHighlight = null
function highlight(m, n){
    activeBoard=n
    $(".game_"+n).removeClass("current")
    if (m >= 0)
    {
        $("#move_"+m+"_"+n).addClass("current")
        $("#turn_"+n).removeClass("btn-warning "+"btn-"+(m%2?"light":"dark"))
        $("#turn_"+n).addClass("btn-"+(m%2?"dark":"light"))
    }
    else
    {
        $("#turn_"+n).removeClass("btn-light btn-dark")
        $("#turn_"+n).addClass("btn-warning")
    }
    $("#turn_"+n).html(Math.round(m / 2 + 0.5))
}
function onMoveEnd(){
    b="#board_"+activeBoard
    side=move[activeBoard]%2?'b':'w'
    from=$("#move_"+move[activeBoard]+"_"+activeBoard).data("from")
    to=$("#move_"+move[activeBoard]+"_"+activeBoard).data("to")
    if (side=='w'){
        $(b).find('.'+squareClass).removeClass('highlight-white')
        $(b).find('.square-'+from).addClass('highlight-white')
        squareToHighlight=to
        colorToHighlight='white'
    }
    else
    {
        $(b).find('.'+squareClass).removeClass('highlight-black')
        $(b).find('.square-'+from).addClass('highlight-black')
        squareToHighlight=to
        colorToHighlight='black'
    }
    $(b).find('.square-'+squareToHighlight).addClass('highlight-'+colorToHighlight)
}
function writeComment(){
    n=$("#comment_board").val()
    m=$("#comment_move").val()
    $("#comment_"+m+"_"+n).html($("#comment_text").val())
    console.log($("#comment_text").val())
}
$(document).ready(function(){
    $(".start").click(function(){
        [c1,n] = $(this).attr("id").split("_")
        game[n] = Chess(init[n])
        move[n] = -1
        board[n].position(game[n].fen())
        highlight(move[n], n)
    })
    $(".prev").click(function(){
        [c1,n] = $(this).attr("id").split("_")
        move[n]--
        if (move[n] < 0)
        {
            game[n] = Chess(init[n])
            move[n] = -1
        }
        else
            game[n] = Chess($("#move_"+move[n]+"_"+n).data("fen"))

        board[n].position(game[n].fen())
        highlight(move[n], n)
    })
    $(".next").click(function(){
        [c1,n] = $(this).attr("id").split("_")
        move[n]++
        if (move[n] > last[n]) move[n] = last[n]
        game[n] = Chess($("#move_"+move[n]+"_"+n).data("fen"))
        board[n].position(game[n].fen())
        highlight(move[n], n)
    })
    $(".final").click(function(){
        [c1,n] = $(this).attr("id").split("_")
        game[n] = Chess($("#move_"+last[n]+"_"+n).data("fen"))
        move[n] = last[n]
        board[n].position(game[n].fen())
        highlight(move[n], n)
    })
    $(".flip").click(function(){
        [c1,n] = $(this).attr("id").split("_")
        board[n].flip()
    })
    $(".move").click(function(){
        [c1,m,n] = $(this).attr("id").split("_")
        game[n] = Chess($(this).data("fen"))
        move[n] = m
        board[n].position(game[n].fen())
        highlight(m, n)
        $("html, body").animate({scrollTop:$("#title_"+n).offset().top},200)
    })
    $(".turn").click(function(){
        [c1,n] = $(this).attr("id").split("_")
        if (move[n]>=0)
            $("html, body").animate({scrollTop:$("#move_"+move[n]+"_"+n).offset().top},200)

    })
    $("#player").change(function(){
        $("#player_select").submit()
    })
    $(".comment-edit").click(function(){
        [c1,m,n] = $(this).attr("id").split("_")
        $("#comment_board").val(n)
        $("#comment_move").val(m)
        $("#comment_text").val($("#comment_"+m+"_"+n).html())
    })
})
</script>
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