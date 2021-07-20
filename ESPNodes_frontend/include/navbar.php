<nav class="navbar navbar-expand-md navbar-dark bg-primary">
	<div class="container-fluid">
		<a class="navbar-brand" href="index.php"><?php echo $PAGE_TITLE; ?></a>

	<!---	uncomment below for hamburger menu on small screens --->
	<!---
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="true" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

	<!---	<div class="collapse navbar-collapse" id="navbarColor01"> --->
		<div class="navbar-collapse" id="navbarColor01">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link <?php if($page==="sensors"){echo "active";} ?>" href="?page=sensors">Sensors</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php if($page==="actuators"){echo "active";} ?>" href="?page=actuators">Actuators</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php if($page==="logs"){echo "active";} ?>" href="?page=logs">Logs</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php if($page==="config"){echo "active";} ?>" href="?page=config">Config</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php if($page==="timers"){echo "active";} ?>" href="?page=timers">Timers</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
