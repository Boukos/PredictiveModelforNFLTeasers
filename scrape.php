<?php

function url($date) {
	$url = "http://archive.scoresandodds.com/pgrid_";
	$url .= date("Ymd", $date);
	$url .= ".html";
	return $url;
}

function url2($date) {
	$url = "http://www.scoresandodds.com/pgrid_";
	$url .= date("Ymd", $date);
	$url .= ".html";
	return $url;
}


function scoresandodds() {
	$urls = "";
	$date = '2006-09-01';
	$date1 = '2009-09-01';
	$today = '2016-11-28';
	while (strtotime($date) <= strtotime($date1)) {
		$month = intval(date( "n", strtotime($date) ));
		$day = date( "D", strtotime($date) );
		if ($month == 1 || $month >= 9) {
			if ($day == "Sun" || $day == "Mon" || $day == "Thu" || $day == "Sat") {
				file_put_contents( "scraped_pages/" . date( "Ymd", strtotime($date) ) . ".txt" , file_get_contents(url(strtotime($date))) );		
			}
		}
		$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));	
	}
	while (strtotime($date) <= strtotime($today)) {
		$month = intval(date( "n", strtotime($date) ));
		$day = date( "D", strtotime($date) );
		if ($month == 1 || $month >= 9) {
			if ($day == "Sun" || $day == "Mon" || $day == "Thu" || $day == "Sat") {
				file_put_contents( "scraped_pages/" . date( "Ymd", strtotime($date) ) . ".txt" , file_get_contents(url2(strtotime($date))) );		
			}
		}
		$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));	
	}
}

function scrape_old(&$list) {
	$files = scandir("scraped_pages/");
	foreach ($files as $file) {
		$filename = substr($file, 0, 8);
		$contents = file_get_contents("scraped_pages/" . $file);
		if (strpos($contents, '<span class="section_name">NFL</span>') !== false) {
			preg_match("/<span class=\"section_name\">NFL<\/span>.*?<span class=\"date\">(.*?)<\/span>(.*?)<\/table>/s",$contents,$match);
			$date=$match[1];
			$nfl=$match[2];
			preg_match_all("/<tbody(.*?)<\/tbody>/s",$nfl,$games);
			foreach($games[0] as $game) {
				
				preg_match("/<span.*?time.*?>(.*?)<\/span>/",$game,$match);
				$time = $match[1];
				
				preg_match_all("/game_team(.*?)<\/tr>/s",$game,$team);
				
				$home_team = $team[0][1];
				preg_match("/team_name.*?>.*?>(.*?)<\/a>/s",$home_team,$match);
				$home_team_name = $match[1];
				if (intval($filename) > 20060916) {
					preg_match_all("/<td(.*?)<\/td>/s",$home_team,$tds);
					preg_match("/(\d+)/s",$tds[0][5],$match);
					$home_team_score = $match[1];
					if( preg_match("/PK/s",$tds[0][3],$match) ) {	
						$home_team_line = 0;					
						$away_team_line = 0;
					} elseif( preg_match("/(-[\.\d]+)/s",$tds[0][3],$match) ) {
						$home_team_line = $match[1];	
					} else {
						$home_team_line = "nothing";
					}
				}
				else {
					preg_match_all("/<td(.*?)<\/td>/s",$home_team,$tds);
					preg_match("/(\d+)/s",$tds[0][5],$match);
					$home_team_score = $match[1];
					if( preg_match("/PK/s",$tds[0][2],$match) ) {	
						$home_team_line = 0;					
						$away_team_line = 0;
					} elseif( preg_match("/(?<!\()(-[\.\d]+)/s",$tds[0][2],$match) ) {
						$home_team_line = $match[1];	
					} else {
						$home_team_line = "nothing";
					}					
				}
				
				
				
				$away_team = $team[0][0];
				preg_match("/team_name.*?>.*?>(.*?)<\/a>/s",$away_team,$match);
				$away_team_name = $match[1];
				preg_match_all("/<td(.*?)<\/td>/s",$away_team,$tds);
				preg_match("/(\d+)/s",$tds[0][5],$match);
				$away_team_score = $match[1];
				if (intval($filename) > 20060916) {
					if ($home_team_line == "nothing") {
						if ( preg_match("/PK/s",$tds[0][3],$match) ) {
							$away_team_line = 0;
							$home_team_line = 0;							
						} elseif( preg_match("/(-[\.\d]+)/s",$tds[0][3],$match)) {
							$away_team_line = $match[1];
							$home_team_line = floatval($away_team_line) * (-1);
						}
					} else {
						$away_team_line = floatval($home_team_line) * (-1);	
					}
				}
				else {
					if ($home_team_line == "nothing") {
						if ( preg_match("/PK/s",$tds[0][6],$match) ) {
							$away_team_line = 0;
							$home_team_line = 0;							
						} elseif( preg_match("/(?<!\()(-[\.\d]+)/s",$tds[0][2],$match)) {
							$away_team_line = $match[1];
							$home_team_line = floatval($away_team_line) * (-1);
						}
					} else {
						$away_team_line = floatval($home_team_line) * (-1);	
					}
				}
				
				
				$list .= "$file,$date,$time,$home_team_name,$away_team_name,$home_team_score,$away_team_score,$home_team_line,$away_team_line\n";
			}			
		}
	}
}

function scrape_new(&$list) {
	$files = scandir("scraped_pages/");
	foreach ($files as $file) {
		$contents = file_get_contents("scraped_pages/" . $file);
		if (strpos($contents, '<span class="league">NFL</span>') !== false) {	
			preg_match("/<span class=\"league\">NFL<\/span>.*?<span class=\"date\">(.*?)<\/span>(.*?)<\/table>/s",$contents,$match);
			$date=$match[1];
			$nfl=$match[2];
			preg_match_all("/(<tr.*?<tr.*?time.*?<tr.*?\"notes bottom\")/s",$nfl,$games);
			foreach($games[0] as $game) {
				
				preg_match("/<span.*?time.*?>(.*?)<\/span>/",$game,$match);
				$time = $match[1];
				
				preg_match_all("/<tr(.*?teamName.*?)<\/tr>/s",$game,$team);
				
				$home_team = $team[0][1];
				preg_match("/teamName.*?>.*?>(.*?)<\/a>/s",$home_team,$match);
				$home_team_name = $match[1];
				preg_match_all("/<td(.*?)<\/td>/s",$home_team,$tds);
				preg_match("/(\d+)/s",$tds[0][6],$match);
				$home_team_score = $match[1];
				if( preg_match("/PK/s",$tds[0][4],$match) ) {	
					$home_team_line = 0;			
					$away_team_line = 0;
				} elseif( preg_match("/(-[\.\d]+)/s",$tds[0][4],$match) ) {
					$home_team_line = $match[1];	
				} else {
					$home_team_line = "nothing";
				}
				
				
				
				$away_team = $team[0][0];
				preg_match("/teamName.*?>.*?>(.*?)<\/a>/s",$away_team,$match);
				$away_team_name = $match[1];
				preg_match_all("/<td(.*?)<\/td>/s",$away_team,$tds);
				preg_match("/(\d+)/s",$tds[0][5],$match);
				$away_team_score = $match[1];
				if ($home_team_line == "nothing") {
					if ( preg_match("/PK/s",$tds[0][3],$match) ) {
						$away_team_line = 0;
						$home_team_line = 0;							
					} elseif( preg_match("/(-[\.\d]+)/s",$tds[0][3],$match)) {
						$away_team_line = $match[1];
						$home_team_line = floatval($away_team_line) * (-1);
					}
				} else {
					$away_team_line = floatval($home_team_line) * (-1);	
				}
				
				$list .= "$file,$date,$time,$home_team_name,$away_team_name,$home_team_score,$away_team_score,$home_team_line,$away_team_line\n";
			}			
		}
	}
}

function scrape() {
	$list = "file,date,time,home team, away team, home team score, away team score, home team line, away team line\n";
	scrape_old($list);
	scrape_new($list);
	file_put_contents("data.csv",str_replace("&nbsp;"," ",$list));
}

scoresandodds();
scrape();
?>