<!DOCTYPE html>
<html>
 <head>
  <title>Product Chart</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <style type="text/css">
   .box{
    width:800px;
    margin:0 auto;
   }
  </style>
  <script type="text/javascript">
   var analytics = <?php echo $Title; ?>

   google.charts.load('current', {'packages':['corechart']});

   google.charts.setOnLoadCallback(drawProductChart);

   function drawProductChart()
   {
    var data = google.visualization.arrayToDataTable(analytics);
    var options = {
     title : 'Percentage of products in orders'
    };
    var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
    chart.draw(data, options);
   }
 
   var stateanalytics = <?php echo $State; ?>

   google.charts.load('current', {'packages':['corechart']});

   google.charts.setOnLoadCallback(drawStateChart);

   function drawStateChart()
   {
    var statedata = google.visualization.arrayToDataTable(stateanalytics);
    var options = {
     title : 'Percentage of orders per state'
    };
    var chart = new google.visualization.PieChart(document.getElementById('statepie_chart'));
    chart.draw(statedata, options);
   }
  
   var statusanalytics = <?php echo $Status; ?>

   google.charts.load('current', {'packages':['corechart']});

   google.charts.setOnLoadCallback(drawStatusChart);

   function drawStatusChart()
   {
    var statusdata = google.visualization.arrayToDataTable(statusanalytics);
    var options = {
     title : 'Percentage of order status per order'
    };
    var chart = new google.visualization.PieChart(document.getElementById('statuspie_chart'));
    chart.draw(statusdata, options);
   }
  </script>
 </head>
 <body>
  <br />
  <div class="container">
   <h3 align="center">Fastfood Statistic Chart</h3><br />
   
   <div class="panel panel-default">
    <div class="panel-heading">
     <h3 class="panel-title">Stats for order status, order state and order products</h3>
    </div>
    <div class="panel-body" align="center">
     <div id="pie_chart" style="width:750px; height:450px;">

     </div>
     <div id="statepie_chart" style="width:750px; height:450px;">

     </div>
     <div id="statuspie_chart" style="width:750px; height:450px;">

     </div>
    </div>
   </div>
   
  </div>
 </body>
</html>

