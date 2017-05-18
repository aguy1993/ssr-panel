<?php
require_once 'lib/init.php';
session_start();
if(isset($_SESSION['uid']) && $_SESSION['uid'] != null){
    $uid = $_SESSION['uid'];
}else{
    header("Location:login.php");
}
$user = new User();
$user_traffic = $user->get_traffic($uid);
?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>流量使用详情</title>
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="https://v3.bootcss.com/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="https://v3.bootcss.com/examples/dashboard/dashboard.css" rel="stylesheet" />
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="http://v3.bootcss.com/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="http://v3.bootcss.com/assets/js/ie-emulation-modes-warning.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="static/echarts.min.js"></script>
    <style id="style-1-cropbar-clipper">/* Copyright 2014 Evernote Corporation. All rights reserved. */
        .en-markup-crop-options {
            top: 18px !important;
            left: 50% !important;
            margin-left: -100px !important;
            width: 200px !important;
            border: 2px rgba(255,255,255,.38) solid !important;
            border-radius: 4px !important;
        }

        .en-markup-crop-options div div:first-of-type {
            margin-left: 0px !important;
        }
    </style>
</head>
<body>
            <div id="main" style="width: 1000px;height:600px;"></div>

            <script type="text/javascript">
                var myChart = echarts.init(document.getElementById('main'));

                // option
                option = {
                    title: {
                        text: '每日流量使用情况（30天内） 单位：MB',
                        textStyle: {
                            color: '#000'
                        }            
                    },
                    backgroundColor: '#f3f3f3',
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                    },
                    legend: {
                        data: ['流量'],
                        textStyle: {
                            color: '#000'
                        }
                    },
                    xAxis: {
                        data: <?php echo $user_traffic['date'];?>,
                        axisLine: {
                            lineStyle: {
                                color: '#000'
                            }
                        }
                    },
                    yAxis: {
                        splitLine: {show: false},
                        axisLine: {
                            lineStyle: {
                                color: '#000'
                            }
                        }
                    },
                    series: [{
                        name: '流量',
                        type: 'line',
                        smooth:true,
                                    symbol: 'none',
                                    sampling: 'average',
                                    itemStyle: {
                                        normal: {
                                            color: 'rgb(255, 70, 131)'
                                        }
                                    },
                                    areaStyle: {
                                        normal: {
                                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                                offset: 0,
                                                color: 'rgb(255, 158, 68)'
                                            }, {
                                                offset: 1,
                                                color: 'rgb(255, 70, 131)'
                                            }])
                                        }
                                    },
                        data: <?php echo $user_traffic['traffic'];?>
                    }]
                };
                myChart.setOption(option);
            </script>
</body>
</html>