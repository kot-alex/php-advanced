<?php

namespace Alex\Weblog\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

final class NotFoundException extends \Exception implements NotFoundExceptionInterface
{
}
