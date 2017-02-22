<?php

namespace MMC\Profile\Component\Manipulator\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UnableToDeleteOwnerUserProfileException extends AccessDeniedHttpException implements ManipulatorExceptionInterface
{
}
