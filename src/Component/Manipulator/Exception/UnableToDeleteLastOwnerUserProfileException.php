<?php

namespace MMC\Profile\Component\Manipulator\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UnableToDeleteLastOwnerUserProfileException extends AccessDeniedHttpException implements ManipulatorExceptionInterface
{
}
