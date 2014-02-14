<?php

//////////////////////////////////////////////////////////////
/*over php version 5.4                                      */
/* API http://weather.livedoor.com/weather_hacks/           */
/* twitter library http://github.com/abraham/twitteroauth   */
//////////////////////////////////////////////////////////////

header("Content-Type: text/html; charset=UTF-8");

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
    $flag = false;
    
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
    $deployStr = "@about_hiroppy ".$pubDate." ".$description;
    $status = $connection->post('statuses/update', array('status' =>  $deployStr));
}
///////////////////////////////////////

setTwitter();
getWeather();

//朝 今日の天気 夜 明日の天気 
/////////////////////////////////////
//check.txt 0 or 1        0 今日 1 明日

$fp = fopen("check.txt", "r");
if(fgets($fp) == "0"){
    tweetWeather(0);
    changeData(1);
}
else{
    tweetWeather(1);
    changeData(0);
}
fclose($fp);

function changeData($n){
    $fp = fopen("check.txt", "w");
    fwrite($fp, $n);
    fclose($fp);
}
/////////////////////////////////////

?>