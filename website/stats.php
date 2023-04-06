<?php
include('dbConnection.php');

$stmt = $conn->prepare("SELECT region, COUNT(*) as num_threads FROM threads GROUP BY region");

if ($stmt->execute()) {
    $result = $stmt->get_result();
}

$data = array();
$data[] = array('Region', 'Number of threads');
while ($row = $result->fetch_assoc() ) {
  $data[] = array($row['region'], (int)$row['num_threads']);
}
$json_data = json_encode($data);
$conn->close();
?>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    // google chart stuff
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo $json_data; ?>);

        var options = {
          title: 'Number of Threads by Region',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart'));
        chart.draw(data, options);
      }
    </script>
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/stats.css">
  </head>
  <?php include('header.php'); ?>
  <body>
    <div id="chart" ></div>
  </body>
</html>
