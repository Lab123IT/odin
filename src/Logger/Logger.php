<?php
namespace Lab123\Odin\Logger;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Formatter\LineFormatter;

class Logger
{

    /**
     * Instance MonoLogger
     *
     * @var loggers
     */
    protected $logger;

    /**
     * Default log Name (index Mono Log)
     *
     * @var file_name
     */
    protected $log_name = 'custom';

    /**
     * Default file name
     *
     * @var file_name
     */
    protected $file_name = 'custom';

    /**
     * Default format line
     *
     * @var formater
     */
    protected $formater = "[%datetime%] %channel%.%level_name%: %message% %extra% %context%\n";

    /**
     * Constructor Class
     *
     * @return void
     */
    public function __construct()
    {
        $this->logger = new MonoLogger($this->log_name);
    }

    /**
     * Emergency Log
     *
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        if (! $this->canLog()) {
            return;
        }
        
        $this->config();
        
        $this->logger->addEmergency($this->formatData($message), $context);
    }

    /**
     * Alert Log
     *
     * @return void
     */
    public function alert($message, array $context = [])
    {
        if (! $this->canLog()) {
            return;
        }
        
        $this->config();
        
        $this->logger->addAlert($this->formatData($message), $context);
    }

    /**
     * Critical Log
     *
     * @return void
     */
    public function critical($message, array $context = [])
    {
        if (! $this->canLog()) {
            return;
        }
        
        $this->config();
        
        $this->logger->addCritical($this->formatData($message), $context);
    }

    /**
     * Error Log
     *
     * @return void
     */
    public function error($message, array $context = [])
    {
        if (! $this->canLog()) {
            return;
        }
        
        $this->config();
        
        $this->logger->addError($this->formatData($message), $context);
    }

    /**
     * Warning Log
     *
     * @return void
     */
    public function warning($message, array $context = [])
    {
        if (! $this->canLog()) {
            return;
        }
        
        $this->config();
        
        $this->logger->addWarning($this->formatData($message), $context);
    }

    /**
     * Notice Log
     *
     * @return void
     */
    public function notice($message, array $context = [])
    {
        if (! $this->canLog()) {
            return;
        }
        
        $this->config();
        
        $this->logger->addNotice($this->formatData($message), $context);
    }

    /**
     * Info Log
     *
     * @return void
     */
    public function info($message, array $context = [])
    {
        if (! $this->canLog()) {
            return;
        }
        
        $this->config();
        
        $this->logger->addInfo($this->formatData($message), $context);
    }

    /**
     * Debug Log
     *
     * @return void
     */
    public function debug($message, array $context = [])
    {
        if (! $this->canLog()) {
            return;
        }
        
        $this->config();
        
        $this->logger->addDebug($this->formatData($message), $context);
    }

    /**
     * Validate can log data
     *
     * @return boolean
     */
    protected function canLog()
    {
        return true;
    }

    /**
     * Full Location Log
     *
     * @return string
     */
    public function getFullPath()
    {
        return storage_path() . "/logs/{$this->file_name}.log";
    }

    /**
     * Return Line Formater
     *
     * @return \Monolog\Formatter\LineFormatter
     */
    public function getLineFormater()
    {
        return new LineFormatter($this->formater);
    }

    /**
     * Configuration MonoLogger Instance
     *
     * @return void
     */
    private function config()
    {
        $handler = new RotatingFileHandler($this->getFullPath(), 0, MonoLogger::INFO);
        
        $handler->setFormatter($this->getLineFormater());
        
        $this->logger->pushHandler($handler);
        
        $this->logger->pushProcessor(new WebProcessor());
        
        $this->logger->pushProcessor(new MemoryUsageProcessor());
    }

    /**
     * Format data to String
     *
     * @return string
     */
    private function formatData($data)
    {
        if (! is_string($data)) {
            $data = json_encode($data);
        }
        
        return $data;
    }
}