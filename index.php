<?php

require __DIR__.'/bootstrap.php';

$search = '';
$table_rows = $locations = array();
if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($mysqli, $_GET['search']);
    $search = " AND (ssid LIKE '%$search%' OR encryption LIKE '%$search%')";
}
$sql = "SELECT * FROM wifi WHERE latitude != '' $search ORDER BY spotted DESC";
if ($result = $mysqli->query($sql)) {
    if ($mysqli->error) {
        printf("Error: %s\n", $mysqli->error);
        exit;
    }
    while ($row = $result->fetch_assoc()) {
        $row['markup'] = '<table>
            <tr><td>ID</td><td>'.$row['id'].'</td></tr>
            <tr><td>BSSID</td><td>'.$row['bssid'].'</td></tr>
            <tr><td>Latitude</td><td>'.$row['latitude'].'</td></tr>
            <tr><td>Longitude</td><td>'.$row['longitude'].'</td></tr>
            <tr><td>SSID</td><td>'.$row['ssid'].'</td></tr>
            <tr><td>Encrypt.</td><td>'.$row['encryption'].'</td></tr>
            <tr><td>Conn. mode</td><td>'.$row['connection_mode'].'</td></tr>
            <tr><td>Channel</td><td>'.$row['channel'].'</td></tr>
            <tr><td>Rx level</td><td>'.$row['receive_level'].'</td></tr>
            <tr><td>Spotted</td><td>'.$row['spotted'].'</td></tr>
            <tr><td>Created</td><td>'.$row['created'].'</td></tr>
            <tr><td>Updated</td><td>'.$row['updated'].'</td></tr>
        </table>';
        $locations[] = $row;
        $table_rows[] = '<tr>
            <td>'.$row['id'].'</td>
            <td>'.$row['bssid'].'</td>
            <td>'.$row['latitude'].'</td>
            <td>'.$row['longitude'].'</td>
            <td>'.$row['ssid'].'</td>
            <td>'.$row['encryption'].'</td>
            <td>'.$row['connection_mode'].'</td>
            <td>'.$row['channel'].'</td>
            <td>'.$row['receive_level'].'</td>
            <td>'.$row['spotted'].'</td>
            <td>'.$row['created'].'</td>
            <td>'.$row['updated'].'</td>
        </tr>';
    }
    $result->close();
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Map WiFi spots</title>
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
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="import.php">Import</a></li>
                </ul>
                <form action="index.php" class="navbar-form navbar-right">
                    <div class="form-group">
                        <input type="text" name="search" placeholder="SSID, encryption, ..." class="form-control" value="<?php if (!empty($_GET['search'])) echo htmlspecialchars($_GET['search']); ?>">
                    </div>
                    <button type="submit" class="btn btn-success">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row placeholders">
                    <div id="map"></div>
                </div>

                <h2 class="sub-header"><abbr title="Access Points">AP</abbr>s (<?php echo count($table_rows); ?>)</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>BSSID</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>SSID</th>
                                <th>Encryption</th>
                                <th>Connection_mode</th>
                                <th>Channel</th>
                                <th>Receive_level</th>
                                <th>Spotted</th>
                                <th>Created</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo implode(PHP_EOL, $table_rows); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>var locations = <?php echo json_encode($locations); ?>;</script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="jquery.min.js"><\/script>')</script>
    <script src="https://maps.google.com/maps/api/js?callback=initMap" async defer></script>
    <script src="script.js"></script>
</body>
</html>
