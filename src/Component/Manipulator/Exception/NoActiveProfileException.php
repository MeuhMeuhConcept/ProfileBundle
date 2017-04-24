<?php

namespace MMC\Profile\Component\Manipulator\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NoActiveProfileException extends NotFoundHttpException implements ManipulatorExceptionInterface
{
}
