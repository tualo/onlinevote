<?php
$data = '{
    "username": "y1Mz9CbR",
    "pwhash": "$2y$10$iWdY9bDFDrUMZMqeSN3Hd.Os0lK1e743CHyq61KxYeuBlSsEZdCmC",
    "possible_ballotpapers": "[{\"stimmzettel\": \"4|0\", \"state\": \"1|0\", \"voter_id\": 71, \"ballotpaper_id\": \"4\", \"canvote\": 1},{\"stimmzettel\": \"2|0\", \"state\": \"1|0\", \"voter_id\": 848, \"ballotpaper_id\": \"2\", \"canvote\": 1},{\"stimmzettel\": \"3|0\", \"state\": \"1|0\", \"voter_id\": 974, \"ballotpaper_id\": \"3\", \"canvote\": 1},{\"stimmzettel\": \"3|0\", \"state\": \"1|0\", \"voter_id\": 1009, \"ballotpaper_id\": \"3\", \"canvote\": 1},{\"stimmzettel\": \"3|0\", \"state\": \"2|0\", \"voter_id\": 1371, \"ballotpaper_id\": \"3\", \"canvote\": 0},{\"stimmzettel\": \"2|0\", \"state\": \"1|0\", \"voter_id\": 1407, \"ballotpaper_id\": \"2\", \"canvote\": 1},{\"stimmzettel\": \"4|0\", \"state\": \"2|0\", \"voter_id\": 1847, \"ballotpaper_id\": \"4\", \"canvote\": 0}]",
    "id": 0
}';
echo __DIR__;
require_once dirname(__DIR__)."/src/Ballotpaper.php";
require_once dirname(__DIR__)."/src/Voter.php";
$v = Tualo\Office\OnlineVote\Voter::getInstance();
$v->setFromJSON($data);
echo serialize($v);
//print_r(json_decode( $o['possible_ballotpapers'],true));