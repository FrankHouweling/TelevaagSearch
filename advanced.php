<?php
$values = $_GET;
if(isset($values['all'])) {
	$search_array = array(
		'query' => array(
		),
		'highlight' => array(
			'fields' => array(
				'text' => array(
					'fragment_size' => 150,
					'number_of_fragments' => 3
				)
			)
		)
	);
	if(isset($values['all']) && !empty($values['all'])) {
		$search_array['query']['match_all'] = $values['all'];
	}
	#echo json_encode($search_array);
	echo "<pre>" . print_r($search_array, true). "</pre>";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Marxgle Advanced search</title>
  <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
  <link href="assets/css/bootstrap.min.css" media="all" rel="stylesheet" rev="stylesheet" type="text/css" />
  <link href="assets/css/style.css" media="all" rel="stylesheet" rev="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="demo/images/favicon.ico">
	<link rel="stylesheet" href="assets/css/pickadate/themes/classic.css" id="theme_base">
	<link rel="stylesheet" href="assets/css/pickadate/themes/classic.date.css" id="theme_date">
	
	<!--[if lt IE 9]>
	    <script>document.createElement('section')</script>
	<![endif]-->
  <script lang="javascript" type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/pickadate/picker.js"></script>
    <script src="assets/js/pickadate/picker.date.js"></script>
    <script src="assets/js/pickadate/legacy.js"></script>
    <script src="assets/js/generic.js"></script>
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top" role="navigation">
		<a class="navbar-brand" href="#">
			<img src="assets/img/marxgle-small.png" />
		</a>
		<form class="navbar-form navbar-left" role="search">
         <div class="form-group">
           <input type="text" class="form-control" value="<?=$_GET['q']?>">
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
			   <li class="selected"><a href="#">Advanced search</a></li>
			</ul>
			<div class="clear"></div>
		</div>
	<div class="container advanced-search">
		<form action="advanced.php" method="get">
			<table cellpadding="5">
				<tr>
					<td colspan="2"><h2>Find pages with...</h2></td>
				</tr>
				<tr>
					<td>all these words:</td><td><input type="text" name="all" /></td>
				</tr>
				<tr>
					<td>this exact word or phrase:</td><td><input type="text" name="exact" /></td>
				</tr>
				<tr>
					<td>any of these words:</td><td><input type="text" name="any" /></td>
				</tr>
				<tr>
					<td>none of these words:</td><td><input type="text" name="none" /></td>
				</tr>
				<tr>
					<td colspan="2"><h2>Then narrow your results by...</h2></td>
				</tr>
				<tr>
					<td>title:</td><td><input type="text" name="title" /></td>
				</tr>
				<tr>
					<td>persons:</td><td><input type="text" name="persons" /></td>
				</tr>
				<tr>
					<td>questions:</td><td><input type="text" name="questions" /></td>
				</tr>
				<tr>
					<td>answers:</td><td><input type="text" name="answers" /></td>
				</tr>
				<tr>
					<td>from date:</td><td><input type="text" class="date" name="from_date" /> to: <input type="text" class="date" name="to_date" /></td>
				</tr>
				<tr>
					<td>type:</td><td>
						<label><input type="radio" name="type" value="both" checked="true"> Both</label>
						<label><input type="radio" name="type" value="both"> Kamervraag</label>
						<label><input type="radio" name="type" value="both"> Telegraaf article</label>
					</td>
				</tr>
				<tr>
					<td></td><td><button type="submit" class="btn btn-default">Marxgle Search</button></td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>
