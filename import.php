<?php

require __DIR__.'/bootstrap.php';

$output = '';
if (!empty($_FILES['import']['tmp_name'])) {
    if (($handle = fopen($_FILES['import']['tmp_name'], 'r')) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
            $data = array_map('trim', $data);
            if ('BSSID' == $data[0]) {
                continue;
            }
            $spotted = date('Y-m-d H:i:s', strtotime($data[9].' '.$data[10]));

            $sql = "INSERT INTO wifi (
                bssid
               ,latitude
               ,longitude
               ,ssid
               ,encryption
               ,connection_mode
               ,channel
               ,receive_level
               ,spotted
               ,created
            ) VALUES (
                '{$data[0]}'
               ,'{$data[1]}'
               ,'{$data[2]}'
               ,'{$data[3]}'
               ,'{$data[4]}'
               ,'{$data[6]}'
               ,'{$data[7]}'
               ,'{$data[8]}'
               ,'{$spotted}'
               ,NOW()
            )";
            $mysqli->query($sql);
            if ($mysqli->error) {
                echo $mysqli->error;
                exit;
            }
        }
        fclose($handle);

        $output = 'Imported';
    }
    else {
        $output = 'Can not open for read';
    }
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Import</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Map WiFi</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a class="active" href="import.php">Import</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form class="import-form" action="import.php" method="post" enctype="multipart/form-data">
                    <div class="jumbotron">
                        <?php if (empty($_FILES['import']['tmp_name'])): ?>
                        <h1>Import</h1>
                        <p>Upload <a href="https://play.google.com/store/apps/details?id=de.carknue.gmon2" target="_blank">G-Mon app</a> export file</p>
                        <div class="form-group"><input type="file" name="import"></div>
                        <p>
                            <button type="submit" class="btn btn-success">Upload</button>
                        </p>
                        <?php else:?>
                            <?php echo $output; ?>
                        <?php endif ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
