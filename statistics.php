<?php include_once 'config.php';
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php
if (!empty($_POST['year'])) {
$selection = $_POST['year'];  
}else{
$selection = "null"; 
}

if($selection == 'lastyear'){
	$years = "Last year data";
	$rows_ex = $conn->query("select SUM(Stock) as book from inventory where date>=DATE_SUB(NOW(),INTERVAL 1 YEAR);");
	$rows_ebooks = $conn->query("select SUM(Stock) as ex_e from ebooks where date>=DATE_SUB(NOW(),INTERVAL 1 YEAR);");
	$rows_upcoming = $conn->query("select count(*) as ex_u from upcoming where date>=DATE_SUB(NOW(),INTERVAL 1 YEAR);");
}elseif($selection == 'last2years'){
	$years = "Last 2 years data";
	$rows_ex = $conn->query("select SUM(Stock) as book from inventory where date>=DATE_SUB(NOW(),INTERVAL 2 YEAR);");
	$rows_ebooks = $conn->query("select SUM(Stock) as ex_e from ebooks where date>=DATE_SUB(NOW(),INTERVAL 2 YEAR);");
	$rows_upcoming = $conn->query("select count(*) as ex_u from upcoming where date>=DATE_SUB(NOW(),INTERVAL 2 YEAR);");
}else{
	$years = "Last 3 years data";
	$rows_ex = $conn->query("select SUM(stock) as book from inventory where date>=DATE_SUB(NOW(),INTERVAL 3 YEAR);");
	$rows_ebooks = $conn->query("select SUM(Stock) as ex_e from ebooks where date>=DATE_SUB(NOW(),INTERVAL 3 YEAR);");
	$rows_upcoming = $conn->query("select count(*) as ex_u from upcoming where date>=DATE_SUB(NOW(),INTERVAL 3 YEAR);");
}

if(mysqli_num_rows($rows_ex)>0){
	  while($rows_data = $rows_ex->fetch_assoc()){
		  $books = $rows_data['book'];
	  }
}else{
	$books=0;
}
if(!$books){
	$books=0;
}

if(mysqli_num_rows($rows_ebooks)>0){
	  while($rows_data = $rows_ebooks->fetch_assoc()){
		  $ebooks = $rows_data['ex_e'];
	  }
}else{
	$ebooks=0;
}
if(!$ebooks){
	$ebooks=0;
}
if(mysqli_num_rows($rows_upcoming)>0){
	  while($rows_data = $rows_upcoming->fetch_assoc()){
		  $upcoming = $rows_data['ex_u'];
	  }
}else{
	$upcoming=0;
}

if(!$upcoming){
	$upcoming=0;
}


$mydata = array("books"=>$books, "ebooks"=>$ebooks, "upcoming"=>$upcoming);
?>
<head>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable([
		['Book Type','count',{role:'style'}],
		['Books', <?php echo $mydata["books"];?>],
        ['eBooks', <?php echo $mydata["ebooks"];?>],
        ['UpcomingBooks', <?php echo $mydata["upcoming"];?>]
		]);
     
var options = {
          title: 'Total Books: <?php echo $years;?>' ,
		  width: 500,
		  height: 300,
		  legend:'none',
		  is3D: true
        };
//
      // Instantiate and draw the chart.
      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
  </script>
</head>
<body>


<div class="container">

<form method="post" action="statistics.php">
<select name="year" onchange="form.submit()">
     <option disabled="disabled" selected="selected">Years</option>
     <option value="lastyear"> Last Year data</option>
     <option value="last2years">Last 2 Years data</option>
     <option value="last3years">Last 3 Years data</option>
</select>
</form>
<div id="chart_div"></div>
</div>
<?php include 'footer.php';?>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/modernizr.js"></script>
    <script src="js/bootstrap-hover-dropdown.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/front.js"></script>


</body>

</html>