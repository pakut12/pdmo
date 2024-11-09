<?php

$HIDE_HEADER = "Y";
include '../include/comtop_user.php';

$wfr = $_POST['id'];
$data = array();
$data['DEL_FACT'] = "Y";
$cond = array(
    'WFR_ID' => $wfr
);

$up = db::db_update('WFR_SLIP_EMPLOYEE', $data, $cond);
