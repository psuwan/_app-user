<?php

date_default_timezone_set('Asia/Bangkok');
$dateNow = date("Y-m-d");
$timeNow = date("H:i:s");
$dt2_userRefCode = date("YmdHis");

$thisFilename = basename(trim(str_replace(__DIR__, "", __FILE__), DIRECTORY_SEPARATOR), ".php");

include_once '../_app-lib/functions.php';
$dbConn = dbConnect();
/*
echo "<pre>";
var_dump($_POST);
echo "</pre>";
*/
$vp_processName = filter_input(INPUT_POST, "processName");
$vp_userRefNumber = filter_input(INPUT_POST, "userRefNumber");
$vp_application = filter_input(INPUT_POST, "regis_app");
$vp_userPrefix = filter_input(INPUT_POST, "regis_userprefix");
$vp_userNameFirst = filter_input(INPUT_POST, "regis_namefirst");
$vp_userNameLast = filter_input(INPUT_POST, "regis_namelast");
$vp_userPassword = filter_input(INPUT_POST, "regis_pass1");
$beforeCrypt = $vp_userPassword;
$vp_userPassword = password_hash($vp_userPassword, PASSWORD_DEFAULT);

if (!empty($vp_processName)) {
    switch ($vp_processName) {
        case "user_register":
            insertDB("tbl_users", "user_refnumber", $vp_userRefNumber, 2);
            updateDB("tbl_users", "user_refnumber", $vp_userRefNumber, 2, "user_application", $vp_application, 2);
            updateDB("tbl_users", "user_refnumber", $vp_userRefNumber, 2, "user_password", $vp_userPassword, 2);
            updateDB("tbl_users", "user_refnumber", $vp_userRefNumber, 2, "user_status", 1, 2);
            updateDB("tbl_users", "user_refnumber", $vp_userRefNumber, 2, "user_created", $dateNow . " " . $timeNow, 2);
            updateDB("tbl_users", "user_refnumber", $vp_userRefNumber, 2, "user_remark", "Add user to application [" . $vp_application . "][][" . $beforeCrypt . "]", 2);

            $checkTblProfile = cntRows("tbl_profiles", "profile_refnumber", $vp_userRefNumber, 2);
            if ($checkTblProfile === 0) {
                insertDB("tbl_profiles", "profile_refnumber", $vp_userRefNumber, 2);
                updateDB("tbl_profiles", "profile_refnumber", $vp_userRefNumber, 2, "profile_namefirst", $vp_userNameFirst, 2);
                updateDB("tbl_profiles", "profile_refnumber", $vp_userRefNumber, 2, "profile_namelast", $vp_userNameLast, 2);
                updateDB("tbl_profiles", "profile_refnumber", $vp_userRefNumber, 2, "profile_prefix", $vp_userPrefix, 2);
            } else {
                updateDB("tbl_profiles", "profile_refnumber", $vp_userRefNumber, 2, "profile_namefirst", $vp_userNameFirst, 2);
                updateDB("tbl_profiles", "profile_refnumber", $vp_userRefNumber, 2, "profile_namelast", $vp_userNameLast, 2);
                updateDB("tbl_profiles", "profile_refnumber", $vp_userRefNumber, 2, "profile_prefix", $vp_userPrefix, 2);
            }

            $applicationName = get1Data("tbl_applications", "app_code", $vp_application, 2, "app_name");
            echo "<script>alert(\"Add user to application [" . $applicationName . "] ready \")</script>";
            echo "<script>window.location.href=\"./user-login.php\"</script>";
            break;
    }
}