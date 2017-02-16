<?php

namespace MMC\Profile\Component\Manipulator\Exception;

use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class InvalidProfileClassName extends PreconditionFailedHttpException implements ManipulatorExceptionInterface
{
}
