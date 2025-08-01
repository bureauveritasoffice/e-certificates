<?php
require_once '../config.php';

$data_no = $_GET['data_no'] ?? '';
if (!$data_no) {
    echo "No Data No provided in URL.";
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM cards WHERE data_no = ?");
    $stmt->execute([$data_no]);
    $card = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$card) {
        // Show full 404.html content inline without redirecting
        http_response_code(404);
        ?>
        <?php include __DIR__ . '/404.html'; ?>
        <?php
        exit;
    }
} catch (Exception $e) {
    echo "Error fetching data: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verify Card/Certificate | TUV</title>
    <meta name="csrf-token" content="bMZ8uxuX4TNBUejpAEGj34nvpCPzpomjKvJYwljj" />

    <!-- TODO add manifest here -->
    <link rel="manifest" href="https://operations.tuvatsa.com/manifest.json" />
    <!-- Add to home screen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="PWA App" />
    <link rel="apple-touch-icon" href="https://operations.tuvatsa.com/images/icons/icon-152x152.png" />
    <meta name="msapplication-TileImage" content="https://operations.tuvatsa.com/images/icons/icon-144x144.png" />
    <meta name="msapplication-TileColor" content="#2F3BA2" />

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
        integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="https://operations.tuvatsa.com/css/select2.min.css" />
    <link rel="stylesheet" type="text/css" href="https://operations.tuvatsa.com/css/select2-bootstrap4.min.css" />
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="https://operations.tuvatsa.com/css/custom.css" />
    <link href="https://operations.tuvatsa.com/assets/pages/css/login-4.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
        integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
        integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css" />

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" />

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

    <!-- Radio buttons -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" />

    <style type="text/css">
        .swal2-cancel {
            background: goldenrod !important;
        }

        .swal2-confirm {
            background: orangered !important;
        }

        .swal2-warning {
            background: red !important;
            color: white !important;
            border: 1px solid white !important;
        }

        .modal-header {
            background-color: #111719;
            color: white;
        }

        .modal-header>.close {
            background: black !important;
            color: white !important;
        }

        .modal-footer {
            background-color: #111719;
        }
    </style>
    <link rel="shortcut icon" href="https://operations.tuvatsa.com/favicon.ico" />
</head>

<body cz-shortcut-listen="true">
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <img width="52" src="https://operations.tuvatsa.com/img/TUV.jpg" alt="" />
            </div>
            <ul class="list-unstyled components">
                <li class="d-none d-md-block"><span title="Main Navigation">Main Navigation</span></li>
                <li class="">
                    <a href="https://operations.tuvatsa.com/dashboard" title="Dashboard">
                        <svg class="svg-inline--fa fa-rocket fa-w-16" aria-hidden="true" data-prefix="fas"
                            data-icon="rocket" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                            data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M505.1 19.1C503.8 13 499 8.2 492.9 6.9 460.7 0 435.5 0 410.4 0 307.2 0 245.3 55.2 199.1 128H94.9c-18.2 0-34.8 10.3-42.9 26.5L2.6 253.3c-8 16 3.6 34.7 21.5 34.7h95.1c-5.9 12.8-11.9 25.5-18 37.7-3.1 6.2-1.9 13.6 3 18.5l63.6 63.6c4.9 4.9 12.3 6.1 18.5 3 12.2-6.1 24.9-12 37.7-17.9V488c0 17.8 18.8 29.4 34.7 21.5l98.7-49.4c16.3-8.1 26.5-24.8 26.5-42.9V312.8c72.6-46.3 128-108.4 128-211.1.1-25.2.1-50.4-6.8-82.6zM400 160c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48z">
                            </path>
                        </svg>
                        <span class="d-none d-md-inline-block">Dashboard</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <svg class="svg-inline--fa fa-bars fa-w-14 fa-2x" aria-hidden="true" data-prefix="fas"
                            data-icon="bars" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                            data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z">
                            </path>
                        </svg>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <svg class="svg-inline--fa fa-align-justify fa-w-14" aria-hidden="true" data-prefix="fas"
                            data-icon="align-justify" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                            data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M0 84V44c0-8.837 7.163-16 16-16h416c8.837 0 16 7.163 16 16v40c0 8.837-7.163 16-16 16H16c-8.837 0-16-7.163-16-16zm16 144h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 256h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0-128h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z">
                            </path>
                        </svg>
                    </button>

                    <div class="collapse navbar-collapse top_menu" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="dropdown dropdown-user">
                                <a href="https://operations.tuvatsa.com/logout" class="text-light" title="Logout">
                                    <svg class="svg-inline--fa fa-power-off fa-w-16 fa-lg" aria-hidden="true"
                                        data-prefix="fas" data-icon="power-off" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512" data-fa-i2svg="">
                                        <path fill="currentColor"
                                            d="M400 54.1c63 45 104 118.6 104 201.9 0 136.8-110.8 247.7-247.5 248C120 504.3 8.2 393 8 256.4 7.9 173.1 48.9 99.3 111.8 54.2c11.7-8.3 28-4.8 35 7.7L162.6 90c5.9 10.5 3.1 23.8-6.6 31-41.5 30.8-68 79.6-68 134.9-.1 92.3 74.5 168.1 168 168.1 91.6 0 168.6-74.2 168-169.1-.3-51.8-24.7-101.8-68.1-134-9.7-7.2-12.4-20.5-6.5-30.9l15.8-28.1c7-12.4 23.2-16.1 34.8-7.8zM296 264V24c0-13.3-10.7-24-24-24h-32c-13.3 0-24 10.7-24 24v240c0 13.3 10.7 24 24 24h32c13.3 0 24-10.7 24-24z">
                                        </path>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <style type="text/css">
                .label-success {
                    background-color: lightgreen !important;
                    color: black !important;
                }

                .label-default {
                    background-color: #cec9c9 !important;
                    color: black !important;
                }
            </style>
            <div class="loading" style="display: none;">
                <div class="loader"></div>
            </div>
            <!-- breadcrumb -->
            <div class="row">
                <div class="col-sm-6">
                    <h4>Verify Card/Certificate</h4>
                </div>
            </div>

            <div class="verify_your_card">
                <h1 class="valid"
                    style="text-align: center; font-size: 30px; margin-top: 20px; background-color: #dff0d8; padding: 10px; display: none;max-width: 320px; margin: 15px auto;">
                    Verified</h1>
                <h1 class="invalid"
                    style="text-align: center; font-size: 30px; margin-top: 20px; background-color: #F2DEDD; padding: 10px; display: none;max-width: 320px; margin: 15px auto;">
                    Invalid</h1>
                <h1 class="error"
                    style="text-align: center; font-size: 20px; margin-top: 20px; color: #fff; background-color: #dc3545; display: none; padding: 10px;">
                </h1>
            </div>

            <div class="verify_card_container">
                <div style="height: 10vh; padding: 2px; padding-bottom: 0;">
                    <div style="float: left; width: 75%;">
                        <img src="https://operations.tuvatsa.com/img/tuv_text_left.png" class="text_left"
                            style="margin-top:20px;" />
                    </div>
                    <div style="float: left; width: 25%;">
                        <img src="https://operations.tuvatsa.com/img/logo_right.png" class="logo_right" />
                    </div>
                </div>
                <div class="defect_report_container" style="display: block;">
                    <div class="inner_container">
                        <style type="text/css">
                            @page {
                                size: 85.60mm 54.98mm portrait;
                                margin: 0px;
                            }

                            .container-elem {
                                font-size: 9px;
                            }

                            .page-break {
                                page-break-after: always;
                            }

                            body {
                                font-family: 'Fjalla One', sans-serif;
                            }

                            .red {
                                color: #9A1E30;
                            }

                            table.second_page tr td:second-child {
                                text-align: center !important;
                            }

                            .column {
                                float: left;
                                width: 50%;
                            }
                        </style>

                        <div class="container">

                            <div style="height:100%; padding: 2px; padding-bottom: 0;">
                                <?php
                                $currentDate = new DateTime();
                                $expiryDate = new DateTime($card['expiry_date']);
                                if ($expiryDate < $currentDate) {
                                    $watermarkText = 'Expired';
                                    $watermarkColor = '#dc3545'; // red
                                } else {
                                    $watermarkText = 'Verified';
                                    $watermarkColor = '#28a745'; // green
                                }
                                ?>
                                <div id="watermark" class="watermark"
                                    style="font-weight: bold;position: absolute;width: 30%;text-align: center;opacity: .3; transform: rotate(-45deg);z-index: 984;font-size: 61px;letter-spacing: 10px; color: <?php echo $watermarkColor; ?>;">
                                    <?php echo $watermarkText; ?>
                                </div>
                                <div class="row" style="width: 30%; float: right;">


<?php if ($card['photo'] && file_exists('../uploads/' . $card['photo'])): ?>
<img src="../uploads/<?php echo htmlspecialchars($card['photo']); ?>" alt="Card Photo" class="photo-padding-right" style="height: 100px;" />
            <?php endif; ?>
         </div>
                                <div style="margin-right:25.5%; text-align:center;">
                                    <p><span style="font-weight:bold;">CARD NO: </span><?php echo htmlspecialchars($card['card_no']); ?></p>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 font-weight-bold">NAME:</div>
                                    <div class="col-sm-6"><?php echo htmlspecialchars($card['name']); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 font-weight-bold">IQAMA / ID NO:</div>
                                    <div class="col-sm-6"><?php echo htmlspecialchars($card['id_no']); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 font-weight-bold">ISSUE DATE:</div>
                                    <div class="col-sm-6"><?php echo htmlspecialchars($card['issue_date']); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 font-weight-bold">EXPIRY DATE:</div>
                                    <div class="col-sm-6"><?php echo htmlspecialchars($card['expiry_date']); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 font-weight-bold">COMPANY:</div>
                                    <div class="col-sm-6"><?php echo htmlspecialchars($card['company']); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 font-weight-bold">MODEL:</div>
                                    <div class="col-sm-6"><?php echo htmlspecialchars($card['model']); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 font-weight-bold">REF / JOB NO:</div>
                                    <div class="col-sm-6"><?php echo htmlspecialchars($card['ref_no']); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 font-weight-bold">ISSUANCE NO:</div>
                                    <div class="col-sm-6"><?php echo htmlspecialchars($card['issuance_no']); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 font-weight-bold">CERTIFICATION:</div>
                                    <div class="col-sm-6"><?php echo htmlspecialchars($card['certification']); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 font-weight-bold">ASSESSOR:</div>
                                    <div class="col-sm-6"><?php echo htmlspecialchars($card['assessor']); ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 font-weight-bold">CR NUMBER:</div>
                                <div class="col-sm-6"><?php echo htmlspecialchars($card['cr_number']); ?></div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <style>
                @media screen and (min-width: 750px) {
                    img.logo_right {
                        width: 34%;
                    }

                    img.text_left {
                        width: 34%;
                    }

                    .watermark {
                        margin-top: 7%;
                    }
                }

                @media screen and (max-width: 750px) {
                    img.logo_right {
                        width: 100%;
                    }

                    img.text_left {
                        width: 90%;
                    }

                    .watermark {
                        margin-top: 70%;
                    }
                }

                .verify_card_container {
                    background-color: #fff;
                }

                #canvas {
                    width: 230px;
                    height: 300px;
                    border-radius: 4px;
                }

                .loading {
                    position: absolute;
                    top: 41px;
                    left: 250px;
                    bottom: 0;
                    right: 0;
                    background-color: #ddd;
                    z-index: 999;
                    text-align: center;
                    opacity: .5;
                    display: none;
                }

                .loader {
                    border: 8px solid #f3f3f3;
                    border-radius: 50%;
                    border-top: 8px solid blue;
                    border-right: 8px solid green;
                    border-bottom: 8px solid red;
                    width: 50px;
                    height: 50px;
                    -webkit-animation: spin 2s linear infinite;
                    animation: spin 2s linear infinite;
                    margin: 0 auto;
                    margin-top: 170px;
                }

                @-webkit-keyframes spin {
                    0% {
                        -webkit-transform: rotate(0deg);
                    }

                    100% {
                        -webkit-transform: rotate(360deg);
                    }
                }

                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }
            </style>
        </div>
    </div>
    <div class="page-footer">
        <div class="page-footer-inner">2023 @ TUV</div>
    </div>

    <!-- jQuery, Popper.js, and Bootstrap JS for navbar functionality -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
</body>
</html>








