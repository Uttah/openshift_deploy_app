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
$id = PostDef('id');
$name = PostDef('name');
$comment = PostDef('comment');

if ($oper == '') {
    if (($user->mode == 1) or ($user->TestRoles('1,3'))) {
        if (! $sidx)
            $sidx = 1;
        $result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM group_nome");
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
        $SQL = "SELECT id,name,comment,active FROM group_nome ORDER BY $sidx $sord LIMIT $start , $limit";
        // echo "!$SQL!";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список групп!" . mysqli_error($sqlcn->idsqlconnection));
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
                    $row['name'],
                    $row['comment']
                );
            } else {
                $responce->rows[$i]['cell'] = array(
                    "<i class=\"fa fa-ban\" aria-hidden=\"true\"></i>",
                    $row['id'],
                    $row['name'],
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
    if (($user->mode == 1) or ($user->TestRoles('1,5'))) {
        $SQL = "UPDATE group_nome SET name='$name',comment='$comment' WHERE id='$id'";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу обновить данные по группе!" . mysqli_error($sqlcn->idsqlconnection));
    }
    ;
}
;
if ($oper == 'add') {
    if (($user->mode == 1) or ($user->TestRoles('1,4'))) {
        $SQL = "INSERT INTO group_nome (id,name,comment,active) VALUES (null,'$name','$comment',1)";
        // echo "!$SQL!";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу добавить группу!" . mysqli_error($sqlcn->idsqlconnection));
    }
    ;
}
;
if ($oper == 'del') {
    if (($user->mode == 1) or ($user->TestRoles('1,6'))) {
        $SQL = "UPDATE group_nome SET active=not active WHERE id='$id'";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу обновить данные по группе!" . mysqli_error($sqlcn->idsqlconnection));
        $SQL = "SELECT * FROM group_nome WHERE id='$id'";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список групп!" . mysqli_error($sqlcn->idsqlconnection));
        while ($row = mysqli_fetch_array($result)) {
            $active = $row['active'];
        }
        ;
        $SQL = "UPDATE group_param SET active='$active' WHERE groupid='$id'";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу обновить данные по группе!" . mysqli_error($sqlcn->idsqlconnection));
    }
    ;
}
;

?>