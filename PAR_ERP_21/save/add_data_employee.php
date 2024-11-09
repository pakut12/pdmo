<?php

print_pre($_POST);
foreach ($_POST["F_ID"] as $k => $v) {
    $fullname = explode(" ", $_POST["F_USER"][$k]);
    $firstname = $fullname[0];
    $lastname = $fullname[1];

    $insert_m = array();
    $insert_m['WFR_ID'] = ($WF["WFR_ID"]);
    $insert_m['YEAR_NO'] = ($_POST["F_YEAR"][0]);
    $insert_m['MONTH'] = ($_POST["MONTH_ID"][0]);
    $insert_m['PER_FNAME'] = ($firstname);
    $insert_m['PER_LNAME'] = ($lastname);
    $insert_m['PER_POS_NO'] = "";
    $insert_m['PER_ID_CARD'] = ($_POST["F_CARD"][$k]);
    $insert_m['DEPARTMENT_ID'] = ($_POST["DEP_ID"][0]);
    $insert_m['POSLINE_ID'] = "";
    $insert_m['POS_TYPE_ID'] = ($_POST["F_POSITION"][$k]);
    $insert_m['POS_LEVEL_ID'] = "";
    $insert_m['M_WAGE'] = ($_POST["F_WAGE"][$k]);
    $insert_m['M_SUM'] = ($_POST["F_SUM"][$k]);
    $insert_m['M_SSO'] = ($_POST["F_SSO"][$k]);
    $insert_m['M_COOPERATIVE'] = ($_POST["F_COOPERATIVE"][$k]);
    $insert_m['M_BANK_DEBT'] = ($_POST["F_BANK_DEBT"][$k]);
    $insert_m['M_SLF'] = ($_POST["F_SLF"][$k]);
    $insert_m['M_PDMO'] = ($_POST["F_PDMO"][$k]);
    $insert_m['M_ACCEPT'] = ($_POST["F_ACCEPT"][$k]);
    $insert_m['M__NOTE'] = ($_POST["F__NOTE"][$k]);

    $array = db::db_insert('M_SLIP_EMPLOYEE', $insert_m, 'SLIP_EMP_ID');
}


