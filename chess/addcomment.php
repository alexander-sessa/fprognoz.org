<?php
require('chessParser/Board0x88Config.php');
require('chessParser/CHESS_JSON.php');
require('chessParser/MoveBuilder.php');
require('chessParser/PgnGameParser.php');
require('chessParser/FenParser0x88.php');
require('chessParser/GameParser.php');
require('chessParser/PgnParser.php');
require('chessParser/JsonToPgnParser.php');
file_put_contents('post', var_export($_POST, true));
$n = $_POST['n'];
$m = $_POST['m'];
$comment = trim($_POST['comment']);
$pgn = 'pgn/' . $_POST['pgn'];
$parser = new PgnParser($pgn);
$games = $parser->getGames();
$game_comment = (!isset($games[$n]['moves'][0]['m']) && isset($games[$n]['moves'][0]['comment'])) ? 1 : 0;
if ($m == -1)
{ // комментарий к партии
    if ($game_comment)
        $games[$n]['moves'][0]['comment'] = trim(urldecode($_POST['comment']));
    else
        array_unshift($games[$n]['moves'], array('comment' => trim(urldecode($_POST['comment']))));

file_put_contents('json_before', json_encode($games));
    if ($event = trim(urldecode($_POST['event'])))
        $games[$n]['event'] = $event;

    if ($site = trim(urldecode($_POST['site'])))
        $games[$n]['site'] = $site;

    if ($round = trim($_POST['round']))
        $games[$n]['round'] = $round;

    if ($date = trim($_POST['date']))
        $games[$n]['date'] = $date;

    if ($white = trim(urldecode($_POST['white'])))
        $games[$n]['white'] = $white;

    if ($black = trim(urldecode($_POST['black'])))
        $games[$n]['black'] = $black;

    if ($whiteelo = trim($_POST['whiteelo']))
        $games[$n]['whiteElo'] = $whiteelo;

    if ($blackelo = trim($_POST['blackelo']))
        $games[$n]['blackElo'] = $blackelo;

}
else
    $games[$n]['moves'][$m + $game_comment]['comment'] = trim(urldecode($_POST['comment']));

$json = json_encode($games);
file_put_contents('json_after', $json);
$parser = new JsonToPgnParser();
$parser->addGame($json);
file_put_contents($pgn, $parser->asPgn());
echo 1;
