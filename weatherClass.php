<?php
class weather{
    private $title;
    private $link;
    private $day;
    private $description;
    private $pubDate;

    public function setData($title, $link, $day, $description, $pubDate){
        $this -> title = trim($title);
        $this -> link = trim($link);
        $this -> day = trim($day);
        $this -> description = " 会津若松市の".explode("でしょう。", $description)[0]."です!";
        $this -> pubDate = trim($pubDate);
    }

    public function getAllData(){
        return $this;
    }

    public function getDescription(){
        return $this -> description;
    }

    public function getPubDate(){
        return $this -> pubDate;
    }

}
?>