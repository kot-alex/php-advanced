<?php

namespace Alex\Weblog\http\Actions;

use Alex\Weblog\http\Request;
use Alex\Weblog\http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}
