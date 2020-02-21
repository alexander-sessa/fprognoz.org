<?php
if (isset($ref))
{
    switch ($ref)
    {
        case 'news': $f = 'news'; break;
        case 'itog': $t = lcfirst($t);
        case 'it'  : $f = isset($t) ? 'publish/'.$league.'it'.$tt : 'it.tpl'; break;
        case 'itc' : $f = isset($t) ? 'publish/'.$league.'it'.$tt : 'itc.tpl'; break;
        case 'prog': $t = lcfirst($t);
        case 'p'   : $f = isset($t) ? ($a == 'sfp-team' ? 'programs/'.$l : 'publish/p').$t : 'p.tpl'; break;
        case 'pc'  : $f = isset($t) ? 'publish/p'.$t : 'pc.tpl'; break;
        case 'rev' : $t = lcfirst($t);
        case 'r'   : $f = isset($t) ? 'publish/'.$league.'r'.$tt : 'header'; break;
    }
    $content = is_file($season_dir . $f) ? file_get_contents($season_dir . $f) : 'файл не найден';
}

/*
выявление декораций и их замена
*/
function textAnalyze($lines) {
    $flags = [
        'text-center' => true,
        'text-flex'  => false,
        'text-table' => 0,
    ];
    foreach ($lines as $line)
    {
        // выявление отступов всех строк в параграфе по серии пробелов
        // для последующей интерпретации параграфа как <div class="text-center">
        if (substr($line, 0, 3) != '   ')
            $flags['text-center'] = false;

        // выявление колонок текста в пределах параграфа по серии пробелов посреди строки
        // для последующей интерпретации параграфа во flex
        if (strpos(trim($line), '   '))
            $flags['text-flex'] = true;

        // выявление таблиц
        $firstChar = mb_substr(trim($line), 0, 1);
        if (in_array($firstChar, ['|', '│']))
            $flags['text-table']++;

    }
    return $flags;
}

// замена строки вида "текст:ссылка" на текст со ссылкой или вставка тэга ссылки вместо ссылки
function convertLink($line) {
    $line = html_entity_decode($line);
    if ($link = mb_strpos($line, 'http'))
    {
        if (($colon = mb_strpos($line, ':')) && $colon < $link)
            $line = '<a href="'.trim(mb_substr($line, $link)).'">'.htmlentities(mb_substr($line, 0, $colon)).'</a>';
        else
            $line = mb_substr($line, 0, $link).'<a href="'.trim(mb_substr($line, $link)).'">'.htmlentities(mb_substr($line, $link)).'</a>';
    }
    if (strpos($line, '--------') !== false)
        $line = '
            <hr>';

    return $line;
}

// парсинг таблицы
function htmlTable($lines) {
    $columns = 0;
    $tableArray = [['', '<hr>']];
    $html = '
        <div class="text-center">';
    $title = false;
    foreach ($lines as $n => $line)
        if (trim($line))
        {
            $firstChar = mb_substr(trim($line), 0, 1);
            if (!in_array($firstChar, ['|', '│']))
            {
                if (isset($divider))
                    $tableArray[] = ['', '<hr>'];
                else
                {
                    $firstChar = mb_substr(trim($lines[$n + 1]), 0, 1);
                    if (!in_array($firstChar, ['|', '│']))
                    {
                        $html .= '
            <br>
            ' . ($title == false ? '<strong>'.$line.'</strong>' : $line);
                        $title = true;
                    }
                }
            }
            else
            {
                $divider = $divider ?? $firstChar;
                if (strpos($line, '---') === false)
                {
                    $row = explode($divider, $line);
                    $tableArray[] = $row;
                    $columns = max($columns, sizeof($row));
                }
                else
                    $tableArray[] = ['', '<hr>'];

            }
        }

    $html .= '
            <center>
            <table class="table-condensed text-left w-auto">';
    foreach ($tableArray as $n => $row)
    {
        $html .= '
                <tr>';
        array_shift($row);
        foreach ($row as $m => $col)
        {
            $span = $columns - sizeof($row);
            if ($m == 1)
            {
                $colStrLen = strlen($col) - 2; // длина без учёта крайних пробелов
                $col = trim($col);
                if ($colStrLen - strlen($col) < 2)
                {
                    $cut = strrpos($col, ' ');
                    $col = trim(substr($col, 0, $cut)).'<div class="float-right mr-2">'.substr($col, $cut).'</div>';
                }
            }
            $html .= '
                    <td'.($span > 1 ? ' colspan="'.$span.'"' : '').'>'.($n == 0 ? '<strong>'.$col.'</strong>' : $col).'</td>';
        }
        $html .= '
                </tr>';
    }
    $html .= '
            </table>
            </center>
        </div>';
    return $html;
}

// разборка параграфа на 2 или 3 колонки
function htmlFlex($lines) {
    // поиск позиций колонок
    $columns = [];
    foreach ($lines as $line)
    {
        $line = rtrim(html_entity_decode($line));
        if (($pos = mb_strrpos($line, '   ')) && trim(mb_substr($line, 0, $pos)))
        {
            if (!in_array($pos + 3, $columns))
                $columns[] = $pos + 3;

            $line = rtrim(mb_substr($line, 0, $pos));

            if (($pos = mb_strrpos($line, '   ')) && trim(mb_substr($line, 0, $pos)))
            {
                if (!in_array($pos + 3, $columns))
                    $columns[] = $pos + 3;

                $line = rtrim(mb_substr($line, 0, $pos));
                if (($pos = mb_strrpos($line, '   ')) && !in_array($pos + 3, $columns) && trim(mb_substr($line, 0, $pos)))
                    $columns[] = $pos + 3;

            }
        }
    }
    sort($columns);
    $html = '
        <center>
            <table class="table-condensed my-3">';
    foreach ($lines as $line)
    {
        unset($prev);
        $html .= '
                <tr>';
        foreach ($columns as $pos)
        {
            $prev = $prev ?? 0;
            $html .= '
                    <td style="min-width:192px;'.(sizeof($lines) == 1 ? 'text-align:center;font-weight:bold' : '').'">'.convertLink(mb_substr(html_entity_decode($line), $prev, $pos - $prev)).'</td>';
            $prev = $pos;
        }
        $html .= '
                    <td style="min-width:192px;'.(sizeof($lines) == 1 ? 'text-align:center;font-weight:bold' : '').'">'.convertLink(mb_substr(html_entity_decode($line), $prev)).'</td>
                </tr>';
    }
    $html .= '
            </table>
        </center>';
    return $html;
}

function text2html($text) {
    $lines = explode("\n", $text);
    $flags = textAnalyze($lines);
    $classes = '';
    foreach ($flags as $class => $use)
        if ($use)
            $classes .= ' ' . $class;

    $html = '';
    // выявление декоративных строк-разделителей с "  ===" и спецобработка предыдущей строки
    if (strpos($lines[1], '==='))
    {
        $html .= '
    <div class="text-center h6 m-2" style="max-width:576px">
        ' . array_shift($lines) . '
    </div>';
        array_shift($lines);
    }
    $html .= '
    <div class="' . trim($classes) . '" style="max-width:576px">';
    if ($flags['text-table'])
        $html .= htmlTable($lines);
    else if ($flags['text-flex'])
        $html .= htmlFlex($lines);
    else
    {
        $html .= '
        <p>';
        foreach ($lines as $n => $line)
        {
            $html .= '
            ' . (in_array($html[-1], ['.', ';', ':', '-', '!', '*']) || strpos($line, ' - ') ? '<br>
            ' : '') . convertLink($line);
        }
        $html .= '
        </p>';
    }
    $html .= '
    </div>';
    return $html;
}

// разбиение текста на параграфы по пустой строке (\n\n)
$parts = explode("\n\n", $content);
$html = '';
foreach ($parts as $part)
    $html .= text2html($part);

echo $html;

?>