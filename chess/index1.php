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

function showGame($game, $n=0) {
    $html = '
        <div class="row h3">'.$game['white'].' - '.$game['black'].'</div>
        <div class="row h4">'.$game['event'].' '.$game['site'].' '.$game['date'].' '.$game['result'].'</div>
        <div class="row">
            <div class="col-5">
                <div id="board_'.$n.'" style="width: 400px"></div>
                <button id="start_'.$n.'" class="start">&laquo;</button>
                <button id="prev_'.$n.'" class="prev">&lt;</button>
                <button id="next_'.$n.'" class="next">&gt;</button>
                <button id="final_'.$n.'" class="final">&raquo;</button>
            </div>
            <div class="col-7">
            </div>';

    $html .= '
        </div>
        <script>
            var board_'.$n.' = null
            var game_'.$n.' = new Chess('.(isset($game['fen']) ? '"'.$game['fen'].'"' : '').')
            board_'.$n.' = Chessboard("board_'.$n.'", "start")
            board_'.$n.'.position(game_'.$n.'.fen())
        </script>
';
    return $html;
}


$pgn = 'annotated.pgn';

$html = '';
$games = getGames($pgn);
foreach ($games as $n => $game)
    $html .= showGame($game, $n);

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
</head>
<body>
    <div class="container">
<?=$html?>

<div id="myBoard" style="width: 400px"></div>
<button id="move1Btn">e4 c6</button>
<button id="move2Btn">d4 d5</button>
<button id="move3Btn">Nc3 dxe4</button>
<button id="startPositionBtn">Start Position</button>

    <script>
var board = null
var game = new Chess()
board = Chessboard('myBoard', 'start')

$('#move1Btn').on('click', function () {
  //board.move('e2-e4')
  game.move('e4')
  board.position(game.fen())
  game.move('c6')
  board.position(game.fen())
})

$('#move2Btn').on('click', function () {
  //board.move('d2-d4', 'g8-f6')
  game.move('d4')
  board.position(game.fen())
  game.move('d5')
  board.position(game.fen())
})

$('#move3Btn').on('click', function () {
  //board.move('d2-d4', 'g8-f6')
  game.move('Nc3')
  board.position(game.fen())
  game.move('dxe4')
  board.position(game.fen())
})

//$('#startPositionBtn').on('click', board.start)
$('#startPositionBtn').on('click', function(){game.reset();board.position(game.fen())})
    </script>

    </div>
</body>
</html>