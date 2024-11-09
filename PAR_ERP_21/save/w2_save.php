<?php

$excel_data = json_decode($_POST['excel_data']);
$update_wf['ROW_DATA'] = ($_POST['row_count']);
$update_wf['FILE_DATA'] = ($_POST['file_name']);

$n = 0;
while ($n < count($excel_data)) {
    $insert_m = array();
    $insert_m['WFD_ID'] = ($WF["WFD_ID"]);
    $insert_m['WFR_ID'] = ($WF["WFR_ID"]);
    $insert_m['F_TEMP_ID'] = ($WF["WFR_ID"]);
    $insert_m['F_YEAR'] = ($_POST['year']);  //ปี
    $insert_m['DEP_ID'] = ($_POST['dep']); //สำนักงาน
    $insert_m['F_TYPE'] = ($_POST['type']); //ประเภท
    $insert_m['MONTH_ID'] = ($_POST['month']); //เดือน
    $insert_m['F_CREATE_DATE'] = date('Y-m-d');
    $insert_m['F_CREATE_BY'] = $_SESSION['WF_USER_ID'];
    $insert_m['WF_MAIN_ID'] = ($_POST['W']);
    $insert_m['F_CARD'] = ($excel_data[$n]->__EMPTY_0) == "-" ? null : ($excel_data[$n]->__EMPTY_0); //เลขบัตรประชาชน
    $insert_m['F_BANK'] = ($excel_data[$n]->__EMPTY_1) == "-" ? null : ($excel_data[$n]->__EMPTY_1); //เลขที่บัญชีธนาคาร
    $insert_m['F_USER'] = ($excel_data[$n]->__EMPTY_2) == "-" ? null : ($excel_data[$n]->__EMPTY_2); //รายชื่อผู้รับเงิน
    $insert_m['F_POSITION'] = ($excel_data[$n]->__EMPTY_3) == "-" ? null : ($excel_data[$n]->__EMPTY_3); //ตำเเหน่ง
    $insert_m['F_AFFILIATION'] = ($excel_data[$n]->__EMPTY_4) == "-" ? null : ($excel_data[$n]->__EMPTY_4); //สังกัด
    $insert_m['F_WAGE'] = ($excel_data[$n]->__EMPTY_5) == "-" ? null : ($excel_data[$n]->__EMPTY_5); //อัตราค่าจ้าง
    $insert_m['F_SUM'] =  ($excel_data[$n]->__EMPTY_6) == "-" ? null : ($excel_data[$n]->__EMPTY_6); //รวมยอดจ่ายจริง
    $insert_m['F_SSO'] =  ($excel_data[$n]->__EMPTY_7) == "-" ? null : ($excel_data[$n]->__EMPTY_7); //ประกันสังคม
    $insert_m['F_COOPERATIVE'] = ($excel_data[$n]->__EMPTY_8) == "-" ? null : ($excel_data[$n]->__EMPTY_8); //สหกรณ์
    $insert_m['F_BANK_DEBT'] = ($excel_data[$n]->__EMPTY_9) == "-" ? null : ($excel_data[$n]->__EMPTY_9); //หนี้ธนาคาร กรุงไทย-ออมสิน
    $insert_m['F_SLF'] = ($excel_data[$n]->__EMPTY_10) == "-" ? null : ($excel_data[$n]->__EMPTY_10); //กยศ
    $insert_m['F_PDMO'] = ($excel_data[$n]->__EMPTY_11) == "-" ? null : ($excel_data[$n]->__EMPTY_11); //หนี้ สบน
    $insert_m['F_ACCEPT'] = ($excel_data[$n]->__EMPTY_12) == "-" ? null : ($excel_data[$n]->__EMPTY_12); //รับจริง
    $insert_m['F__NOTE'] = ($excel_data[$n]->__EMPTY_13) == "-" ? null : ($excel_data[$n]->__EMPTY_13); //หมายเหตุ

    $array = db::db_insert('FRM_SLIP_EMPLOYEE', $insert_m, 'F_ID');
    $n++;
}

unset($insert_m);
