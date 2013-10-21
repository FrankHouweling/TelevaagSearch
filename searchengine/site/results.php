<!DOCTYPE html>
<html>
<head>
  <title>Results</title>
  <link href="css/bootstrap.min.css" media="all" rel="stylesheet" rev="stylesheet" type="text/css" />
  <link href="css/style.css" media="all" rel="stylesheet" rev="stylesheet" type="text/css" />
  <script lang="javascript" type="text/javascript" src="js/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top" role="navigation">
		<a class="navbar-brand" href="#">
			<img src="img/marxgle-small.png" />
		</a>
		<div class="collapse navbar-collapse navbar-ex7-collapse">
		<ul class="nav navbar-nav navbar-right">
			<li><a href="/logs">Marxgle+</a></li>
			<li><a href="/logs">Mmail</a></li>
		</ul>
		</div>
	</nav>
	<div class="container">
		<div class="info">
			Page 1 of about 100.000 results (0.22 seconds)
		</div>
		<div class="results">
			<div class="result">
				<a href="#">This is a search result</a>
				<span class="url">http://urltoresult.com/page/blaat/</span>
				<p>
					Ever since I <strong>bought</strong> my SSL certificate yesterday, my theme's main font no longer works. It gets automatically switched to Times New Roman. I now force a SSL ...
				</p>
			</div>
		</div>
		<div class="pagination">
			<ul>
				<li class="previous">
					<a href="#">
						<i class="glyphicon glyphicon-chevron-left"></i><br/>
						Previous
					</a>
				</li>
				<li>
					<img src="img/m.png" /><br/><br/>
				</li>
				<? for($i=1;$i<13;$i++) { ?>
				<li class="number-item">
					<a href="#">
						<img border="0" src="img/a.png" /><br/><?=$i?>
					</a>
				</li>
				<? } ?>
				<li>
					<img src="img/rxgle.png" /><br/><br/>
				</li>
				<li class="next">
					<a href="#">
						<i class="glyphicon glyphicon-chevron-right"></i><br/>
						Next
					</a>
				</li>
			</ul>
		</div>
	</div>
</body>
</html>
