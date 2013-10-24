<!DOCTYPE html>
<html>
<head>
  <title><?=$query?> - Marxgle</title>
  <link href="assets/css/bootstrap.min.css" media="all" rel="stylesheet" rev="stylesheet" type="text/css" />
  <link href="assets/css/style.css" media="all" rel="stylesheet" rev="stylesheet" type="text/css" />
  <script lang="javascript" type="text/javascript" src="assets/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" href="assets/timeline/css/timelinexml.sleek.css">
  
  <script src="assets/timeline/js/modernizr-2.0.6.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top" role="navigation">
		<a class="navbar-brand" href="./">
			<img src="assets/img/marxgle-small.png" />
		</a>
		<form class="navbar-form navbar-left" role="search">
         <div class="form-group">
           <input type="text" class="form-control" value="<?=$q?>" autocomplete="off" name="q">
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
				<li><a href="?q=<?=$q?>&source=kamervraag-bm25"><i>Kamervragen (BM25)</i></a></li>
				<li><a href="?q=<?=$q?>&source=telegraafarticle">Telegraaf</a></li>
				<li class="selected blue">
   				<a href="?timeline=<?=$q?>">Timeline</a>
				</li>
				<li class="blue">
   				<a href="?cloud=<?=$q?>">WordCloud</a>
				</li>
				<li class="blue">
   				<a href="?q=<?=$q?>&persons">Persons</a>
				</li>
				<li class="right">
					<a href="advanced.php">
   					<button type="button" class="btn btn-default">
   						<span class="glyphicon glyphicon-cog"></span>
   					</button>
					</a>
				</li>
			</ul>
			<div class="clear"></div>
		</div>
	<div class="container">
   	 <div class="demo-box">
   			<div id="my-timeline">
   				<div class="timeline-html-wrap" style="display: none">
   				  <?php
   				  foreach( $resultset as $result ):
   				  ?>
   				  <div class="timeline-event">
   						<div class="timeline-date"><?=date("d.m.Y",strtotime($result->getDate()))?></div>
   						<div class="timeline-title"><?=$result->getTitle()?></div>
   						<div class="timeline-content"><?=$result->getPreview()?></div>
   						<div class="timeline-link"><a href="<?=$result->getLink()?>">Lees verder</a></div>
   					</div>
   				  <?php
   				  endforeach;
   				  ?>
   				</div>
   			</div>
        </div>
			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
			<script defer src="assets/timeline/js/plugins.js"></script>
      	<script defer src="assets/timeline/js/mylibs/timelinexml.js"></script>
      	<script defer src="assets/timeline/js/script.js"></script>
      	<div class="yearresults">
      	  <?php
      	  // Reorder...
      	  $resultding = array();
      	  foreach( $resultset as $result ){
         	  $resultding[date("Y",strtotime($result->getDate()))][] = $result;
      	  }
      	  
      	  krsort( $resultding );
      	  ?>
      	   <?php
   			foreach( $resultding as $jaar => $rset ):
   			?>
   			<h3><?=$jaar?></h3>
   			<ul>
   			<?php
   			foreach( $rset as $result ):
   			?>
   			<li>
   			   <span class="date"><?=date("d.m.Y",strtotime($result->getDate()))?></span>
   			   <p><a href="<?=$result->getLink()?>"><?=$result->getTitle()?></a></p>
   			</li>
   			<?php
   			endforeach;
   			?>
   			</ul>
   			<?php
   			endforeach;
   			?>
      	</div>
	</div>
</body>
</html>
