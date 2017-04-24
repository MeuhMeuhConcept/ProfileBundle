<?php

namespace MMC\Profile\Component\Browser;

interface UserBrowserInterface
{
    public function browse(array $options):BrowserResponse;
}
