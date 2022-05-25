<?php

date_default_timezone_set('Asia/Bangkok');
$dateNow = date("Y-m-d");
$timeNow = date("H:i:s");
$dt2_userRefCode = date("YmdHis");

$thisFilename = basename(trim(str_replace(__DIR__, "", __FILE__), DIRECTORY_SEPARATOR), ".php");

include_once '../_app-lib/functions.php';
$dbConn = dbConnect();

$vg_userRefCode = filter_input(INPUT_GET, "userRefCode");
if (empty($vg_userRefCode)) {
    $textTitle = "ลงทะเบียนผู้ใช้";
    $userRefCode = $dt2_userRefCode;
} else {
    $textTitle = "แก้ไขข้อมูลผู้ใช้";
    $userRefCode = $vg_userRefCode;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="../_theme-01/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../_app-asset/image/apks-logo-sq320.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>
    <!-- Bootstrap Frontend Framework 5.2.0 Beta -->
    <link rel="stylesheet" href="../theme-bootstrap-5.2.0/css/bootstrap.min.css">

    <!--     Fonts and icons     -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" /> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@200&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="../font-IBMPlexSansThaiLooped-Light/IBMPlexSansThaiLooped-Light.css">
    <link rel="stylesheet" href="../font-awesome-6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../font-bootstrap-icons-1.8.2/bootstrap-icons.css">

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

    <title>ผู้ใช้เข้าระบบ</title>
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
            <h4>APKS web application user system</h4>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4 offset-md-4 text-center">
            thanos manage users
        </div>
    </div>
    <form action="act4-user.php" method="post">
        <div class="row mt-3 px-3">
            <div class="col-md-6 offset-md-3 bg-white" style="border-radius:5px">
                <div class="row mt-5">
                    <div class="col-md-12">
                        <select name="login_app" id="id4_select_loginapp" class="select2-basic-single form-control"
                                required onchange="listUsers()">
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
                    <div class="col-md-12">
                        table show users list
                    </div>
                </div><!-- ROW USER -->
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
<script src="../theme-bootstrap-5.2.0/js/jquery-3.6.0.min.js"></script>
<script src="../theme-bootstrap-5.2.0/js/popper.min.js"></script>
<script src="../theme-bootstrap-5.2.0/js/bootstrap.min.js"></script>

<!-- PLUGIN SELECT2 -->
<script src="../plugin-select2-4.1.0/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // PLUGIN SELECT2
        $('#id4_select_loginuser').select2({
            placeholder: "ชื่อผู้ใช้งาน"
        });
        $("#id4_select_loginapp").select2({
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
    let listUsers = function () {
        let listApp2Login = document.getElementById("id4_select_loginapp");
        let listUserByApplication = document.getElementById("id4_select_loginuser");
        let selectedApp = listApp2Login.options[listApp2Login.selectedIndex].value;
        $.ajax({
            url: "user-action.php",
            type: "post",
            data: {
                processName: "listUser4App",
                app2ListUsr: selectedApp
            },
            success: function (response) {
                $(listUserByApplication).empty();
                // You will get response from your PHP page (what you echo or print)
                $(listUserByApplication).append(
                    response
                )
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }
</script>

</body>

</html>