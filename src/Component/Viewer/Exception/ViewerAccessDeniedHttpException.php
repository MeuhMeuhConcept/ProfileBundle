<?php

namespace MMC\Profile\Component\Viewer\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ViewerAccessDeniedHttpException extends AccessDeniedHttpException implements ViewerExceptionInterface
{
}
