<h4>&nbsp;&nbsp;&nbsp;API для отправки прогнозов</h4>
<p>
Кроме возможностей отправить прогнозы со страниц сайта и по e-mail, мы предоставляем
API для интегрирования отправки в ваше ПО.
</p>
<p>
Сейчас API поддерживает два формата подачи прогнозов - индивидуальный и командный.<br>
Отправьте на <b>https://fprognoz.org/online/ajax.php</b> POST-запрос следующего содержания:
</p>
<p>
<b>data = <span class="small">"<?=$apikey?>"</span></b><br>
 &nbsp; - это ваш API Key - держите его в секрете!<br>
<b>tour = "код тура"</b><br>
<b>team  = "название сборной, а для сборных ФП-ассоциаций - код страны"</b><br>
 &nbsp; - только для управления сборной<br>
<b>players = ["имя игрока", ...]</b><br>
 &nbsp; - только для управления сборной - упорядоченный список игроков<br>
<b>team_codes = ["название сборной или код команды, а для сборных ФП-ассоциаций - код страны", ...]</b><br>
 &nbsp; - только для индивидуального прогноза; для Лиги Наций и еврокубков можно указывать несколько команд<br>
<b>predicts = ["строка прогноза", ...]</b><br>
 &nbsp; - прогнозы для игроков, перечисленных в players, или для команд из team_codes.<br>
Если количество элементов массива predicts меньше чем players или team_codes, для "лишних" игроков
или команд будет использован последний вариант строки прогноза.<br>
В прогнозах для Лиги Наций допускаются раздаляющие пробелы.<br>
В прогнозах с указанием серии пенальти, номера выбранных для пенальти матчей указываются через запятую после
основного прогноза и ключевой подстроки " pen - ", например:<br>
<b>predicts = ["12221XXX1(2)2 12X2<1 pen - 12,13,14,15,10"]</b>
</p>
<p>Будьте внимательны: "X" в прогнозе - это заглавная латинская буква "X"!<br>
<p>
В зависимости от результата обработки запроса могут быть такие ответы:<br>
"&lt;i class="fas fa-times text-danger"&gt;&lt;/i&gt; Ваши полномочия не подтверждены.&lt;br&gt;"<br>
"&lt;i class="fas fa-times text-danger"&gt;&lt;/i&gt; Увы, Вы опоздали - дедлайн уже наступил.&lt;br&gt;"<br>
"&lt;i class="fas fa-times text-danger"&gt;&lt;/i&gt; Получен пустой прогноз.&lt;br&gt;"<br>
"&lt;i class="fas fa-check text-success"&gt;&lt;/i&gt; Состав команды записан.&lt;br&gt;"<br>
"&lt;i class="fas fa-check text-success"&gt;&lt;/i&gt; Принят прогноз от 'имя или код команды' на тур 'код тура'.&lt;br&gt;"<br>
</p>
<p>
При указании недействительного API Key или отсутствии одного из праметров team или team_codes, запрос будет игнорирован.
</p>