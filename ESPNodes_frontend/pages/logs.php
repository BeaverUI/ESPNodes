<script type="text/javascript" src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<p>Recorded log data is shown here.</p>

<script type="text/javascript">
<?php
include('config/logs.php');

foreach($log_plot as $log_plot_index=>$log_plot_key){
	$sensor_number=0;
?>
$(document).ready(function() {
	var plot_data=[];

	$.when(
<?php
	foreach($log_plot_key as $node_index=>$node_key){
		foreach($node_key as $sensor_index=>$sensor_key){
?>
		$.get("logs/<?php echo $node_index; ?>/<?php echo $sensor_key; ?>.csv", function(csv) {
			var x = [], y = [], csvLines = [], points = [];

			csvLines = csv.split(/[\r?\n|\r|\n]+/);

			for (var i = 0; i < csvLines.length; i++){
				if (csvLines[i].length > 0) {
					points = csvLines[i].split(";");
					const dateObject = new Date(points[0]*1000);
					x.push(dateObject);
					y.push(points[1]);
				}
			}

			var trace = {
			  x: x,
			  y: y,
			  type: 'line',
			  name: '<?php echo $node_sensors[$node_index][$sensor_key][0]; ?>'
			};

			plot_data[<?php echo $sensor_number; ?>]=trace;
		}).fail(function() {
			alert('Error downloading logs/<?php echo $node_index; ?>/<?php echo $sensor_key; ?>.csv'); // or whatever
		}),
<?php
		$sensor_number++;
		}
	}
?>
	).then(function() {

		var layout = {
			xaxis: {
				type: 'date',
				title: 'Time'
			},
			yaxis: {
				title: 'Sensor value'
			},
			margin: {
				t: 25,
				b: 100,
				l: 50,
				r: 0
			},
			legend: {
				yanchor: 'top',
				xanchor: 'left',
				x: 0.01,
				y: 0.99,
				bgcolor: 'rgba(0.4,0.4,0.6,0.2)'
			},
			
			plot_bgcolor:"#FFFFFF",
			paper_bgcolor:"#f0f5fa"
		};

		var config = {responsive: true};

		Plotly.newPlot('log_<?php echo $log_plot_index; ?>', plot_data, layout, config);
	});
});

<?php
}
?>
</script>

<?php
foreach($log_plot as $log_plot_index=>$log_plot_key){
	echo '<hr class="bg-primary border-3 border-top border-primary">';	
	echo '<center><h3>'. $log_plot_title[$log_plot_index] . '</h3></center>';
	echo '<div id="log_'.$log_plot_index.'" style="width:100%; height:550px;"></div>'.PHP_EOL;
}
?>
