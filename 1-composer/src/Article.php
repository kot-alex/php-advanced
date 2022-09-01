<?php

namespace Alex\Weblog;

class Article
{
    private int $id;
    private int $authorId;
    private string $title;
    private string $text;

    function __construct($id, $authorId, $title, $text)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->title = $title;
        $this->text = $text;
    }

    function __toString()
    {
        return $this->title . ' >>> ' . $this->text;
    }
}
