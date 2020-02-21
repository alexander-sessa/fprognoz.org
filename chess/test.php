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


$pgn = 'problematic3.pgn';
$pgn = 'nullmoves.pgn';
//$pgn = '1.pgn';
$pgn = 'lichess_pgn_2020.01.30_STALINEZ_vs_Chesspistol.BpXRj9YH.pgn';
$pgn = 'lcc2016.pgn';
$pgn = 'kotlyarov_zelyak_122018.pgn';
$html = '';
$games = getGames($pgn);
print_r($games);
