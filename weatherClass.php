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
        $this -> description = trim($description);
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