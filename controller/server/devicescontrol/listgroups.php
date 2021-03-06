<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
include_once ("../../../config.php"); // загружаем первоначальные настройки
                                      
// загружаем классы

include_once ("../../../class/sql.php"); // загружаем классы работы с БД
include_once ("../../../class/config.php"); // загружаем классы настроек
include_once ("../../../class/users.php"); // загружаем классы работы с пользователями
include_once ("../../../class/employees.php"); // загружаем классы работы с профилем пользователя
                                              
// загружаем все что нужно для работы движка

include_once ("../../../inc/connect.php"); // соеденяемся с БД, получаем $mysql_base_id
include_once ("../../../inc/config.php"); // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once ("../../../inc/functions.php"); // загружаем функции
include_once ("../../../inc/login.php"); // загружаем функции

if (isset($_GET["page"])) {
    $page = $_GET['page'];
} else {
    $page = "";
}
;
if ($page == 0) {
    $page = 1;
}
;
if (isset($_GET["rows"])) {
    $limit = $_GET['rows'];
} else {
    $limit = "";
}
;
if (isset($_GET["sidx"])) {
    $sidx = $_GET['sidx'];
} else {
    $sidx = "";
}
;
if (isset($_GET["sord"])) {
    $sord = $_GET['sord'];
} else {
    $sord = "";
}
;
if (isset($_GET["orgid"])) {
    $orgid = $_GET['orgid'];
} else {
    $orgid = "";
}
;
if (isset($_POST["oper"])) {
    $oper = $_POST['oper'];
} else {
    $oper = "";
}
;

if (isset($_POST["id"])) {
    $id = $_POST['id'];
} else {
    $id = "";
}
;
if (isset($_POST["dgname"])) {
    $dgname = $_POST['dgname'];
} else {
    $dgname = "";
}
;
if (isset($_POST["dcomment"])) {
    $dcomment = $_POST['dcomment'];
} else {
    $dcomment = "";
}
;

if ($oper == '') {
    if (! $sidx)
        $sidx = 1;
    $result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM devgroups");
    $row = mysqli_fetch_array($result);
    $count = $row['count'];
    
    if ($count > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        $total_pages = 0;
    }
    ;
    if ($page > $total_pages)
        $page = $total_pages;
    
    $start = $limit * $page - $limit;
    $SQL = "SELECT id,dgname,dcomment FROM devgroups ORDER BY $sidx $sord LIMIT $start , $limit";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список групп!" . mysqli_error($sqlcn->idsqlconnection));
    $responce = new stdClass();
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
        $responce->rows[$i]['id'] = $row['id'];
        $responce->rows[$i]['cell'] = array(
            $row['id'],
            $row['dgname'],
            $row['dcomment']
        );
        $i ++;
    }
    echo json_encode($responce);
}
;
if ($oper == 'edit') {
    $SQL = "UPDATE devgroups SET dgname='$dgname',dcomment='$dcomment' WHERE id='$id'";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу обновить данные по группам!" . mysqli_error($sqlcn->idsqlconnection));
}
;
if ($oper == 'add') {
    $SQL = "INSERT INTO devgroups (id,dgname,dcomment) VALUES (null,'$dgname','$dcomment')";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу добавить группу!" . mysqli_error($sqlcn->idsqlconnection));
}
;
if ($oper == 'del') {
    $SQL = "delete FROM devgroups WHERE id='$id'";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу удалить группу!" . mysqli_error($sqlcn->idsqlconnection));
}
;

?>