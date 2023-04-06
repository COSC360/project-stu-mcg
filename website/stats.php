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

$stmt2 = $conn->prepare("SELECT threads.threadId, threads.threadTitle, COUNT(replies.replyId) AS replyCount
FROM threads
LEFT JOIN replies ON threads.threadId = replies.thread
GROUP BY threads.threadId
ORDER BY replyCount DESC
LIMIT 10
");
if ($stmt2->execute()) {
  $result = $stmt2->get_result();
}
$data2 = array();
$data2[] = array('Thread title', 'Number of replies');
while ($row = $result->fetch_assoc() ) {
  $data2[] = array($row['threadTitle'], (int)$row['replyCount']);
}
$json_data2 = json_encode($data2);
$conn->close();
?>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    // google chart stuff
      google.charts.load('current', {'packages':['corechart', 'table']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo $json_data; ?>);

        var options = {
          title: 'Number of Threads by Region',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart'));
        chart.draw(data, options);

        // Call drawTable() function here
        drawTable();
      }

      function drawTable() {
        var data = google.visualization.arrayToDataTable(<?php echo $json_data2; ?>);

        var options = {
          showRowNumber: true,
          width: '60%',
          height: '100%'
        };

        var table = new google.visualization.Table(document.getElementById('chart2'));

        table.draw(data, options);
      }
    </script>
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/stats.css">
  </head>
  <?php include('header.php'); ?>
  <?php
    if(!isset($_SESSION['isAdmin'])){
        die("Must be signed in as admin user to view this page");
    }
  ?>
  <body>
    <div id="chart"></div>
    <h2>Top 10 Threads by engagement: </h2>
    <div id="chart2"></div>
  </body>
</html>
