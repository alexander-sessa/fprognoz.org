<?php
if ($_GET['l'] == 'msl')
{
    switch ($_SESSION['Coach_name'])
    {
        case 'Андрей Новиков': $name = 'Севас'; break;
        case 'Владимир': $name = 'Vladimir36'; break;
        case 'Semion Vasserman': $name = 'VIVA'; break;
        case 'Александр Сесса': $name = 'Sessa'; break;
        default : $name = $_SESSION['Coach_name'];
    }
    $teams = [
        'Fprognoz.com' => 'Fprognoz.com',
        'Kins Forecasts' => 'Kins Forecasts',
        'PrimeGang' => 'PrimeGang',
        'SEclub' => 'SEclub',
        'SFP' => 'SFP',
        'Sport Contest' => 'Sport Contest',
        'ФК Форвард' => 'ФК Форвард',
    ];
}
else
{
    switch ($_SESSION['Coach_name'])
    {
        case 'AnDrusha': $name = 'Андрей Вышинский'; break;
        case 'Vladimir36': $name = 'Владимир'; break;
        case 'Semion Vasserman': $name = 'VIVA'; break;
        default : $name = $_SESSION['Coach_name'];
    }
    $teams = [
        'BLR' => 'Сборная Беларуси',
        'ENG' => 'Сборная Англии',
        'ESP' => 'Сборная Испании',
        'FRA' => 'Сборная Франции',
        'GER' => 'Сборная Германии',
        'ITA' => 'Сборная Италии',
        'NLD' => 'Сборная Голландии',
        'PRT' => 'Сборная Португалии',
        'RUS' => 'Сборная России',
        'SCO' => 'Сборная Шотландии',
        'SUI' => 'Сборная Швейцарии',
        'UKR' => 'Сборная Украины'
    ];
}
$myteam = '';
foreach ($teams as $team => $team_name)
{
    $squad = file('/home/fp/data/online/UNL/'.date('Y').'/'.$team.'.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($squad as $player)
        if ($name == substr($player, 0, strpos($player, ';')))
        {
            $myteam = $team;
            if (strpos($player, ';coach;'))
                $role = 'president';

            break 2;
        }
}
if ($myteam)
{
    echo '
<h4>' . $team_name . '. Раздевалка</h4>
<p>Доступ к информации на этой странице открыт только участникам команды:<br>
';
    foreach ($squad as $player)
        echo substr($player, 0, strpos($player, ';')) . '<br>
';

    echo '</p>';
}
else
    echo 'Не удалось определить, в какой команде Вы играете, или у вашей команды нет Раздевалки :-(';
?>