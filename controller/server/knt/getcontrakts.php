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

$page=  _GET("page");
$limit=  _GET("limit");
$sidx=  _GET("sidx");
$sord=  _GET("sord");
$oper= _POST("oper");
$idknt=  _GET("idknt");
$id= _POST("id");
$name= _POST("name");
$num= _POST("num");
$datestart= _POST("datestart");
$dateend= _POST("dateend");
$work= _POST("work");
$comment= _POST("comment");

$where = " WHERE kntid='$idknt'";

if ($oper == '') {
    if ($user->TestRoles('1,3')) {
        if (! $sidx)
            $sidx = 1;
        $result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM contract" . $where);
        $row = mysqli_fetch_array($result);
        $count = $row['count'];
        
        if ($count > 0) {
	    if ($limit==0){$limit=1;};
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        ;
        if ($page > $total_pages)
            $page = $total_pages;
        
        $start = $limit * $page - $limit;
        $SQL = "SELECT * FROM contract " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit";
        // echo "!$SQL!";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список договоров!" . mysqli_error($sqlcn->idsqlconnection));
        
        $responce = new stdClass();
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;
        while ($row = mysqli_fetch_array($result)) {
            $responce->rows[$i]['id'] = $row['id'];
            if ($row['work'] == 0) {
                $row['work'] = 'No';
            } else {
                $row['work'] = 'Yes';
            }
            ;
            $dateend = $row['dateend'] . ' 00:00:00';
            $datestart = $row['datestart'] . ' 00:00:00';
            if ($row['active'] == "1") {
                $responce->rows[$i]['cell'] = array(
                    "<i class=\"fa fa-check-circle-o\" aria-hidden=\"true\"></i>",
                    $row['id'],
                    $row['num'],
                    $row['name'],
                    MySQLDateTimeToDateTime($datestart),
                    MySQLDateTimeToDateTime($dateend),
                    $row['work'],
                    $row['comment']
                );
            } else {
                $responce->rows[$i]['cell'] = array(
                    "<i class=\"fa fa-ban\" aria-hidden=\"true\"></i>",
                    $row['id'],
                    $row['num'],
                    $row['name'],
                    MySQLDateTimeToDateTime($datestart),
                    MySQLDateTimeToDateTime($dateend),
                    $row['work'],
                    $row['comment']
                );
            }
            ;
            $i ++;
        }
        echo json_encode($responce);
    }
    ;
}
;
if ($oper == 'edit') {
    if ($user->TestRoles('1,5')) {
        if ($work == 'Yes') {
            $work = '1';
        } else {
            $work = '0';
        }
        ;
        $datestart = DateToMySQLDateTime2($datestart);
        $dateend = DateToMySQLDateTime2($dateend);
        $SQL = "UPDATE contract SET num='$num',name='$name',comment='$comment',datestart='$datestart',dateend='$dateend',work='$work' WHERE id='$id'";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу обновить данные по договору!" . mysqli_error($sqlcn->idsqlconnection));
        echo "!$SQL!";
    }
    ;
}
;

if ($oper == 'add') {
    if ($user->TestRoles('1,4')) {
        if ($work == 'Yes') {
            $work = '1';
        } else {
            $work = '0';
        }
        ;
        $datestart = DateToMySQLDateTime2($datestart);
        $dateend = DateToMySQLDateTime2($dateend);
        $SQL = "INSERT INTO contract (id,kntid,num,name,comment,datestart,dateend,work,active) VALUES (null,'$idknt','$num','$name','$comment','$datestart','$dateend','$work',1)";
        // echo "!$SQL!";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу добавить данные по договору!" . mysqli_error($sqlcn->idsqlconnection));
    }
    ;
}
;
if ($oper == 'del') {
    if ($user->TestRoles('1,6')) {
        $SQL = "UPDATE contract SET active=not active WHERE id='$id'";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не смог пометить на удаление договор!" . mysqli_error($sqlcn->idsqlconnection));
    }
    ;
}
;

?>
