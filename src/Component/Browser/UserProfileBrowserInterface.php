<?php

namespace MMC\Profile\Component\Browser;

interface UserProfileBrowserInterface
{
    public function browse(array $options):BrowserResponse;
}
