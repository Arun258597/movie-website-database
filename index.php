<!doctype html>
<?php
	$page = $_GET["page"];
	$page_title = "";
	$releaseYear = "SUBSTRING(movie_released_date,1, 4)";
	$releaseMonth = "SUBSTRING(movie_released_date,6, 2)";
	$releaseDay = "SUBSTRING(movie_released_date,9, 2)";

	switch($page){ //Popluate different pages with different information
		
		case "main":
			$page_title = "Soon to be Released";
			//Newest movies to be released
			//(year > cYear) || (year = cYear && month > cMonth) || (year = cYear && month = cMonth && day >= cDay) 
			$QUERY = "SELECT * FROM btran6291_MOVIE WHERE ((".$releaseYear." > YEAR(CURDATE())) OR (".$releaseYear." = YEAR(CURDATE()) AND ".$releaseMonth." > MONTH(CURDATE())) OR (".$releaseYear." = YEAR(CURDATE()) AND ".$releaseMonth." = MONTH(CURDATE()) AND ".$releaseDay." >= DAY(CURDATE()))) ORDER BY movie_released_date asc";
			break;
		case "current":
			$page_title = "Now Showing";
			//Newest movies released in 2016 only
			$QUERY = "SELECT * FROM btran6291_MOVIE WHERE (".$releaseYear." = YEAR(CURDATE()) AND ".$releaseMonth." <= MONTH(CURDATE()) AND ".$releaseDay." <= DAY(CURDATE())) ORDER BY movie_released_date desc";
			break;
		case "topall":
			$page_title = "Best Movies of All Times";
			$QUERY = "SELECT * FROM btran6291_MOVIE WHERE movie_rating > 8.5 ORDER BY movie_rating desc";
			break;
		case "top15":
			$page_title = "The Best Movies of 2015";
			$QUERY = "SELECT * FROM btran6291_MOVIE WHERE movie_rating > 7.5 && ".$releaseYear." = 2015 ORDER BY movie_rating desc";
			break;
		case "top14":
			$page_title = "The Best Movies of 2014";
			$QUERY = "SELECT * FROM btran6291_MOVIE WHERE movie_rating > 7.5 && ".$releaseYear." = 2014 ORDER BY movie_rating desc";
			break;
		case "top13":
			$page_title = "The Best Movies of 2013";
			$QUERY = "SELECT * FROM btran6291_MOVIE WHERE movie_rating > 7.5 && ".$releaseYear." = 2013 ORDER BY movie_rating desc";
			break;
		case "top12":
			$page_title = "The Best Movies of 2012";
			$QUERY = "SELECT * FROM btran6291_MOVIE WHERE movie_rating > 7.5 && ".$releaseYear." = 2012 ORDER BY movie_rating desc";
			break;
		case "top11":
			$page_title = "The Best Movies of 2011";
			$QUERY = "SELECT * FROM btran6291_MOVIE WHERE movie_rating > 7.5 && ".$releaseYear." = 2011 ORDER BY movie_rating desc";
			break;
		case "top10":
			$page_title = "The Best Movies of 2010";
			$QUERY = "SELECT * FROM btran6291_MOVIE WHERE movie_rating > 7.5 && ".$releaseYear." = 2010 ORDER BY movie_rating desc";
			break;
		default: //Same as main
			$page_title = "New and Currently Showing Movies";
			$QUERY = "SELECT * FROM btran6291_MOVIE WHERE ".$releaseYear." = 2016 ORDER BY movie_released_date desc";
	}
?>
<html>

<head>
<title><?php echo $page_title; ?></title>
<link href='https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz|Ubuntu+Condensed|Fjalla+One|PT+Sans' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
<?php
	include "nav-bar.php";
?>

<div class="document">
	<br><h1><center><?php echo $page_title; ?></center></h1>
	<hr>
	<table width="100%">
		<?php
			//connection parameters
			include 'connect_server.php';

			$q = $conn->prepare($QUERY);
			if ($q->execute()){

			}else{
				echo $q->errorCode();
			}

			//Getting the data back
			$q->setFetchMode(PDO::FETCH_BOTH);
			
			while($r=$q->fetch()){
				//Generate Rows of Movies
				$movie_id= $r["ID"];
				$poster_url = $r["poster_url"];
				$movie_released_date= $r["movie_released_date"];
				$movie_title= $r["movie_title"];
				$movie_plot = $r["movie_plot"];
				//$movie_director= $r["movie_director"];
				//$movie_duration = $r["movie_duration"];
				$movie_rating= $r["movie_rating"];
				
				echo "<tr>";
				echo "<td width='20%'><a href='movie_page.php?id=".$movie_id."'><img src='". $poster_url ."' style='width:250px;height:350px;' class='imgBox'></a></td>";
				echo "<td width='65%' style='vertical-align:bottom;'><div class='movie_title' style='display:inline'>". $movie_title ."</div><p style='display:inline;margin-left:15px'>". formatDate($movie_released_date) ."<p>". $movie_plot. "</p></td>";
				echo "<td width='15%' align='center'><div class='movie_rating'>". $movie_rating . "/10</div></td>";
				echo "</tr>";
			}
			
			//Close connection
			$conn=null; 
			
		?>
		<?php
			function formatDate($date){
				$dateObject = date_create($date);
				return date_format($dateObject, "j F Y");
			}
		?>

	<table>
</div>

<footer>
	<p align="right">Sample Movies Data Obtained from IMDB</p>
</footer>

</body>

</html>