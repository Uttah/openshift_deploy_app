<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("scriptalert", "Мониторинг выполнения скриптов", "Грибов Павел"); 
if ($md->IsActive("scriptalert")==1) {    
 $this->Add("config","<i class='fa fa-bell-o fa-fw'> </i>", "Мониторинг скриптов", "Мониторинг выполнения скриптов", 0, "config/scriptalert","scriptalert");                     
 $this->Add("config","<i class='fa fa-bell-o fa-fw'> </i>", "Просрочки", "Мониторинг выполнения скриптов", 0, "config/scriptalert","scriptalert_mon");                     
};
unset($md);