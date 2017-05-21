<?php


namespace AlfredNutileInc\LarScanner;

class ResultDTO
{

    public $title;
    public $body;
    public $library;

    public function __construct($title, $body, $library)
    {
        $this->title = $title;
        $this->body = $body;
        $this->library = $library;
    }
}
