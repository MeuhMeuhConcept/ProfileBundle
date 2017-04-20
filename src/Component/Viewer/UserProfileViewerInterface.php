<?php

namespace MMC\Profile\Component\Viewer;

use MMC\Profile\Component\Model\UserProfileInterface;

interface UserProfileViewerInterface
{
    public function show(UserProfileInterface $up);
}
