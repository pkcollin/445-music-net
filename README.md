#Music Net - PHP Site for CS 445
Database schema:
```SQL
Albums:
album_id - CHAR(6) PRIMARY KEY
name - CHAR(255)

Artists:
artist_id - char(18) PRIMARY KEY
name - CHAR(255)
location - CHAR(255)

Events:
event_id - INT PRIMARY KEY
user_id - char(32) FOREIGN KEY REFERENCES Users(user_id)
song_id - char(18) FOREIGN KEY REFERENCES Songs(song_id)
etype - ENUM('play','rating')
tstamp - TIMESTAMP

Followers:
follower - CHAR(32) PRIMARY KEY, FOREIGN KEY REFERENCES Users(user_id)
leader - CHAR(32) PRIMARY KEY, FOREIGN KEY REFERENCES Users(user_id)

Plays:
user_id - CHAR(32) PRIMARY KEY, FOREIGN KEY REFERENCES Users(user_id)
song_id - CHAR(18) PRIMARY KEY, FOREIGN KEY REFERENCES Songs(song_id)
plays - SMALLINT

Ratings:
user_id - char(32) PRIMARY KEY, FOREIGN KEY REFERENCES Users(user_id)
song_id - char(18) PRIMARY KEY, FOREIGN KEY REFERENCES Songs(song_id)
rating - TINYINT

Songs:
song_id - CHAR(18) PRIMARY KEY
duration - DOUBLE
loudness - DOUBLE
title - CHAR(255)
year - SMALLINT
album_id - CHAR(6) FOREIGN KEY REFERENCES Artists(artist_id)
artist_id - CHAR(18) FOREIGN KEY REFERENCES Albums(album_id)

Tags:
song_id - CHAR(18)
term - CHAR(255)
weight - DOUBLE

Users:
user_id - CHAR(32) PRIMARY KEY
username - CHAR(32)
password - CHAR(16)
age - TINYINT
gender - CHAR(6)
location - CHAR(32)
```
