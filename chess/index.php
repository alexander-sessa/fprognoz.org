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

function showMove($first_turn, $first_side, $m, $move, $n=0) {
    global $game;
/*
    if ($game_comment)
        $m--; // сместить нумерацию ходов, если в 0-м "ходу" записан комментарий к игре
*/
    $m1 = $m + ($first_side == 'w' ? 0 : 1);
    $turn = 1 + floor($m1 / 2);
    $side = $m1 % 2 ? 'black' : 'white';

    $html = '
                    '
    . ($m == 0 || $side == 'white' ? '<li>' : '')
    . ($m == 0 && $side == 'black' ? '
                        <span class="px-1"><strong>...</strong></span>' : '') . '
                        <span id="move_'.$m.'_'.$n.'" class="game_'.$n.' move '.$side.' px-1" data-from="'.$move['from'].'" data-to="'.$move['to'].'" data-fen="'.$move['fen'].'"><strong>' . $move['m'] . '</strong></span>
                        <span id="comment_'.$m.'_'.$n.'" class="comment">' . (isset($move['comment']) ? htmlspecialchars($move['comment']) : '') . '</span>
                    '
    . ($side == 'black' ? '</li>' : '');
    if ($side == 'white' && isset($move['comment']) && isset($game['moves'][$m + 1]))
        $html .= '</li>
                    <li value="'.($first_turn + $turn - 1).'"><span class="px-1"><strong>...</strong></span>';

    return $html;
}

function showGame($game, $n=0) {
    global $eco;

    $game_comment = 0;
    $first_side = isset($game['fen']) ? explode(' ', $game['fen'])[1] : 'w';
    $first_turn = isset($game['fen']) ? explode(' ', $game['fen'])[5] : 1;
    $html = '
        <div class="row">
            <div class="col-sm-12 text-black"><hr></div>
        </div>
        <div id="title_'.$n.'" class="row">
            <div class="col-sm-5 h3 text-center">
                <a name="'.urlencode($game['white'].'-'.$game['black']).'"></a>
                <span id="white_'.$n.'">'.$game['white'].'</span><sub><span id="whiteElo_'.$n.'" class="small">'.($game['metadata']['whiteelo'] ?? '').'</span></sub> -
                <span id="black_'.$n.'">'.$game['black'].'</span><sub><span id="blackElo_'.$n.'" class="small">'.($game['metadata']['blackelo'] ?? '').'</span></sub>, '
        . (strtr($game['result'], ['1/2' => '½']))
        . '
            </div>
            <div class="col-sm-6 h4 mt-1 ml-4">
                <span id="site_'.$n.'">'.$game['site'].'</span>,
                <span id="date_'.$n.'">'.$game['date'].'</span>,
                <span id="event_'.$n.'">'.$game['event'].'</span>,
                тур <span id="round_'.$n.'">'.$game['round'].'</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">'
        . (isset($game['eco']) ? '<h5 class="text-center">'.$eco[$game['eco']].'</h5>' : '')
        . '
                <div id="board_'.$n.'"></div>
                <div class="m-1 text-center">
                    <button id="start_'.$n.'" class="btn btn-warning btn-small start" title="начало партии">&laquo;</button>
                    <button id="prev_'.$n.'" class="btn btn-warning btn-small prev" title="предыдущий ход">&lt;</button>
                    <button id="next_'.$n.'" class="btn btn-warning btn-small next" title="следующий ход">&gt;</button>
                    <button id="final_'.$n.'" class="btn btn-warning btn-small final" title="конец партии">&raquo;</button>
                    <button id="flip_'.$n.'" class="btn btn-warning btn-small flip" title="развернуть">↻</button>
                    <button id="turn_'.$n.'" class="btn btn-warning btn-small turn" title="к нотации">0</button>'
    . (isset($_COOKIE['chess_auth']) ? '
                    <button id="edit_'.$n.'" class="btn btn-transparent btn-small comment-edit" title="комментарий" data-toggle="modal" data-target="#Modal">✍</button>' : '') . '
                    <button id="ome_'.$n.'" class="btn btn-warning btn-small onmoveend" title="подсветка ходов ">'.(isset($_COOKIE['onmoveend']) ? '&check;' : '&nbsp;').'</button>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="ml-3">
                    <span id="comment_-1_'.$n.'" class="comment">';
    if (!isset($game['moves'][0]['m']) && isset($game['moves'][0]['comment']))
    {
        $html .= htmlspecialchars($game['moves'][0]['comment']);
        $game_comment = 1;
        unset($game['moves'][0]);
    }
    $html .= '
                    </span>
                </div>
                <ol start="'.$first_turn.'">';
    foreach ($game['moves'] as $m => $move)
        $html .= showMove($first_turn, $first_side, $m - $game_comment, $move, $n);

    $init = isset($game['fen']) ? '"'.$game['fen'].'"' : 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';
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
        list($file, $n) = explode('/', $file);
        $game = $index['file'][$file][$n];
        $html .= '
            <li><a href="index.php?pgn='.$file.'#'.urlencode($game['white'].'-'.$game['black']).'">'.explode('|', $name)[0].': '.$game['site'].', '.$game['date'].', '.$game['event'].', тур '.$game['round'].', '.$game['eco'].', '.$game['white'].' - '.$game['black'].'</a></li>';
    }
    return $html;
}

eval('$index = '.file_get_contents('index.inc').';');
eval('$eco = '.file_get_contents('eco.inc').';');
$options = '';
foreach ($index['name'] as $name => $files)
    $options .= '
                <option value="'.explode('|', $name)[0].'"></option>';

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
    $parser = new PgnParser('pgn/'.$_GET['pgn']);
    $games = $parser->getGames();
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
            $parser = new PgnParser('pgn/'.explode('/', $index['name'][$name][0])[0]);
            $games = $parser->getGames();
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
.onmoveend {
    font-size: 16px;
    font-weight: bold;
    background-color: #f0d9b5;
    box-shadow: inset 0 0 3px 3px yellow;
    color: black;
}
.onmoveend:hover {
    background-color: #b58863;
    box-shadow: inset 0 0 3px 3px blue;
}
.form-control::-webkit-input-placeholder {
  color: red;
}
.form-control.bg-dark::-webkit-input-placeholder {
  color: pink;
}
</style>
<script>
var board = [], init = [], last = [], game = [], move = []
var activeBoard = 0
var squareClass = 'square-55d63'
var squareToHighlight = colorToHighlight = null
var ome=getCookie("onmoveend")
function getCookie(cname){
  var name=cname+"=";
  var decodedCookie=decodeURIComponent(document.cookie);
//console.log(decodedCookie)
  var ca=decodedCookie.split(';');
  for(var i=0;i<ca.length;i++){
    var c=ca[i];
    while(c.charAt(0)==' ')
      c=c.substring(1);

    if (c.indexOf(name)==0)
      return c.substring(name.length,c.length);

  }
  return "";
}
function highlight(m, n){
    //console.log(game[n].fen())
    activeBoard=n
    $(".game_"+n).removeClass("current")
    if (m >= 0){
        fen=$("#move_"+m+"_"+n).data("fen")
        nextSide=fen.split(" ")[1]
        $("#move_"+m+"_"+n).addClass("current")
        $("#turn_"+n).removeClass("btn-warning "+"btn-"+(nextSide=="w"?"light":"dark"))
        $("#turn_"+n).addClass("btn-"+(nextSide=="w"?"dark":"light"))
    }else{
        $("#turn_"+n).removeClass("btn-light btn-dark")
        $("#turn_"+n).addClass("btn-warning")
    }
    if (init[n].split(" ")[1]=="b")
        m++

    $("#turn_"+n).html(Math.round(m / 2 + 0.5))
}
function onMoveEnd(){
    if (ome!="on") return;
    b="#board_"+activeBoard
    if (move[activeBoard]>=0){
        fen=$("#move_"+move[activeBoard]+"_"+activeBoard).data("fen")
        nextSide=fen.split(" ")[1]
        from=$("#move_"+move[activeBoard]+"_"+activeBoard).data("from")
        to=$("#move_"+move[activeBoard]+"_"+activeBoard).data("to")
        if (nextSide=="b"){
            $(b).find("."+squareClass).removeClass("highlight-white")
            $(b).find(".square-"+from).addClass("highlight-white")
            squareToHighlight=to
            colorToHighlight="white"
            colorToHide="black"
        }else{
            $(b).find("."+squareClass).removeClass("highlight-black")
            $(b).find(".square-"+from).addClass("highlight-black")
            squareToHighlight=to
            colorToHighlight="black"
            colorToHide="white"
        }
        $(b).find(".square-"+squareToHighlight).removeClass("highlight-"+colorToHide)
        $(b).find(".square-"+squareToHighlight).addClass("highlight-"+colorToHighlight)
    }else
        $(b+" .square-55d63").removeClass("highlight-white highlight-black")
}
function writeComment(){
    m=$("#comment_move").val()
    n=$("#comment_board").val()
    $("#comment_"+m+"_"+n).html($("#comment_text").val())
    <?php if (isset($_COOKIE['chess_auth']) && isset($_GET['pgn'])){?>
    $.post("addcomment.php",{
        pgn:encodeURIComponent("<?=$_GET['pgn']?>"),
        n:n,
        m:m,
        event:encodeURIComponent($("#event").val()),
        site:encodeURIComponent($("#site").val()),
        round:$("#round").val(),
        date:$("#date").val(),
        white:encodeURIComponent($("#white").val()),
        black:encodeURIComponent($("#black").val()),
        whiteelo:$("#whiteElo").val(),
        blackelo:$("#blackElo").val(),
        comment:encodeURIComponent($("#comment_text").val())
},function(r){})<?php }?>
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
        if (move[n] < 0){
            game[n] = Chess(init[n])
            move[n] = -1
        }else
            game[n] = Chess($("#move_"+move[n]+"_"+n).data("fen"))

        if (game[n].fen()=="8/8/8/8/8/8/8/8 w - - 0 1")
            board[n].position($("#move_"+move[n]+"_"+n).data("fen"))
        else
            board[n].position(game[n].fen())
        highlight(move[n], n)
    })
    $(".next").click(function(){
        [c1,n] = $(this).attr("id").split("_")
        move[n]++
        if (move[n] > last[n]) move[n] = last[n]
        game[n] = Chess($("#move_"+move[n]+"_"+n).data("fen"))
        if (game[n].fen()=="8/8/8/8/8/8/8/8 w - - 0 1")
            board[n].position($("#move_"+move[n]+"_"+n).data("fen"))
        else
            board[n].position(game[n].fen())
        highlight(move[n], n)
    })
    $(".final").click(function(){
        [c1,n] = $(this).attr("id").split("_")
        game[n] = Chess($("#move_"+last[n]+"_"+n).data("fen"))
        if (last[n]-move[n]!=1)
            $("#board_"+n+" .square-55d63").removeClass("highlight-white highlight-black")

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
        if (m-move[n]!=1)
            $("#board_"+n+" .square-55d63").removeClass("highlight-white highlight-black")

        move[n] = m
        if (game[n].fen()=="8/8/8/8/8/8/8/8 w - - 0 1")
            board[n].position($("#move_"+move[n]+"_"+n).data("fen"))
        else
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
        [c1,n] = $(this).attr("id").split("_")
        if (move[n]<0){
            $(".modal-title").html("Редактирование описания партии")
            $("#pgn-header").removeClass("d-none")
            $("#event").val($("#event_"+n).html())
            $("#site").val($("#site_"+n).html())
            $("#round").val($("#round_"+n).html())
            $("#date").val($("#date_"+n).html())
            $("#white").val($("#white_"+n).html())
            $("#black").val($("#black_"+n).html())
            $("#whiteElo").val($("#whiteElo_"+n).html())
            $("#blackElo").val($("#blackElo_"+n).html())
        }else{
            $(".modal-title").html("Редактирование комментария к ходу")
            $("#pgn-header").addClass("d-none")
        }
        $("#comment_board").val(n)
        $("#comment_move").val(move[n])
        $("#comment_text").val($("#comment_"+move[n]+"_"+n).html())
    })
    $(".onmoveend").on("click",function(){
        //ome=getCookie("onmoveend")
        $(".onmoveend").html(ome=="on"?"&nbsp;":"&check;")
        document.cookie="onmoveend="+(ome=="on"?"; expires=Thu, 01 Jan 1970 00:00:00 UTC;":"on;")+" path=/chess/";
        ome=ome=="on"?"":"on"
        if (ome=="")
            $(".square-55d63").removeClass("highlight-white highlight-black")

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
                        <div id="pgn-header" class="d-none">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input id="event" type="text" class="form-control" name="event" placeholder="Event">
                                </div>
                                <div class="col-sm-6">
                                    <input id="site" type="text" class="form-control" name="site" placeholder="Site">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input id="round" type="text" class="form-control" name="round" placeholder="Round">
                                </div>
                                <div class="col-sm-6">
                                    <input id="date" type="text" class="form-control" name="date" placeholder="Date yyyy.mm.dd">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input id="white" type="text" class="form-control" name="white" placeholder="White">
                                </div>
                                <div class="col-sm-6">
                                    <input id="black" type="text" class="form-control bg-dark text-white" name="black" placeholder="Black">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input id="whiteElo" type="text" class="form-control" name="whiteelo" placeholder="WhiteElo">
                                </div>
                                <div class="col-sm-6">
                                    <input id="blackElo" type="text" class="form-control bg-dark text-white" name="blackelo" placeholder="BlackElo">
                                </div>
                            </div>
                        </div>
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