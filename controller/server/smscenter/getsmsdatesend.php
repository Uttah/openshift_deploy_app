<?php

/*
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф *
 * Если исходный код найден в сети - значит лицензия GPL v.3 *
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс *
 */
include_once ("../../../config.php"); // загружаем первоначальные настройки
                                      
// загружаем классы

include_once ("../../../class/sql.php"); // загружаем классы работы с БД
include_once ("../../../class/config.php"); // загружаем классы настроек
include_once ("../../../class/users.php"); // загружаем классы работы с пользователями
include_once ("../../../class/cconfig.php"); // загружаем классы настроек
                                            
// загружаем все что нужно для работы движка

include_once ("../../../inc/connect.php"); // соеденяемся с БД, получаем $mysql_base_id
include_once ("../../../inc/config.php"); // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once ("../../../inc/functions.php"); // загружаем функции
include_once ("../../../inc/login.php"); // загружаем функции

$set = _GET("set");
$time_to = _GET("sec");
if ($time_to == "") {
    $time_to = 0;
}
;

$tsms = new Tcconfig();
$dtsms = $tsms->GetByParam("datetimetosmssend");
if (($dtsms == "") or ($set == true)) {
    $dtsms = microtime(true) + $time_to * 60;
    $tsms->SetByParam("datetimetosmssend", $dtsms);
}
;
$nw = intval(round($dtsms - microtime(true), 0));
if ($nw >= 0) {
    echo "Отправлять СМС начнем через:" . $nw . " сек.";
} else {
    echo "СМС сейчас отправляем..";
}
;
?>