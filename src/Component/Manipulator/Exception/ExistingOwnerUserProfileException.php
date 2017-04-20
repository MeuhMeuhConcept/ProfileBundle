<?php

namespace MMC\Profile\Component\Manipulator\Exception;

use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class ExistingOwnerUserProfileException extends PreconditionFailedHttpException implements ManipulatorExceptionInterface
{
}
