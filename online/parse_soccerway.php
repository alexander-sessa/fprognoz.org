#!/usr/bin/php
<?php
date_default_timezone_set('UTC');
$fixtures = '/home/fp/data/online/fixtures';

$debug = false;

# countryCode.tournament => [competition_id => [round_id, ...], ...]
$ccodes = array(
'INT.ACN' => [385 => [55283, 55281, 55284, 55285, 55280, 55282]],
'INT.CA'  => [288 => [55092, 55093, 55094, 55090, 55091]],
'INT.EC'  => [ 25 => [38188, 38186, 38189, 38190, 38187]],
'INT.ECQ' => [1000=> []],
'INT.UNL' => [1661=> [54499, 54500, 54501, 54502, 54503, 54497, 54498, 57351]],
'INT.WC'  => [ 72 => [49519, 49517, 49520, 49521, 49516, 49518]],
'INT.OG'  => [221 => [52381, 52382, 52383, 52380, 52379]],
'INT.WCQ' => [224 => [46192, 46193],
              225 => [44085],
              227 => [44081, 44083]],
'INT.CC'  => [246 => [55096, 55097, 55098, 55095]],
'INT.CCC' => [247 => []],
'INT.CNL' => [1728=> [46557, 46558, 46559]],
'INT.CLU' => [284 => [62659, 62663, 62661, 62660, 62662], // Club World Cup
              1148=> [55628], // International Champions Cup
              1407=> [], // Emirates Cup
              1442=> [55686, 55684, 55685]],// Audi Cup
'INT.FRI' => [430 => [61277, 61278, 61279]],

'ENG.PL'  => [  8 => [63396]],
'ENG.D1'  => [ 70 => [63602]],
'GER.PL'  => [  9 => [63141],
              931 => []],
'ITA.PL'  => [ 13 => [63208]],
'ESP.PL'  => [  7 => [63145]],
'ESP.D1'  => [ 12 => []],
'FRA.PL'  => [ 16 => [63317]],
'NLD.PL'  => [  1 => [63325]],
'RUS.PL'  => [121 => [63064]],
'RUS.D1'  => [122 => [63073]],
'PRT.PL'  => [ 63 => [63146]],
'SCO.PL'  => [ 43 => [63065]],
'BLR.PL'  => [ 66 => [61317]],
'UKR.PL'  => [125 => [63018]],
'BEL.PL'  => [ 24 => [63268]],
'TUR.PL'  => [ 19 => [63067]],
'AUT.PL'  => [ 49 => [63649, 63646, 63547, 63648]],
'SUI.PL'  => [ 27 => [63225]],

'ENG.CUP' => [ 93 => [64085, 64087, 64089, 64093, 64094, 64091], // FA Cup
               95 => [62861, 62862, 62863, 62865, 62866, 62864]], // League Cup 62860
'GER.CUP' => [104 => [63037, 63038, 63039, 63041, 63042, 63040]],
'ITA.CUP' => [135 => [63967, 63968, 63970, 63971, 63969]],
'ESP.CUP' => [138 => [62780, 62778, 62781, 62784, 62785, 62782]],
'FRA.CUP' => [105 => [],  // Coupe de la Ligue
              177 => [63944, 63947, 63950, 63951, 63949]], // Coupe de France
'NLD.CUP' => [103 => []],
'RUS.CUP' => [190 => [63010, 63008, 63011, 63012, 63009]],
'PRT.CUP' => [102 => [63436, 63437, 63439, 63440, 63438],  // Taça de Portugal
              640 => [61953, 61954, 61952]], // Taça da Liga
'SCO.CUP' => [175 => [63290, 63292, 63293, 63291],  // FA Cup
              176 => [62348, 62346, 62349, 62350, 62347]], // League Cup
'UEC.CUP' => [2187 => [62280, 62281, 62282, 62283, 62284, 62285, 62286, 62287, 62288, 62289]],
'UEL.CUP' => [ 18 => [63668, 63669, 63673, 63672, 63666, 63670, 63675, 63676, 63671]],
'UCL.CUP' => [ 10 => [63485, 63486, 63490, 63489, 63487, 63493, 63494, 63488]],
'BLR.CUP' => [200 => [63047, 63048, 63049, 63050, 63051]],
'UKR.CUP' => [188 => [62998, 62999, 63000, 63001, 63003, 63002]],
'BEL.CUP' => [126 => [62962, 62965, 62966, 62963]],
'SUI.CUP' => [114 => [63836, 63838, 63839, 63837]],

'ENG.SC'  => [173 => [59864]],
'GER.SC'  => [638 => [60376]],
'ITA.SC'  => [171 => []],
'ESP.SC'  => [186 => [61906, 61905]],
'FRA.SC'  => [312 => [61904]],
'NLD.SC'  => [170 => [55291]],
'RUS.SC'  => [242 => [59459]],
'PRT.SC'  => [184 => [61616]],
'UEL.SC'  => [211 => [60503]],
'BLR.SC'  => [790 => []],
'UKR.SC'  => [298 => [60052]],
'BEL.SC'  => [169 => [55248]]
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
                $week = date('W', $mtime - 86400);      // наша "неделя" начинается во вторник!
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
