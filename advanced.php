<?php
session_start();
$values = $_GET;
if(isset($values['all'])) {
	$search_array = array(
		'highlight' => array(
			'fields' => array(
				'text' => array(
					'fragment_size' => 150,
					'number_of_fragments' => 3
				)
			)
		)
	);
	
	$source = "";
	if(isset($values['type']) && !empty($values['type']) && $values['type'] != 'both') {
		$source = $values['type'];
	}
	$search_array['query']['bool']['must'] = array();
	if(isset($values['all']) && !empty($values['all'])) {
		#$search_array['query']['match_all'] = $values['all'];
		#$search_array['bool']['must'] = array(array('match' => array('text' => $values['all'])));
		$search_array['query']['bool']['must'][] = array(
			'query_string' => array(
				'default_field' => "_all",
				'query' =>  $values['all']
			)
		);
	}
	if(isset($values['exact']) && !empty($values['exact'])) {
		#$search_array['filter']['match_phrase'] = array( "text" => array("query" => $values['exact'] ));
		$search_array['query']['bool']['must'][] = array(
			'text' => array(
				'text' =>   $values['exact']
			)
		);
	}
	/*if(isset($values['any']) && !empty($values['any'])) {
		$search_array['query']['match']["text"] = array(
			'query' => explode(" ", $values['any']),
			'operator' => 'or'
		);
	}
	*/
	if(!empty($values['from_date']) || !empty($values['to_date'])) {
		
		$range['range']["date"] = array();
		
		if(!empty($values['from_date'])) {
			$range['range']["date"]['gte'] = $values['from_date'];
		}
		
		if(!empty($values['to_date'])) {
			$range['range']["date"]['lte'] = $values['to_date'];
		}
		$search_array['query']['bool']['must'][] = $range;
		
	}
	if(isset($values['title']) && !empty($values['title'])) {
		$search_array['query']['bool']['must'][] = array(
			'query_string' => array(
				'default_field' => "title",
				'query' =>  $values['title']
			)
		);
	}
	if(isset($values['persons']) && !empty($values['persons'])) {
		$search_array['query']['bool']['must'][] = array(
			'query_string' => array(
				'default_field' => "persons",
				'query' =>  $values['persons']
			)
		);
	}
	if(isset($values['questions']) && !empty($values['questions'])) {
		$search_array['query']['bool']['must'][] = array(
			'query_string' => array(
				'default_field' => "questions",
				'query' =>  $values['questions']
			)
		);
	}
	if(isset($values['answers']) && !empty($values['answers'])) {
		$search_array['query']['bool']['must'][] = array(
			'query_string' => array(
				'default_field' => "answers",
				'query' =>  $values['answers']
			)
		);
	}
	$code = md5(json_encode($search_array));
	$_SESSION[$code] = array(
		'query' => $search_array,
		'source' => $source,
		'hash' => $values
	);
	header("Location: ./?advanced=" . $code);
	exit;
}
if(isset($_GET['hash'])) {
	$values = $_SESSION[$_GET['hash']]['hash'];
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
					<td>all these words:</td><td><input type="text" name="all" value="<?=$values['all']?>" /></td>
				</tr>
				<tr>
					<td>[NEEDS FIX] this exact word or phrase:</td><td><input type="text" name="exact" value="<?=$values['exact']?>" /></td>
				</tr>
				<tr>
					<td>[DOESNT WORK] any of these words:</td><td><input type="text" name="any" value="<?=$values['any']?>" /></td>
				</tr>
				<tr>
					<td>none of these words:</td><td><input type="text" name="none" value="<?=$values['none']?>" /></td>
				</tr>
				<tr>
					<td colspan="2"><h2>Then narrow your results by...</h2></td>
				</tr>
				<tr>
					<td>title:</td><td><input type="text" name="title" value="<?=$values['title']?>" /></td>
				</tr>
				<tr>
					<td>persons:</td><td><input type="text" name="persons" value="<?=$values['persons']?>" /></td>
				</tr>
				<tr>
					<td>questions:</td><td><input type="text" name="questions" value="<?=$values['questions']?>" /></td>
				</tr>
				<tr>
					<td>answers:</td><td><input type="text" name="answers" value="<?=$values['answers']?>" /></td>
				</tr>
				<tr>
					<td>from date:</td><td><input type="text" class="date" name="from_date" value="<?=$values['from_date']?>" /> to: <input type="text" class="date" name="to_date" value="<?=$values['to_date']?>" /></td>
				</tr>
				<tr>
					<td>[DOESNT WORK] type:</td><td>
						<label><input type="radio" name="type" value="both" checked="true"> Both</label>
						<label><input type="radio" name="type" value="kamervraag"> Kamervraag</label>
						<label><input type="radio" name="type" value="telegraafarticle"> Telegraaf article</label>
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
