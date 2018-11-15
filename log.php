<?php
/**
 * @copyright 2018 Alaa Al-Maliki <alaa.almaliki@gmail.com>
 * @license   MIT
 */

declare(strict_types=1);

if (!function_exists('consoleLog')) {
    /**
     * a method made globally available to log to fire php
     *
     * @param string $message
     * @param array $context
     */
    function consoleLog(string $message, array $context = [])
    {
        \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Alaa\Firephp\Logger::class)
            ->debug($message, $context);
    }
}
