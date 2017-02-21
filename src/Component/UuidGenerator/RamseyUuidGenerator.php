<?php

namespace MMC\Profile\Component\UuidGenerator;

use Ramsey\Uuid\Uuid;

class RamseyUuidGenerator implements UuidGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        return Uuid::uuid4();
    }
}
