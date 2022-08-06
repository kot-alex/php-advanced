<?php

namespace Alex\Weblog;

class User
{
    private int $id;
    private string $firstName;
    private string $lastName;


    function __construct($id, $firstName, $lastName)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
