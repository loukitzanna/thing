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

        <form>

            <input type="radio" name="script" value="script1">py1
            <input type="radio" name="script" value="script2">py2
            <input type="radio" name="script" value="script3">py3
            <input type="radio" name="script" value="script4">py4
        </form>
        <form>
            <input type="checkbox" name="script" value="script1">py1
            <input type="checkbox" name="script" value="script2">py2
            <input type="checkbox" name="script" value="script3">py3
            <input type="checkbox" name="script" value="script4">py4
        </form>
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
        var options = {
            chart: {
                renderTo: 'container',
                type: 'scatter'
            },
            series: [{}]
        };
        var url = "jsonp.php?callback=?&name=../<?php echo $target_file ?>";
        console.log(url);
        $.getJSON(url, function (data) {
            console.log(data);
            //data.sort();
            //var i = 0;
            for (var label in data){
                console.log(options.series);
                console.log(label);

                temp = {
                    name: label,
                    data:data[label]
                }

                //options.series[i].

                //TODO: not this
                options.series.push(temp);
                //i++;
            }


            //options.series[0].data = data["numbers"];
            var chart = new Highcharts.Chart(options);
        });
        document.getElementById("container").style.display = "block";
    }
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