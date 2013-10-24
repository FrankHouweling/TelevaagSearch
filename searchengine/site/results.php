<!DOCTYPE html>
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
  <title><?=$query?> - Marxgle</title>
  <link href="assets/css/bootstrap.min.css" media="all" rel="stylesheet" rev="stylesheet" type="text/css" />
  <link href="assets/css/style.css" media="all" rel="stylesheet" rev="stylesheet" type="text/css" />
  <script lang="javascript" type="text/javascript" src="assets/js/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top" role="navigation">
		<a class="navbar-brand" href="./">
			<img src="assets/img/marxgle-small.png" />
		</a>
		<form class="navbar-form navbar-left" role="search">
         <div class="form-group">
           <input type="text" class="form-control" value="<?=$_GET['q']?>" name="q">
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
			   <li
			   <?php
			      if( !isset($_GET['source']) ){
			         ?>
			         class="selected"
			         <?
			      }
			   ?>
			   ><a href="?q=<?=$_GET['q']?>">All sources</a></li>
				<li
				<?php
			      if( $_GET['source'] == "kamervraag" ){
			         ?>
			         class="selected"
			         <?
			      }
			   ?>
			   ><a href="?q=<?=$_GET['q']?>&source=kamervraag">Kamervragen</a></li>
				<li
				<?php
			      if( $_GET['source'] == "kamervraag-bm25" ){
			         ?>
			         class="selected"
			         <?
			      }
			   ?>
				><a href="?q=<?=$_GET['q']?>&source=kamervraag-bm25"><i>Kamervragen (BM25)</i></a></li>
				<li
				<?php
			      if( $_GET['source'] == "telegraafarticle" ){
			         ?>
			         class="selected"
			         <?
			      }
			   ?>
				><a href="?q=<?=$_GET['q']?>&source=telegraafarticle">Telegraaf</a></li>
				<li class="blue">
   				<a href="?timeline=<?=$_GET['q']?>">Timeline</a>
				</li>
				<li class="blue">
   				<a href="?cloud=<?=$_GET['q']?>">WordCloud</a>
				</li>
				<li class="blue">
   				<a href="?q=<?=$_GET['q']?>&persons">Persons</a>
				</li>
				<li class="right">
					<button type="button" class="btn btn-default" onclick="window.location = 'advanced.php<?=((isset($_GET['advanced'])) ? "?hash=".$_GET['advanced'] : "")?>';">
						<span class="glyphicon glyphicon-cog"></span>
					</button>
				</li>
			</ul>
			<div class="clear"></div>
		</div>
	<div class="container">
		<div class="info">
			Page <?=$page_num?> of about <?=$result_total_num?> results (<?=$processing_time?> seconds)
		</div>
		<?php foreach( $resultset as $result ): ?>
		<div class="results">
			<div class="result">
			   <div class="fix1">
   				<a href="<?=$result->getLink()?>"><?=$result->getTitle()?></a>
   				<span class="url">
   				 <?php 
   				  if( strpos($result->getLink(), "kranten.kb.nl" ) ){
      				  echo "Telegraaf - ";
   				  }
   				  else{
      				  echo "Kamervragen - ";
   				  }
   				 ?>
   				  <?=$result->getLink()?>
   				  </span>
   				<?php if($result->hasAuthor()): ?>
   				<span class="author">
   				  <a href="?author=<?=$result->getAuthor()?>"><?=$result->getAuthor()?></a>
   			   </span>
   				<?php endif; ?>
   				<p>
      				<?=$result->getPreview()?>
      				<span class="date">
      			      <?=$result->getDate()?>
      			   </span>
   				</p>
   			</div>
   			<a href="?doccloud=<?=$result->getId()?>&orc=<?=$_GET['q']?>">
   			   <button type="button" class="btn btn-default right">
   				  <span class="glyphicon glyphicon-cloud"></span>
   				</button>
   			</a>
   			<div class="clear"></div>
			</div>
		</div>
		<?php endforeach; ?>

		<div class="pagination">
			<ul>
				<li class="previous">
					<a href="#">
						<i class="glyphicon glyphicon-chevron-left"></i><br/>
						Previous
					</a>
				</li>
				<li>
					<img src="assets/img/m.png" /><br/><br/>
				</li>
				<? for($i=1;$i<10;$i++) { ?>
				<li class="number-item">
					<a href="#">
						<img border="0" src="assets/img/a.png" /><br/><?=$i?>
					</a>
				</li>
				<? } ?>
				<li>
					<img src="assets/img/rxgle.png" /><br/><br/>
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
