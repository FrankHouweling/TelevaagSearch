<!DOCTYPE html>
<html>
<head>
  <title><?=$query?> - Marxgle</title>
  <link href="assets/css/bootstrap.min.css" media="all" rel="stylesheet" rev="stylesheet" type="text/css" />
  <link href="assets/css/style.css" media="all" rel="stylesheet" rev="stylesheet" type="text/css" />
  
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  
  <script lang="javascript" type="text/javascript" src="assets/js/bootstrap.min.js"></script>
  
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top" role="navigation">
		<a class="navbar-brand" href="#">
			<img src="assets/img/marxgle-small.png" />
		</a>
		<form class="navbar-form navbar-left" role="search">
         <div class="form-group">
           <input type="text" class="form-control" value="<?=$q?>" name="q">
         </div>
         <button type="submit" class="btn btn-default">Search</button>
       </form>
		<div class="collapse navbar-collapse navbar-ex7-collapse">
		<ul class="nav navbar-nav navbar-right">
			<li><a href="/logs">Marxgle+</a></li>
			<li><a href="/logs">Mmail</a></li>
		</ul>
		</div>
	</nav>
		<div class="filter">
			<ul>
			   <li><a href="?q=<?=$q?>">All sources</a></li>
				<li><a href="?q=<?=$q?>&source=kamervraag">Kamervragen</a></li>
				<li><a href="?q=<?=$q?>&source=telegraafarticle">Telegraaf</a></li>
				<li class="blue">
   				<a href="?timeline=<?=$q?>">Timeline</a>
				</li>
				<li class="blue">
				  <a href="?cloud=<?=$q?>">WordCloud</a>
				</li>
				<li class="selected blue">
				  <a href="?q=<?=$q?>&persons">Persons</a>
				</li>
				<li class="right">
					<button type="button" class="btn btn-default">
						<span class="glyphicon glyphicon-cog"></span>
					</button>
				</li>
			</ul>
			<div class="clear"></div>
		</div>
	<div class="container">
	  <h3>Belangrijke personen:</h3>
	  <ul class="personlist">
	  <?php
	     foreach( $personlist as $person => $cnt ):
	  ?>
	  <li>
	     <a href="">
	        <span><?=$cnt?></span>
	        <p><?=$person?></p>
	     </a>
	     <div class="clear"></div>
	  </li>
	  <?php endforeach;?>
	</div>
	</ul>
</body>
</html>
