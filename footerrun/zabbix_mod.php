<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
$md=new Tmod; // обьявляем переменную для работы с классом модуля
if ($md->IsActive("zabbix-mon")==1) {
    if (_GET("printable")!="true"){
	?>
	 <script type="text/javascript" src="controller/client/js/zabbix_mod.js"></script>
	<?php
    };
};
unset($md);