<nav class="navbar navbar-expand navbar-dark bg-primary">
	<div class="container">
  		<a class="navbar-brand" href="index.php"><?php echo $PAGE_TITLE; ?></a>
    <ul class="navbar-nav mr-auto">
		<li class="nav-item <?php if($page==="sensors"){echo "active";} ?>">
            <a class="nav-link" href="?page=sensors">Sensors</a>
      	</li>
		<li class="nav-item <?php if($page==="config"){echo "active";} ?>">
        	<a class="nav-link" href="?page=config">Config</a>
      	</li>
		<li class="nav-item <?php if($page==="timers"){echo "active";} ?>">
        	<a class="nav-link" href="?page=timers">Timers</a>
      	</li>
    </ul>
	</div>
</nav>