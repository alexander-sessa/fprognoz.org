#!/usr/bin/php
<?php
date_default_timezone_set('UTC');
$fixtures = '/home/fp/data/online/fixtures';

$debug = false;

# countryCode.tournament => [competition_id => [round_id, ...], ...]
$ccodes = array(
'INT.ACN' => [385 => []],
'INT.CA'  => [288 => [55092, 55093, 55094, 55090, 55091]],
'INT.EC'  => [ 25 => [38188, 38186, 38189, 38190, 38187]],
'INT.ECQ' => [1000=> [46026, 46027, 46028, 46029, 46030, 46031, 46032, 46033]],
'INT.UNL' => [1661=> [54499, 54500, 54501, 54502, 54503, 54497, 54498, 57351]],
'INT.WC'  => [ 72 => []],
'INT.OG'  => [221 => [52381, 52382, 52383, 52380, 52379]],
'INT.WCQ' => [224 => [46192, 46193],
              225 => [44085]],
'INT.CC'  => [246 => []],
'INT.CCC' => [247 => []],
'INT.CNL' => [1728=> [46557, 46558, 46559]],
'INT.CLU' => [284 => [], // Club World Cup
              1148=> [55628], // International Champions Cup
              1407=> [], // Emirates Cup
              1442=> []],// Audi Cup
'INT.FRI' => [430 => [56441, 56442, 56443]],

'ENG.PL'  => [  8 => [53145]],
'ENG.D1'  => [ 70 => [53782, 53781, 53780]],
'GER.PL'  => [  9 => [58871],
              931 => []],
'ITA.PL'  => [ 13 => [54890]],
'ESP.PL'  => [  7 => [53502]],
'ESP.D1'  => [ 12 => [54949, 54948]],
'FRA.PL'  => [ 16 => [58178]],
'NLD.PL'  => [  1 => [57990, 57991, 57992]],
'RUS.PL'  => [121 => [53628]],
'RUS.D1'  => [122 => [58078]],
'PRT.PL'  => [ 63 => [53517]],
'SCO.PL'  => [ 43 => [58114, 58732]],
'BLR.PL'  => [ 66 => [56666]],
'UKR.PL'  => [125 => [54066, 54068, 54672, 54673]],
'BEL.PL'  => [ 24 => [57595, 58104, 57596, 58105]],
'TUR.PL'  => [ 19 => [53866]],
'AUT.PL'  => [ 49 => [54162, 54163, 54165, 54161]],
'SUI.PL'  => [ 27 => [54327]],

'ENG.CUP' => [ 93 => [53387, 53383], // FA Cup
               95 => [57589, 57590, 57592, 57593, 57591]], // League Cup
'GER.CUP' => [104 => [58915, 58916, 58918, 58919, 58917]],
'ITA.CUP' => [135 => []],
'ESP.CUP' => [138 => [53583]],
'FRA.CUP' => [105 => [52204],  // Coupe de la Ligue
              177 => [54029]], // Coupe de France
'NLD.CUP' => [103 => [57851, 57852, 57954, 57855, 57853]],
'RUS.CUP' => [190 => [53608, 53606]],
'PRT.CUP' => [102 => [53861],  // Taça de Portugal
              640 => [57122, 57124, 57125, 57123]], // Taça da Liga
'SCO.CUP' => [175 => [53839, 53840, 53841, 53838],  // FA Cup
              176 => [56801, 56799, 56802, 56803, 56800]], // League Cup
'UEL.CUP' => [ 18 => [54013, 54014, 54009]],
'UCL.CUP' => [ 10 => [54146, 54147, 54141, 59012, 59013, 59014, 59015, 59016, 59017, 59018, 59019, 59020, 59021, 59022]],
'BLR.CUP' => [200 => [54063]],
'UKR.CUP' => [188 => [58945, 58948, 58950, 58951, 58949]],
'BEL.CUP' => [126 => [52764]],

'ENG.SC'  => [173 => []],
'GER.SC'  => [638 => []],
'ITA.SC'  => [171 => []],
'ESP.SC'  => [186 => [57025, 57024]],
'FRA.SC'  => [312 => []],
'NLD.SC'  => [170 => []],
'RUS.SC'  => [242 => []],
'PRT.SC'  => [184 => []],
'UEL.SC'  => [211 => []],
'BLR.SC'  => [790 => []],
'UKR.SC'  => [298 => []],
'BEL.SC'  => [169 => []]
);

function parse_team_name($match, $side) {
    $fr = strpos($match, $side) + 6;
    $to = strpos($match, '/td>', $fr);
    $str = substr($match, $fr, $to - $fr);
    if ($fr = strpos($str, 'title="'))
    {
        $fr = $fr + 7;
        return trim(substr($str, $fr, strpos($str, '">', $fr) - $fr));
    }
    $fr = strrpos($str, '">') + 2;
    return trim(substr($str, $fr, strpos($str, '<', $fr) - $fr));
}

$out = [];
foreach ($ccodes as $cc => $competitions)
    foreach ($competitions as $competition => $rounds)
        foreach ($rounds as $round_id)
        {
            $tournament = explode('.', $cc)[1];
            $sw = file_get_contents('https://int.soccerway.com/a/block_competition_matches_full?block_id=page_competition_1_block_competition_matches_full_7&callback_params=%7B%22block_service_id%22%3A%22competition_summary_block_competitionmatchesfull%22%2C%22round_id%22%3A'.$round_id.'%2C%22outgroup%22%3Afalse%2C%22view%22%3A1%2C%22competition_id%22%3A'.$competition.'%7D&action=changeView&params=%7B%22view%22%3A2%7D');
            $decoded = json_decode($sw, true);
            $sw = $decoded['commands'][0]['parameters']['content'];
            $aweek = explode('class="no-date-repetition-new "', $sw);
            unset($aweek[0]);
            foreach ($aweek as $match)
            {
                if (strpos($match, 'PSTP'))
                    continue;

                $fr = strpos($match, "data-format='dd/mm/yyyy'>") + 25;
                $str = substr($match, $fr, 10);
                list($day, $month, $year) = explode('/', $str);
                if ($year.$month.$day < date('Ymd', strtotime('monday')))
                    continue;

                $home = parse_team_name($match, 'team-a');
                $away = parse_team_name($match, 'team-b');
                if ($fr = strpos($match, "'HH:MM'>"))
                {
                    $time = substr($match, $fr + 8);
                    $time = str_replace(' ', '', substr($time, 0, strpos($time, '<')));
                }
                else
                    $time = '19:00';

                $mtime = strtotime("$year/$month/$day");
                $week = date('W', $mtime);
                if (strpos($time, ':'))
                    $out["$year/$week"][$cc][] = "$home,$away,".date('m-d', $mtime).",$time,$tournament\n";

            }
        }


foreach ($out as $dir => $tournaments)
{
    if (!is_dir("$fixtures/$dir"))
        mkdir("$fixtures/$dir", 0755, true);

    foreach ($tournaments as $tournament => $matches)
    {
        $matches = array_unique($matches);
        if ($debug)
            echo "\n$dir/$tournament\n".implode('', $matches);
        else
            file_put_contents("$fixtures/$dir/$tournament", implode('', $matches));
    }
}
?>
