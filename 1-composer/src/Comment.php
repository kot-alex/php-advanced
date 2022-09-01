<?php

namespace Alex\Weblog;

class Comment
{
    private int $id;
    private int $authorId;
    private int $articleId;
    private string $text;

    function __construct($id, $authorId, $articleId, $text)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->articleId = $articleId;
        $this->text = $text;
    }

    function __toString()
    {
        return $this->text;
    }
}
