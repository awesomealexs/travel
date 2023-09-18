<?php

namespace Ratehawk;
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Ratehawk\Api\Client;


class Base
{
    public const BASE_DIR = __DIR__;

    public const STORAGE_DIR = self::BASE_DIR . '/Storage/';

    protected Client $rateHawkApi;

    protected $configuration;

    protected Logger $logger;


    public function __construct()
    {
        $dotenv = new Dotenv();
        $dotenv->load(dirname(__DIR__).'/.env');
        $logDir = static::BASE_DIR . '/Logs/ApiLog.log';
        $handler = new RotatingFileHandler($logDir);

        $this->logger = new Logger('', [], [], (new \DateTimeZone('+3:00')));
        $this->logger->pushHandler($handler);

        $this->configuration = new Configuration();
        $this->rateHawkApi = new Client(sprintf('%s:%s',
                $this->configuration->getKeyId(),
                $this->configuration->getApiKey(),

            )
        );


    }

//    public function run()
//    {
//        $res = $this->rateHawkApi->getHotelsDumpUrl();
//    }

    public function getHotelsDumpFile(): bool
    {
        try {
            $this->rateHawkApi->getHotelsDump(static::STORAGE_DIR);

            return true;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }

    public function test(){
        $r = $this->rateHawkApi->overview();

        var_dump($r);
    }

    public function handleHotelsDumpFile()
    {
        ini_set('memory_limit', '3G');
        $file = file_get_contents(static::STORAGE_DIR . '14f3ffed719dd13ca902b345ea7382ff.zstd');
        file_put_contents(static::STORAGE_DIR . 'uncompressed.log', zstd_uncompress($file));
    }
}

$b = new Base();
//$b->getHotelsDumpFile();
$b->handleHotelsDumpFile();
//$b->test();