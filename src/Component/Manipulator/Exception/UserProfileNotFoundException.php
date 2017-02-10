<?php

namespace MMC\Profile\Component\Manipulator\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserProfileNotFoundException extends NotFoundHttpException implements ManipulatorExceptionInterface
{
}
