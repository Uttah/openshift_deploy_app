<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф

$page = GetDef('page');
$limit = GetDef('rows');
$sidx = GetDef('sidx');
$sord = GetDef('sord');
$oper = PostDef('oper');
$groupid = GetDef('groupid');
if ($groupid == "") {
    $groupid = PostDef('groupid');
}
;
$id = PostDef('id');
$name = PostDef('name');
// если выбрана группа, то обрабатываем, иначе ничего
// echo "!$groupid!";
if ($oper == '') {
    if (($user->mode == 1) or ($user->TestRoles('1,3'))) {
        if (! $sidx)
            $sidx = 1;
        $result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM group_param WHERE groupid='$groupid'");
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
        // echo "!$limit*$page - $limit!";
        $start = $limit * $page - $limit;
        $SQL = "SELECT id,name,active FROM group_param WHERE groupid='$groupid' ORDER BY $sidx $sord LIMIT $start , $limit";
        // echo "!$SQL!";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список параметров групп!" . mysqli_error($sqlcn->idsqlconnection));
        $responce = new stdClass();
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;
        while ($row = mysqli_fetch_array($result)) {
            $responce->rows[$i]['id'] = $row['id'];
            if ($row['active'] == "1") {
                $responce->rows[$i]['cell'] = array(
                    "<i class=\"fa fa-check-circle-o\" aria-hidden=\"true\"></i>",
                    $row['id'],
                    $row['name']
                );
            } else {
                $responce->rows[$i]['cell'] = array(
                    "<i class=\"fa fa-ban\" aria-hidden=\"true\"></i>",
                    $row['id'],
                    $row['name']
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
    if (($user->mode == 1) or ($user->TestRoles('1,5'))) {
        $SQL = "UPDATE group_param SET name='$name' WHERE id='$id'";
        // echo "!$SQL!";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу обновить данные по группе!" . mysqli_error($sqlcn->idsqlconnection));
    }
    ;
}
;
if ($oper == 'add') {
    if (($user->mode == 1) or ($user->TestRoles('1,4'))) {
        if (($groupid == "") or ($name == "")) {
            die();
        }
        ;
        $SQL = "INSERT INTO group_param (id,groupid,name,active) VALUES (null,'$groupid','$name',1)";
        // echo "!$SQL!";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу добавить параметр группы!" . mysqli_error($sqlcn->idsqlconnection));
    }
    ;
}
;
if ($oper == 'del') {
    if (($user->mode == 1) or ($user->TestRoles('1,6'))) {
        $SQL = "UPDATE group_param SET active=not active WHERE id='$id'";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу обновить данные по параметрам группы!" . mysqli_error($sqlcn->idsqlconnection));
    }
    ;
}
;
?>