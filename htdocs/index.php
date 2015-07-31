<!DOCTYPE html>

<html>

<head>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <script src="http://code.highcharts.com/highcharts.src.js"></script>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css">

    <style>
        aside, .main-sidebar {
            position: relative;
            top: 0;
            left: 0;
            padding-top: 50px;
            min-height: 100%;
            width: 230px;
            z-index: 810;
            display: inline-block;
        }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            /*font-size: 14px;*/
            line-height: 1.8;
            color: #333;
            background-color: #ecf0f5;
            display: inline-block;
            width: 100%;
        }
        #upload-header {
            display: inline-block
        }
       #container {
            width: 75%;
            height: 500px;
        }
    </style>

</head>
<body>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">Main Navigation
            <!-- Optionally, you can add icons to the links -->
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <span>dashboard</span></a></li>
            <li class="treeview">
                <a href="#">Hello</a>
            </li>
            <li class="treeview">
                <a href="#"> Filler </a></li>
        </ul>
       <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>

<div id="upload-header">

    <h2>File Upload</h2>

    <div id="upload-form">

        <form method="post" enctype="multipart/form-data">

            Select file to upload:

            <input type="file" name="fileToUpload" id="fileToUpload">

            <input type="submit" value="Upload File" name="submit">

        </form>

    </div>

    <div id="option-buttons">

        <h2>Options</h2>


            <select id="selectWafer">
                <option selected="selected">All Wafers</option>
                <option selected="selected">Show none</option>
            </select>


    </div>
</div>

<div id="container" style="display:None;"></div>
</body>
<?php
require "functions.php";
echo "<div id = \"test-res\">";

$toReturn = "";
if (isset($_POST['submit'])) {
    $name = $_FILES["fileToUpload"]["name"];
    $uploadOk = checkValid($_FILES["fileToUpload"]);

    echo "uploadOk: " . ($uploadOk ? 'True' : 'False');
    if ($uploadOk && move_uploaded_file($tmp_name, $target_file)) {
        $toReturn .= "The file " . basename($name) . " has been uploaded to " . $target_file;
        $toReturn .= "<br>executing python: ";
        $toReturn .= $name;
        ?>

        <script>
            var renderChart = function () {
                var chart;
                var options = {
                    chart: {
                        renderTo: 'container',
                        type: 'scatter',
                        zoomType: 'xy'
                    },
                    yAxis: {
                        min: 0,
                        max: 1500
                    },
                    series: [{}]
                };
                var url = "jsonp.php?callback=?&name=../<?php echo $target_file ?>";
                console.log(url);
                $.getJSON(url, function (data) {

                    //TODO dynamically make dropdown
                    var select = document.getElementById("selectWafer");
                    var cats = data.categories;
                    for (var i = 0; i < cats.length; i++) {
                        var opt = cats[i];
                        //console.log(opt)
                        var el = document.createElement("option");
                        el.textContent = opt;
                        el.val = opt;
                        select.appendChild(el);
                    }

                    points = data['allpoints'];
                    console.log(points);

                    options.series[0] = {'data': []};
                    total = Object.keys(points).length - 1;
                    for (var label in points) {

                        temp = {
                            name: label,
                            //color: 'rgba(223, 83, 83, .5)',
                            data: []
                        }

                        for (point in points[label]) {
                            temp.data.push([
                                total,
                                parseFloat(points[label][point])
                            ])
                        }
                        options.series.push(temp);

                        total--;
                    }

                    chart = new Highcharts.Chart(options);
                });
                document.getElementById("container").style.display = "block";
            };

            $("#selectWafer").change(function () {
                //console.log($(this).val())//.options[$(this).selectedIndex].text);
                var selVal  = $(this).val();
                //TODO update highchart to only show selected value
                var chart=$("#container").highcharts();

                for(i=0; i < chart.series.length; i++) {
                    //if(chart.series[i].selected == true ){ //&& chart.series[i].name !== $(this).val()
//                        chart.series[i].select();
//                        showSeries.call(chart.series[i], {checked: false});
                    //}
                    if (selVal == "All Wafers")
                        chart.series[i].show();
                    else if(chart.series[i].name == selVal)
                        chart.series[i].show();
                    else if (selVal == "Show none")
                        chart.series[i].hide();
                    else chart.series[i].hide();
                }


            });

        </script>
        <?php


        echo '<script>renderChart()</script>';

        echo $toReturn;
// echo "</div></div>";
    } else {
        $toReturn .= "Sorry, there was an error uploading your file." . print_r($_FILES);
        echo $toReturn;
    }
} else {
//echo "No files found";
} ?>
</html>