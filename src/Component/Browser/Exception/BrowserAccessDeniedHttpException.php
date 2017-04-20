<?php

namespace MMC\Profile\Component\Browser\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BrowserAccessDeniedHttpException extends AccessDeniedHttpException implements BrowserExceptionInterface
{
}
