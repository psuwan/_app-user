<?php

date_default_timezone_set('Asia/Bangkok');
$dateNow = date("Y-m-d");
$timeNow = date("H:i:s");

$thisFilename = basename(trim(str_replace(__DIR__, "", __FILE__), DIRECTORY_SEPARATOR), ".php");

include_once '../_app-lib/functions.php';
$dbConn = dbConnect();

do {
    $genUserRefNumber = date("YmdHis");
    $genUserRefNumber .= genToken(36);
    $userRefNumberChkExist = cntRows("tbl_users", "user_refnumber", $genUserRefNumber, 2);
} while ($userRefNumberChkExist > 0);

$vg_userRefNumber = filter_input(INPUT_GET, "userRefNumber");
if (empty($vg_userRefCode)) {
    $textTitle = "ลงทะเบียนผู้ใช้";
    $userRefNumber = $genUserRefNumber;
} else {
    $textTitle = "แก้ไขข้อมูลผู้ใช้";
    $userRefNumber = $vg_userRefNumber;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <!--<link rel="apple-touch-icon" sizes="51x51" href="../_app-asset/image/apks-logo-sq320.png">-->
    <link rel="icon" type="image/png" href="../_app-asset/image/apks-logo-sq320.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>
    <!-- Bootstrap Frontend Framework 5.2.0 Beta -->
    <link rel="stylesheet" href="../theme-bootstrap-5.2.0/css/bootstrap.min.css">

    <!--     Fonts and icons     -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" /> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@200&display=swap" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai+Looped:wght@300&display=swap"
          rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.css">

    <!-- PLUGIN SELECT2 -->
    <link rel="stylesheet" href="../plugin-select2-4.1.0/css/select2.min.css">
    <style>
        body, input, button, a {
            font-family: 'IBM Plex Sans Thai Looped', sans-serif;
        }

        .select2-container .select2-selection--single {
            height: 31px !important;
            padding-top: 1px !important;
            border: solid 1px lightgrey !important;
            border-radius: 6px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            /*top: 6px !important;*/
        }

        .select2-container--default .select2-selection--single {
            background-color: rgba(25, 25, 25, 0.1) !important;
        }

        .row {
            --bs-gutter-y: 0.5rem !important;
        }
    </style>

    <title>ลงทะเบียนผู้ใช้</title>
</head>

<body class="bg-info">

<div class="container">
    <div class="row mt-3">
        <div class="col-md-4 offset-md-4 text-center">
            <img src="../_app-asset/image/apks-logo-310x310.png" width="75%" alt="...">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4 offset-md-4 text-center">
            <h4><strong>APKS web application user system</strong></h4>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4 offset-md-4 text-center">
            <strong><a href="user-login.php" class="text-secondary">เข้าสู่ระบบ</a>
                / <?= $textTitle; ?></strong>
        </div>
    </div>
    <form action="./user-action.php" method="post">
        <div class="row mt-3 px-3">
            <div class="col-md-6 offset-md-3 bg-white" style="border-radius:5px">
                <div class="row mt-5">
                    <div class="col-md-12">
                        <select name="regis_app" id="id4_select_regisapp" class="select2-basic-single form-control"
                                required>
                            <option value=""></option>
                            <?php
                            $sqlcmd_listPrograms = "SELECT * FROM tbl_applications WHERE 1";
                            $sqlres_listPrograms = mysqli_query($dbConn, $sqlcmd_listPrograms);

                            if ($sqlres_listPrograms) {
                                while ($sqlfet_listPrograms = mysqli_fetch_assoc($sqlres_listPrograms)) {
                                    ?>
                                    <option value="<?= $sqlfet_listPrograms["app_code"]; ?>"><?= $sqlfet_listPrograms["app_name"]; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- ROW USER -->
                <div class="row mt-2">
                    <div class="col-md-3 pr-md-1">
                        <select name="regis_userprefix" id="id4_select_userprefix"
                                class="form-control form-control-sm select2-basic-single" required>
                            <option value=""></option>
                            <?php
                            $sqlcmd_listPrefix = "SELECT * FROM tbl_prefixes WHERE 1";
                            $sqlres_listPrefix = mysqli_query($dbConn, $sqlcmd_listPrefix);

                            if ($sqlres_listPrefix) {
                                while ($sqlfet_listPrefix = mysqli_fetch_assoc($sqlres_listPrefix)) {
                                    ?>
                                    <option value="<?= $sqlfet_listPrefix["prefix_code"]; ?>"><?= $sqlfet_listPrefix["prefix_name"]; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-5 text-center px-md-1">
                        <input type="text" name="regis_namefirst" id="id4_input_text_namefirst"
                               class="form-control form-control-sm"
                               placeholder="ชื่อ" required>
                    </div>
                    <div class="col-md-4 text-center pl-md-1">
                        <input type="text" name="regis_namelast" id="id4_input_text_name_last"
                               class="form-control form-control-sm"
                               placeholder="นามสกุล" required>
                    </div>
                </div><!-- ROW USER -->

                <!-- ROW PASSWORD -->
                <div class="row mt-2">
                    <div class="col-md-12 text-center">
                        <input type="password" name="regis_pass1" id="id4_input_password1"
                               class="form-control form-control-sm"
                               placeholder="รหัสผ่าน" onkeyup="checkedPassword(this.value)" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12 text-center">
                        <input type="password" name="regis_pass2" id="id4_input_password2"
                               class="form-control form-control-sm"
                               placeholder="รหัสผ่านอีกครั้ง" onkeyup="checkedRePassword(this.value)" required>
                    </div>
                </div><!-- ROW PASSWORD -->

                <div class="row mt-2 mb-5">
                    <div class="col-md-12 text-center">
                        <input type="hidden" name="userRefNumber" value="<?= $userRefNumber; ?>">
                        <input type="hidden" name="processName" value="user_register">
                        <button type="submit" class="btn btn-sm btn-block btn-primary" id="id4_button_submit_register"
                                style="font-size:14px;background-color:gray;" disabled>
                            ลงทะเบียน
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="fixed-bottom text-end font-weight-bold">
            modified by <a href="mailto:psuwan@apks-software.com">Pattanapong Suwan</a>
        </div>
    </div>
</div>

<!--   Core JS Files   -->
<!--<script src="../_theme-01/assets/js/core/jquery.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"
        integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js"
        integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy"
        crossorigin="anonymous"></script>

<!-- PLUGIN SELECT2 -->
<script src="../plugin-select2-4.1.0/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // PLUGIN SELECT2
        $('#id4_select_userprefix').select2({
            placeholder: "คำนำหน้าชื่อ"
        });
        $("#id4_select_regisapp").select2({
            placeholder: "เลือกโปรแกรม"
        });
    });
</script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

<script>
    let regisButton = document.getElementById("id4_button_submit_register");
    let inputPassword = document.getElementById("id4_input_password1");
    let inputRePassword = document.getElementById("id4_input_password2");
    let checkedPassword = function (password2Check) {
        if (password2Check.length >= 6) {
            if (isPasswordValid(password2Check)) {
                // Remove boxShadow glowing
                inputPassword.style.boxShadow = "";
                // input borderColor color
                inputPassword.style.borderColor = "green";
            }
        } else {
            // boxShadow glowing
            inputPassword.style.boxShadow = "0 0 5px rgba(255, 0, 0, 1)";
            regisButton.style.background = "gray";
            regisButton.disabled = true;
        }
    }

    let isPasswordValid = function (text2Check) {
        /*
          Usernames can only have:
          - Lowercase Letters (a-z)
          - Numbers (0-9)
          - Dots (.)
          - Underscores (_)
        */
        const res = /^[a-zA-Z0-9_\.@#$%^&!]+$/.exec(text2Check);
        const valid = !!res;
        return valid;
    }

    let checkedRePassword = function (rePassword2Check) {
        if (rePassword2Check !== inputPassword.value) {
            inputRePassword.style.boxShadow = "0 0 5px rgba(255, 0, 0, 1)";
            regisButton.style.background = "gray";
            regisButton.disabled = true;
        } else {
            inputRePassword.style.boxShadow = "";
            inputRePassword.style.borderColor = "green";
            regisButton.style.background = "#A3CA76";
            regisButton.disabled = false;
        }
    }
</script>

</body>

</html>