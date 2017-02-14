<?php

namespace MMC\Profile\Component\Validator\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class InvalidProfileTypeException extends AccessDeniedHttpException implements ValidatorExceptionInterface
{
}
