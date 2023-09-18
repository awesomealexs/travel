<?php

namespace Ratehawk;

use Symfony\Component\Dotenv\Dotenv;

class Configuration
{
    public function getKeyId(): string
    {
        return $_ENV['RATEHAWK_KEY_ID'];
    }

    public function getApiKey(): string
    {
        return $_ENV['RATEHAWK_API_KEY'];
    }
}

