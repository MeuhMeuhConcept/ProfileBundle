<?php

namespace MMC\Profile\Component\Manipulator\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ManipulatorAccessDeniedHttpException extends AccessDeniedHttpException implements ManipulatorExceptionInterface
{
}
