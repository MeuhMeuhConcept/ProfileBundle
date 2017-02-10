<?php

namespace MMC\Profile\Component\Manipulator\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UnableToDeleteActiveUserProfileException extends AccessDeniedHttpException implements ManipulatorExceptionInterface
{
}
