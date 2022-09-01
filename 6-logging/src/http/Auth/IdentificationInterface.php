<?php

namespace Alex\Weblog\http\Auth;

use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\http\Request;

interface IdentificationInterface
{
    public function user(Request $request): User;
}
