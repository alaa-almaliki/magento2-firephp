<?php
/**
 * @copyright 2018 Alaa Al-Maliki <alaa.almaliki@gmail.com>
 * @license   MIT
 */

declare(strict_types=1);

namespace Alaa\Firephp;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Monolog\Handler\FirePHPHandler;
use Monolog\LoggerFactory;

/**
 * Class Logger
 *
 * @package Alaa\Firephp\Logger
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Logger
{
    /**
     * @var \Monolog\Logger
     */
    protected $logger;

    /**
     * @var State
     */
    protected $appState;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Logger constructor.
     *
     * @param LoggerFactory $loggerFactory
     * @param State $appState
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(LoggerFactory $loggerFactory, State $appState, ScopeConfigInterface $scopeConfig)
    {
        $this->logger = $loggerFactory->create(
            ['name' => 'FIREPHP', 'handlers' => [new FirePHPHandler()]]
        );
        $this->appState = $appState;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return \Monolog\Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function debug(string $message, array $context = [])
    {
        if (!$this->getConfigFlag('enabled')) {
            return;
        }

        $canLog = true;
        if ($this->appState->getMode() !== 'developer') {
            $canLog &= $this->getConfigFlag('production_enabled');
        }

        if ($canLog) {
            $this->getLogger()->debug($message, $context);
        }
    }

    /**
     * @param string $title
     * @return bool
     */
    protected function getConfigFlag(string $title): bool
    {
        return $this->scopeConfig->isSetFlag('firephp/settings/' . $title);
    }
}
