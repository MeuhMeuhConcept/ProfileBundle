<?php

namespace MMC\Profile\Component\Manipulator\Exception;

use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class ExistingUserProfileException extends PreconditionFailedHttpException implements ManipulatorExceptionInterface
{
}
