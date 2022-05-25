<?php

//--> SESSION START
session_start();

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
//--> USER REGISTER DATA
$vp_processName = filter_input(INPUT_POST, "processName");
$vp_userRefNumber = filter_input(INPUT_POST, "userRefNumber");
$vp_application = filter_input(INPUT_POST, "regis_app");
$vp_userPrefix = filter_input(INPUT_POST, "regis_userprefix");
$vp_userNameFirst = filter_input(INPUT_POST, "regis_namefirst");
$vp_userNameLast = filter_input(INPUT_POST, "regis_namelast");
$vp_userPassword = filter_input(INPUT_POST, "regis_pass1");
$beforeCrypt = $vp_userPassword;
$vp_userPassword = password_hash($vp_userPassword, PASSWORD_DEFAULT);

//--> USER LOGIN DATA
$vp_loginApp = filter_input(INPUT_POST, "login_app");
$vp_loginUser = filter_input(INPUT_POST, "login_user");
$vp_loginPass = filter_input(INPUT_POST, "login_pass");

$vp_app2ListUser = filter_input(INPUT_POST, "app2ListUsr");

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
            //--> LOG REGISTER USER EVENT
            logWrite("NORMAL", "REGISTER USER", $vp_userRefNumber . " / " . $vp_application);

            echo "<script>alert(\"Add user to application [" . $applicationName . "] ready \")</script>";
            echo "<script>window.location.href=\"./user-login.php\"</script>";
            break;

        case "user_login":
            $applicationName = get1Data("tbl_applications", "app_code", $vp_application, 2, "app_name");

            $userlogin_password = get1Data("tbl_users", "user_refnumber", $vp_loginUser, 2, "user_password");
            $verified_password = password_verify($vp_loginPass, $userlogin_password);
            if (!$verified_password) {
                //--> LOG REGISTER USER EVENT
                logWrite("FAILED", "USER LOGIN", get1Data("tbl_profiles", "profile_refnumber", $vp_loginUser, 2, "profile_namefirst") . " " . get1Data("tbl_profiles", "profile_refnumber", $vp_loginUser, 2, "profile_namelast") . " FAILED TO LOGIN " . get1Data("tbl_applications", "app_code", $vp_loginApp, 2, "app_name"));
                echo "<script>alert(\"Error! [wrong password]\")</script>";
                echo "<script>window.location.href=\"user-login.php\"</script>";
            } else {
                //--> LOG REGISTER USER EVENT
                logWrite("NORMAL", "USER LOGIN", get1Data("tbl_profiles", "profile_refnumber", $vp_loginUser, 2, "profile_namefirst") . " " . get1Data("tbl_profiles", "profile_refnumber", $vp_loginUser, 2, "profile_namelast") . " SUCCESS TO LOGIN " . get1Data("tbl_applications", "app_code", $vp_loginApp, 2, "app_name"));
                echo "ผ่าน";
            }
            break;

        case
        "listUser4App":
            $sqlcmd_listUsers = "SELECT * FROM tbl_users WHERE user_application='" . $vp_app2ListUser . "'";
            $sqlres_listUsers = mysqli_query($dbConn, $sqlcmd_listUsers);

            if ($sqlres_listUsers) {
                while ($sqlfet_listUsers = mysqli_fetch_assoc($sqlres_listUsers)) {
                    ?>
                    <option value="<?= $sqlfet_listUsers["user_refnumber"]; ?>">
                        <?php
                        $getUserPrefix = get1Data("tbl_profiles", "profile_refnumber", $sqlfet_listUsers["user_refnumber"], 2, "profile_prefix");
                        echo get1Data("tbl_prefixes", "prefix_code", $getUserPrefix, 2, "prefix_name");
                        ?>
                        <?= get1Data("tbl_profiles", "profile_refnumber", $sqlfet_listUsers["user_refnumber"], 2, "profile_namefirst") . "&nbsp;"; ?>
                        <?= get1Data("tbl_profiles", "profile_refnumber", $sqlfet_listUsers["user_refnumber"], 2, "profile_namelast"); ?></option>
                    <?php
                }
            } else {
                echo "Query Error [" . mysqli_error($dbConn) . "]";
            }
            break;
    }
}