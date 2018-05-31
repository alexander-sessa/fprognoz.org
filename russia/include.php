<?php
$country = "russia";
//http://www.sovsport.ru/team-item/[Team]_6
$teamss = array(
"125" => "ALANIA",
"15" => "AMKAR",
"123" => "ANZHI",
"128" => "BALTIKA",
"414" => "VOLGA-T",
"442" => "KAVKAZ",
"121" => "DIN-B",
"23" => "DIN-M",
"10" => "DIN-ST",
"1151" => "DNEPRS",
"464" => "ENISEY",
"20" => "ZENIT-SP",
"438" => "ZENIT-2",
"1560" => "KOMETA",
"532" => "KRASNODAR",
"9" => "KRSOV",
"151" => "KUBAN",
"127" => "MASHUK",
"2434" => "MOSCOW",
"152" => "ROSTOV",
"491" => "ROTOR",
"17" => "RUBIN",
"439" => "SATURN",
"116" => "SIBIR",
"21" => "SPARTAK",
"133" => "TEKSTIL",
"22" => "TOMSK",
"517" => "TOR-V",
"119" => "TOR-M",
"1561" => "FAKEL",
"14" => "KHIMKI",
"2" => "CSKA",
);
$teams = array(
"6633" => "ALANIA",
"1851" => "AMKAR",
"1868" => "ANZHI",
"1875" => "BALTIKA",
"3997" => "VOLGA-T",
"6630" => "KAVKAZ",
"1866" => "DIN-B",
"1845" => "DIN-M",
"6632" => "DIN-ST",
"3959" => "DNEPRS",
"4094" => "ENISEY",
"1841" => "ZENIT-SP",
"18838" => "ZENIT-2",
"14976" => "KOMETA",
"10610" => "KRASNODAR",
"1848" => "KRSOV",
"1853" => "KUBAN",
"4093" => "MASHUK",
"2962" => "MOSCOW",
"1854" => "ROSTOV",
"13206" => "ROTOR",
"1852" => "RUBIN",
"1846" => "SATURN",
"6559" => "SIBIR",
"1844" => "SPARTAK",
"3992" => "TEKSTIL",
"1870" => "TOMSK",
"3994" => "TOR-V",
"1840" => "TOR-M",
"3748" => "FAKEL",
"1867" => "KHIMKI",
"1842" => "CSKA",
);
/*
"1871" => "CHERNO",
"3982" => "SKA-RD",
"1843" => "LOKO",
*/
$names = array(
"3748" => "Fakel",
"1853" => "Kuban' Krasnodar",
"1854" => "Rostov",
"1852" => "Rubin Kazan'",
"1841" => "Zenit",
"6633" => "Alaniya",
"3992" => "Tekstilshchik",
"3994" => "Torpedo Vladimir",
"1851" => "Amkar Perm'",
"4093" => "Mashuk-KMV",
"1842" => "CSKA Moskva",
"1875" => "Baltika",
"1844" => "Spartak Moskva",
"1845" => "Dinamo Moskva",
"1867" => "Khimki",
"4094" => "Yenisey",
"3997" => "Volga Tver",
"6559" => "Sibir",
"1840" => "Torpedo Moskva",
"1848" => "Krylya Sovetov",
"6630" => "Gazprom Transgaz",
"1868" => "Anzhi Makhachkala",
"10610" => "Krasnodar",
"14976" => "Kaluga",
"2962" => "Moskva",
"18838" => "Zenit St. Petersburg II",
"1866" => "Dinamo Bryansk",
"6632" => "Dinamo Stavropol",
"1846" => "Saturn Ramenskoye",
"1870" => "Tom' Tomsk",
"3959" => "Dnepr Smolensk",
"13206" => "Rotor Volgograd",
);
/*
"15158" => "Strogino",
"2962" => "Moskva",
"6632" => "Dinamo Stavropol",
"1871" => "Chernomorets",
"3982" => "SKA Rostov",
"1843" => "Lokomotiv Moskva",
*/
$swcodes = array(
"fc-alaniya-vladikavkaz" =>  "6633",
"fk-amkar-perm" =>  "1851",
"anzhi-makhachkala" =>  "1868",
"baltika-kaliningrad" =>  "1875",
"volga-tver" =>  "3997",
"kavkaztransgaz-ryzdvyanyi" =>  "6630",
"dinamo-briansk" =>  "1866",
"fk-dinamo-moskva" =>  "1845",
"dinamo-stavropol" =>  "6632",
"fk-smolensk" =>  "3959",
"metallurg-krasnoyarsk" =>  "4094",
"fk-zenit-sankt-petersburg" =>  "1841",
"fk-zenit-st-petersburg-ii" => "18838",
"fk-kaluga" => "14976",
"fk-krasnodar" => "10610",
"pfk-kryliya-sovetov-samara" =>  "1848",
"fc-kuban-krasnodar" =>  "1853",
"fk-mashuk-kmv-pyatigorsk" =>  "4093",
"fk-moskva" =>  "2962",
"fk-rostov-na-donu" =>  "1854",
"fk-volgograd" => "13206",
"rubin-kazan" =>  "1852",
"fk-saturn-moskovskaya" =>  "1846",
"sibir-novosibirsk" =>  "6559",
"fk-spartak-moskva" =>  "1844",
"fk-tekstilshchik-telecom-ivanovo" =>  "3992",
"tom-tomsk" =>  "1870",
"torpedo-vladimir" =>  "3994",
"fk-torpedo-moskva" =>  "1840",
"fakel-voronezh" =>  "3748",
"fk-khimki" =>  "1867",
"cska-moskva" =>  "1842",
);
$wiki = array(
"FAKEL" => "http://ru.wikipedia.org/wiki/%D0%A4%D0%B0%D0%BA%D0%B5%D0%BB_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%92%D0%BE%D1%80%D0%BE%D0%BD%D0%B5%D0%B6)",
"KUBAN" => "http://ru.wikipedia.org/wiki/%D0%9A%D1%83%D0%B1%D0%B0%D0%BD%D1%8C_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1)",
"ROSTOV" => "http://ru.wikipedia.org/wiki/%D0%A0%D0%BE%D1%81%D1%82%D0%BE%D0%B2_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1)",
"RUBIN" => "http://ru.wikipedia.org/wiki/%D0%A0%D1%83%D0%B1%D0%B8%D0%BD_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1)",
"ZENIT-SP" => "http://ru.wikipedia.org/wiki/%D0%97%D0%B5%D0%BD%D0%B8%D1%82_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%A1%D0%B0%D0%BD%D0%BA%D1%82-%D0%9F%D0%B5%D1%82%D0%B5%D1%80%D0%B1%D1%83%D1%80%D0%B3)",
"ALANIA" => "http://ru.wikipedia.org/wiki/%D0%90%D0%BB%D0%B0%D0%BD%D0%B8%D1%8F_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1)",
"TEKSTIL" => "http://ru.wikipedia.org/wiki/%D0%A2%D0%B5%D0%BA%D1%81%D1%82%D0%B8%D0%BB%D1%8C%D1%89%D0%B8%D0%BA_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%98%D0%B2%D0%B0%D0%BD%D0%BE%D0%B2%D0%BE)",
"TOR-V" => "http://ru.wikipedia.org/wiki/%D0%A2%D0%BE%D1%80%D0%BF%D0%B5%D0%B4%D0%BE_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%92%D0%BB%D0%B0%D0%B4%D0%B8%D0%BC%D0%B8%D1%80)",
"AMKAR" => "http://ru.wikipedia.org/wiki/%D0%90%D0%BC%D0%BA%D0%B0%D1%80",
"MASHUK" => "http://ru.wikipedia.org/wiki/%D0%9C%D0%B0%D1%88%D1%83%D0%BA-%D0%9A%D0%9C%D0%92",
"CSKA" => "http://ru.wikipedia.org/wiki/%D0%A6%D0%A1%D0%9A%D0%90_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0)",
"BALTIKA" => "http://ru.wikipedia.org/wiki/%D0%91%D0%B0%D0%BB%D1%82%D0%B8%D0%BA%D0%B0_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1)",
"SKA-RD" => "http://ru.wikipedia.org/wiki/%D0%A1%D0%9A%D0%90_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%A0%D0%BE%D1%81%D1%82%D0%BE%D0%B2-%D0%BD%D0%B0-%D0%94%D0%BE%D0%BD%D1%83)",
"LOKO" => "http://ru.wikipedia.org/wiki/%D0%9B%D0%BE%D0%BA%D0%BE%D0%BC%D0%BE%D1%82%D0%B8%D0%B2_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0)",
"SPARTAK" => "http://ru.wikipedia.org/wiki/%D0%A1%D0%BF%D0%B0%D1%80%D1%82%D0%B0%D0%BA_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0)",
"DIN-M" => "http://ru.wikipedia.org/wiki/%D0%94%D0%B8%D0%BD%D0%B0%D0%BC%D0%BE_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0)",
"KHIMKI" => "http://ru.wikipedia.org/wiki/%D0%A5%D0%B8%D0%BC%D0%BA%D0%B8_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1)",
"ENISEY" => "http://ru.wikipedia.org/wiki/%D0%95%D0%BD%D0%B8%D1%81%D0%B5%D0%B9_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1)",
"VOLGA-T" => "http://ru.wikipedia.org/wiki/%D0%92%D0%BE%D0%BB%D0%B3%D0%B0_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%A2%D0%B2%D0%B5%D1%80%D1%8C)",
"SIBIR" => "http://ru.wikipedia.org/wiki/%D0%A1%D0%B8%D0%B1%D0%B8%D1%80%D1%8C_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%9D%D0%BE%D0%B2%D0%BE%D1%81%D0%B8%D0%B1%D0%B8%D1%80%D1%81%D0%BA)",
"TOR-M" => "http://ru.wikipedia.org/wiki/%D0%A2%D0%BE%D1%80%D0%BF%D0%B5%D0%B4%D0%BE_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0)",
"CHERNO" => "http://ru.wikipedia.org/wiki/%D0%A7%D0%B5%D1%80%D0%BD%D0%BE%D0%BC%D0%BE%D1%80%D0%B5%D1%86_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%9D%D0%BE%D0%B2%D0%BE%D1%80%D0%BE%D1%81%D1%81%D0%B8%D0%B9%D1%81%D0%BA)",
"KRSOV" => "http://ru.wikipedia.org/wiki/%D0%9A%D1%80%D1%8B%D0%BB%D1%8C%D1%8F_%D0%A1%D0%BE%D0%B2%D0%B5%D1%82%D0%BE%D0%B2_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%A1%D0%B0%D0%BC%D0%B0%D1%80%D0%B0)",
"KAVKAZ" => "http://ru.wikipedia.org/wiki/%D0%9A%D0%B0%D0%B2%D0%BA%D0%B0%D0%B7%D1%82%D1%80%D0%B0%D0%BD%D1%81%D0%B3%D0%B0%D0%B7-2005",
"ANZHI" => "http://ru.wikipedia.org/wiki/%D0%90%D0%BD%D0%B6%D0%B8",
"KRASNODAR" => "http://ru.wikipedia.org/wiki/%D0%9A%D1%80%D0%B0%D1%81%D0%BD%D0%BE%D0%B4%D0%B0%D1%80_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1)",
"MOSCOW" => "http://ru.wikipedia.org/wiki/%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1)",
"ZENIT-2" => "http://ru.wikipedia.org/wiki/%D0%A1%D0%BC%D0%B5%D0%BD%D0%B0-%D0%97%D0%B5%D0%BD%D0%B8%D1%82",
"DIN-B" => "http://ru.wikipedia.org/wiki/%D0%94%D0%B8%D0%BD%D0%B0%D0%BC%D0%BE_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%91%D1%80%D1%8F%D0%BD%D1%81%D0%BA)",
"KOMETA" => "http://ru.wikipedia.org/wiki/%D0%9A%D0%B0%D0%BB%D1%83%D0%B3%D0%B0_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1)",
"DIN-ST" => "http://ru.wikipedia.org/wiki/%D0%94%D0%B8%D0%BD%D0%B0%D0%BC%D0%BE_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%A1%D1%82%D0%B0%D0%B2%D1%80%D0%BE%D0%BF%D0%BE%D0%BB%D1%8C)",
"SATURN" => "http://ru.wikipedia.org/wiki/%D0%A1%D0%B0%D1%82%D1%83%D1%80%D0%BD_(%D1%84%D1%83%D1%82%D0%B1%D0%BE%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%BA%D0%BB%D1%83%D0%B1,_%D0%A0%D0%B0%D0%BC%D0%B5%D0%BD%D1%81%D0%BA%D0%BE%D0%B5,_1991)",
);
?>
