--Software Package Implementation--

--Scraping Data--
To scrape data, create a directory "scraped_pages" in the same directory as scrape.php. Then run scrape.php. It will download HTML pages from Scores and Odds from dates that are candidates for NFL games. It then loops through all downloaded pages, writing all found game data into a csv file, data.csv.

--Analyzing Data Based On Initial Models--
The files python_test.csv and python_train.csv are derived from the above generated data.csv. Ensure these are placed in the same directory as initial_models.py. Running initial_models.py, as written, will provide the initial analysis for away team teasers. It is easily modified to analyze home team teasers.

--SQL Query to Calculate Teaser Ratios for Lines and Seasons--
Database1.accdb is a Microsoft Access database with a table of game data derived from the above data.csv. It includes 4 queries: teasers_away, teasers_home, teasers_favored, teasers_underdog. Running each one will generate the data necessary to populate the similarly named csv files that are included here.

--Final Analysis--
Make sure the teasers_away.csv, teasers_home.csv, teasers_favored.csv, and teasers_underdog.csv files are placed in the same directory as final_models.py. Running final_models.py, as written, will provide the final analysis for underdog teasers. It is easily modified to analyze the other data sets.

