<?php
/**
 * @copyright 2018 Alaa Al-Maliki <alaa.almaliki@gmail.com>
 * @license   MIT
 */

declare(strict_types=1);

namespace Alaa\Firephp\Test\Unit;

use Alaa\Firephp\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class LoggerTest
 *
 * @package Alaa\Firephp\Test
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class LoggerTest extends TestCase
{
    /**
     * @var Logger|MockObject
     */
    private $subject;

    /**
     * @var State|MockObject
     */
    private $appState;

    /**
     * @var ScopeConfigInterface|MockObject
     */
    private $scopeConfig;

    /**
     * @var \Monolog\Logger|MockObject
     */
    private $monologLogger;

    protected function setUp()
    {
        parent::setUp();
        $this->appState = $this->getMockBuilder(State::class)
            ->disableOriginalConstructor()
            ->setMethods(['getMode'])
            ->getMock();

        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->monologLogger = $this->createMock(\Monolog\Logger::class);

        $this->subject = $this->getMockBuilder(Logger::class)
            ->setConstructorArgs(
                [
                    'loggerFactory' => new \Monolog\LoggerFactory(),
                    'appState' => $this->appState,
                    'scopeConfig' => $this->scopeConfig
                ]
            )
            ->setMethods(['getConfigFlag', 'getLogger'])
            ->getMock();

        $this->subject->expects($this->any())
            ->method('getLogger')
            ->willReturn($this->monologLogger);
    }

    public function testDebugDisabled()
    {
        $this->subject->expects($this->any())
            ->method('getConfigFlag')
            ->with('enabled')
            ->willReturn(false);

        $this->subject->debug('', []);
    }

    public function testDebugOnDeveloperMode()
    {
        $this->subject->expects($this->any())
            ->method('getConfigFlag')
            ->with('enabled')
            ->willReturn(true);

        $this->appState->expects($this->any())
            ->method('getMode')
            ->willReturn('developer');

        $this->subject->debug('', []);
    }

    public function testDebugOnProductionMode()
    {
        $this->subject->expects($this->at(0))
            ->method('getConfigFlag')
            ->with('enabled')
            ->willReturn(true);

        $this->appState->expects($this->any())
            ->method('getMode')
            ->willReturn('production');

        $this->subject->expects($this->at(1))
            ->method('getConfigFlag')
            ->with('production_enabled')
            ->willReturn(true);

        $this->subject->debug('', []);
    }
}
