# Сервер футбольного прогнозирования fprognoz.org
В этом проекте собраны рабочие скрипты сервера.
Сам сервер разделяется на 2 части: web-компоненты и данные.
Скрипты, обеспечивающие сервер данными также включены в проект.
## Структура web-сервера
* В корне лежат **index.php** и файлы для браузеров и роботов
* **comments** - система комментирования
* **online** - вспомагательные скрипты
* **js** и **css** - понятно из названий
* **images** - изображения в репозиторий не включены (необходимый минимум есть в **vagrant/images.tgz**), также как и копия первой версии сайта из каталога **old**
* **fifa** - каталог "Федерации Игровых Футболпрогнозных Ассоциаций" - главный каталог сайта
* **uefa**, **italy** и прочие каталоги турниров и футбол-прогнозных ассоциаций (стран) - итальянский каталог надо выделить особо, покольку исторически сложилось так, что именно в нем лежат скрипты, используемые другими ассоциациями и турнирами посредством симлинков
* **vagrant** - скрипт с примером настроек и данных для быстрого развёртывания сервера на виртуальной машине

### Структура каталогов турниров и ассоциаций, включая ФИФА, сходная:
* **settings.inc.php** - настройки
* прочие скрипты, на которые ведут ссылки из колонок навигации и меню

### Скрипты из **online**:
* **cale.php** - создание календаря группового этапа и списка прогнозов-генераторов для еврокубковых групповых турниров
* **cal6.php** - создание календаря группового турнира для 6 участников
* **draw.php** - жеребьёвка и создание списка прогнозов-генераторов для кубковых турниров

Следующие скрипты не онлайновые, но тоже лежат здесь:
* **ajax.php** - обработчик ajax-запросов
* **build_results.php** - скрипт автоматического формирования страниц итогов туров
* **cron.php** - cron-скрипт, выполняющийся каждую минуту; фукции: мониторинг событий и получение свежих результатов
* **newrank.php** - cron-скрипт построения внутреннего рейтинга реальных команд, запускается раз в сутки, выполняется 1-2 раза в месяц 
* **parse_soccerway.php** - cron-скрипт получения календарей турниров, выполняется раз в сутки
* **scanmail.php** - incron-скрипт получения прогнозов, присланных по e-mail
* **xscores.php** - cron-скрипт получения расписаний матчей на ближайшие 2 недели, выполняется раз в сутки
* **swiss.inc.php** - скрипт жеребьёвки тура по швейцарской системе, **swiss.php** - демонстрационная модель турнира по шверцарской системе

### Компоненты для других скриптов:
* **cc.inc.php** - данные для генераторов программок
* **cnames.inc.php**, **en.teams.tsv** - названия стран для скрипта **newrank.php**
* **realteam.inc.php** - "переводчик" названий команд в варианты, используемые сервером  
* **translate.inc.php** - обратный переводчик для генератора программок 
* **tournament.inc.php** и **xscoregroups.inc.php** - сокращения и полные названия турниров 

Здесь также могут оказаться другие вспомагательные скрипты, которые пока не включены в общий функционал сервера

## Структура данных сервера
Данные могут находиться в любом месте, файл **config.inc.php**, указывающий на их расположение, должен быть включен в **index.php**.
Кроме этого в конфигурации указывается информация для использования в системе комментариев СУБД Redis.
Пример:
```php
<?php
$data_dir = '/path/to/data/';
$online_dir = $data_dir . 'online/';
$admin = ['Admin', 'Администратор', 'Иван Кузнецов'];

$have_redis = false; // при false используется redis_emu
$redis_host = 'somename.xoo62h.ng.0001.use2.cache.amazonaws.com';
$redis_port = 6379;

$salt = 'произвольная фраза для защиты передаваемых данных';
$recaptcha_secret = 'секретный ключ google recaptcha';
$recaptcha_sitekey = 'ключ сайта google recaptcha';

$mail_server = '{fprognoz.org:993/imap/ssl}INBOX';
$mail_user = 'somename';
$mail_password = 'пароль для почты';
```

* **auth** - внутри файл управляющий доступом на сервер и могут быть технические скрипты 
* **personal** - персональные данные пользователей
* **redis-emu** - хранилище данных эмулятора СУБД Redis, используется при отсутствии собственно Redis-а
* **online** - рабочие данные, внутри лежат:
* каталоги турниров и ассоциаций
  * **QUOTAS** - данные о квотах игроков (файл **qb**), используется также как хранилище материалов ФИФА
  * **fixtures** - расписание игр реальных клубных и сборных команд
  * **konkurs** - данные конкурсов на свободные команды
  * **log** - журналы работы некоторых скриптов, а также файлы блокировки доступа
  * **ranking** - данные для построения внутреннего рейтинга реальных команд
  * **results** - результаты реальных матчей и расписание на ближайшие 2 недели
  * **schedule** - расписание событий для отслеживание крон-скриптом
  * **vacancy** - хранилище заявок на свободные команды

Большинство каталогов данных просты по структуре и ведутся автоматически скриптами сервера, но описание типичного каталога турнира или ассоциации требует пояснения. Внутрення структура такова (может отличаться в зависимости от используемых скриптов):
* **passwd** - каталог с файлами паролей владельцев команд
* **emails** - адреса и коды команд руководителей турнира или ассоциации, используется для исключения адреса из отправки копии прогноза, если один из руководителей - сопереник автора прогноза
* каталоги сезонов с названием в виде 4 цифры года или 4 цифры года,"минус" и 2 цифры следующего года (указывается в конфигурации турнира или ассоциации), внутри которых довольно сложная структура:
  * **bomb** - а также похожие по названию - протоколы матчей с указанием виртуальных бомбардиров для чемпиолната, кубка, суперкубка
  * **prognoz** - прогнозы игроков и технические данные по турам сезона 
  * **programs** - рабочие программки туров
  * **publish** - все публикации: пресс-релизы, полученные прогнозы, программки, итоги и обзоры туров 
  * **bombers** - файл виртуальных бомбардиров (составов виртуальных команд)
  * **cal** - а также похожие по названию файлы - календарь чемпионата, кубка, суперкубка, плей-офф
  * **cards** - а также похожие по названию файлы - наказния игроков за нарушения в соответствующих турнирах
  * **codes.tsv** - список команд и игроков турнира или ассоциации
  * **fp.cfg** - конфигурация турнира: количество туров, групп (лиг), кругов и т.п.
  * **gen**, **genc** - прогнозы генераторы на чемпионат (основной турнир) и кубок
  * **news** - текст, который показывается на странице сезона
  * **p.tpl**, **pc.tpl** - шаблоны программок туров чемпионата и кубка
  * **it.tpl**, **itc.tpl** - шаблоны итогов туров чемпионата и кубка
  * **header** - заголовок обзоров
  * **horse**, **raven**, **superb** - а также похожие по названию файлы - накопительные данные для номинаций "Темная лошадка", "Белая ворона" и "Супер-бутса" чемпионата и кубка

## Зависимости
* Для приёма прогнозов по почте требуются imap-сервер (dovecot-imapd) и incron, в задачах incron дожна быть указана **/var/mail/ваш_почтовый_ящик IN_CLOSE_WRITE /путь_к_сайту/fprognoz.org/online/scanmail.php**
* Используются следующие модули php: php-imap (для приёма прогнозов по почте), php-mbstring, php-tidy (для сохранения в красивом формате текстов после CKEditor), php-gd, php-xml и php-zip (эти 3 требуются для построения внутреннего рейтинга реальных команд)
* Для извлечения рейтинга команд из файлов в XLS-формате используется **phpoffice/phpspreadsheet**, установленный посредством **composer**

## Быстрое развёртывание сервера с использованием vagrant + VirtualBox
* Установите vagrant и Oracle VirtualBox
* Инициализируйте виртуальный сервер командой vagrant init ubuntu/bionic64
* Внесите необходимые правки в Vagrantfile (проброс порта 80, ограничение памяти - нам достаточно 512 и т.п.)
* Положите рядом файлы из каталога **vagrant** (они provision-скриптом)
* Поднимите сервер с использованием provision-скрипта **bootstrap.sh**

Больше полезной информации для огранизаторов турниров есть на самом web-сервере в файле **fifa/help-hq.inc.php**

