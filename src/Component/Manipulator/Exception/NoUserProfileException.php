<?php

namespace MMC\Profile\Component\Manipulator\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NoUserProfileException extends NotFoundHttpException implements ManipulatorExceptionInterface
{
}
