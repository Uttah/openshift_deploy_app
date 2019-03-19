<?php

/*
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

if ($user->TestRoles("1,2,3,4,5,6")) {
	$md = new Tmod; // обьявляем переменную для работы с классом модуля
	$md->Register("astra", "Управление серверами Astra", "Грибов Павел");
	if ($md->IsActive("astra") == 1) {
		unset($md);
		$this->Add("main","<i class='fa fa-cog fa-fw'> </i>","Astra", "Настройка серверов Астра", 2, "astra", "");
		$this->Add("astra","<i class='fa fa-desktop fa-fw'> </i>", "Мониторинг", "Настройка серверов Астра", 2, "astra/mon", "astra/mon");
		$this->Add("astra","<i class='fa fa-desktop fa-fw'> </i>", "Внешний мониторинг", "Внешний мониторинг", 2, "astra/mon2", "astra/mon2");
		$this->Add("astra","<i class='fa fa-info fa-fw'> </i>", "Инфоканал", "Настройка серверов Астра", 2, "astra/pic", "astra/pic");
		$this->Add("astra","<i class='fa fa-list fa-fw'></i>", "Список серверов", "Настройка серверов Астра", 2, "astra/config", "astra/config");
	}
}
if ($user->TestRoles("1,2,3,4,5,6")) {
	$md = new Tmod; // обьявляем переменную для работы с классом модуля
	$md->Register("pbi", "Управление станциями PBI", "Грибов Павел");
	if ($md->IsActive("pbi") == 1) {
		unset($md);
		$this->Add("main","<i class='fa fa-cog fa-fw'> </i>","PBI", "Управление PBI", 2, "pbi", "");
		$this->Add("pbi","<i class='fa fa-list fa-fw'></i>", "Список станций", "Настройка списка станций PBI", 2, "pbi/pbilist", "PBI/pbilist");
	}
}