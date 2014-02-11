<?php

//////////////////////////////////////////////////////////////
/* API http://weather.livedoor.com/weather_hacks/           */
/* twitter library https://github.com/abraham/twitteroauth  */
//////////////////////////////////////////////////////////////


require_once("twitteroauth/twitteroauth.php");
require_once("weatherClass.php");

//twitter
////////////////////////////////////////
function setTwitter(){
    global $connection;

    $file = file_get_contents("key.txt");
    $key = explode("\n", $file);

    //key
    $consumerKey = $key[0];
    $consumerSecret = $key[1];
    $accessToken = $key[2];
    $accessTokenSecret = $key[3];
    $connection = new TwitterOAuth($consumerKey,$consumerSecret,$accessToken,$accessTokenSecret);    
}

////////////////////////////////////////

//get weather
///////////////////////////////////////
function getWeather(){
    //global array
    global $weatherArray;
    $weatherArray = array();
    $url = "http://weather.livedoor.com/forecast/rss/area/070030.xml"; //Aizu 
    $contents = file_get_contents($url);
    $xml = simplexml_load_string($contents);
    $itemList = $xml -> channel -> item;
    $flag = false; //item[0]がようわからん説明文のため回避
    
    foreach($itemList as $item){
        if(!$flag){
            $flag = true;
            continue;
        }
        $weatherObj = new weather();
        $weatherObj -> setData($item -> title, $item -> link,
                            $item -> day, $item -> description, $item -> pubDate);
        array_push($weatherArray, $weatherObj);
    }
}
///////////////////////////////////////

//tweet weather
///////////////////////////////////////
function tweetWeather($day){
    if($day < 0  || $day > 8) return;
    global $weatherArray;
    global $connection;
    $description = $weatherArray[$day] -> getDescription();
    $pubDate = $weatherArray[$day] -> getPubDate();
    $deployStr = $pubDate." ".$description;
    /* echo $description."\n"; */
    /* $status = $connection->post('statuses/update', array('status' =>  $description)); */
}
///////////////////////////////////////

setTwitter();
getWeather();
tweetWeather(0); //n日後
?>