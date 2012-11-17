Very basic implementation of a URL shortener.
Could be a lot prettier; dynamic page loading, etc.

Important parts;
* .htaccess needs to have this line: ErrorDocument 404 /shortURL.php
* variables.php needs to have all your database data in it. Make sure it's
inaccessible from outsite your webserver or someone gets all your database
info.

Essential Table;
+-----------+--------------+------+-----+-------------------+-------+
| Field     | Type         | Null | Key | Default           | Extra |
+-----------+--------------+------+-----+-------------------+-------+
| shortURL  | varchar(30)  | NO   | PRI | NULL              |       |
| longURL   | varchar(254) | NO   |     | NULL              |       |
| userID    | varchar(20)  | YES  |     | NULL              |       |
| createdon | timestamp    | NO   |     | CURRENT_TIMESTAMP |       |
+-----------+--------------+------+-----+-------------------+-------+
userID is not essential. I use it for notarising manual entries.

Non-essential table;
+-----------+-------------+------+-----+-------------------+-------+
| Field     | Type        | Null | Key | Default           | Extra |
+-----------+-------------+------+-----+-------------------+-------+
| URL       | varchar(30) | NO   | MUL | NULL              |       |
| userIP    | varchar(15) | NO   |     | NULL              |       |
| visitedon | timestamp   | NO   |     | CURRENT_TIMESTAMP |       |
+-----------+-------------+------+-----+-------------------+-------+
URL references shortURL

HOW TO
Navigate to shorten.php and input a URL to shorten.
If you set up the database(s) correctly, it should load shortenURL.php which
presents you with the shortened URL.
