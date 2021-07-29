<?php 

class Board {
    public $id;
    public $title;
    public $background;

    public function __construct($_id, $_title, $_background) {
        $this->id = $_id;
        $this->title = $_title;
        $this->background = $_background;
    }    
}

?>