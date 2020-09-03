<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Queue\Tests\unit;

use InvalidArgumentException;
use Yiisoft\Yii\Queue\Driver\DriverInterface;
use Yiisoft\Yii\Queue\Enum\JobStatus;
use Yiisoft\Yii\Queue\Message\MessageInterface;
use Yiisoft\Yii\Queue\Queue;
use Yiisoft\Yii\Queue\QueueDependentInterface;
use Yiisoft\Yii\Queue\Tests\TestCase;

class QueueDependentInterfaceTest extends TestCase
{
    public function getClasses()
    {
        $dependent = new class() implements QueueDependentInterface, DriverInterface {
            public ?Queue $queue = null;

            public function setQueue(Queue $queue): void
            {
                $this->queue = $queue;
            }
            public function nextMessage(): ?MessageInterface
            {
            }
            public function status(string $id): JobStatus
            {
            }
            public function push(MessageInterface $message): ?string
            {
            }
            public function subscribe(callable $handler): void
            {
            }
            public function canPush(MessageInterface $message): bool
            {
            }
        };
        $independent = new class() implements DriverInterface {
            public ?Queue $queue = null;

            public function setQueue(Queue $queue): void
            {
                $this->queue = $queue;
            }
            public function nextMessage(): ?MessageInterface
            {
            }
            public function status(string $id): JobStatus
            {
            }
            public function push(MessageInterface $message): ?string
            {
            }
            public function subscribe(callable $handler): void
            {
            }
            public function canPush(MessageInterface $message): bool
            {
            }
        };

        return [
            [$dependent, true],
            [$independent, false],
        ];
    }

    /**
     * @dataProvider getClasses
     *
     * @param DriverInterface $driver
     * @param bool $implements
     */
    public function testDependencyResolved(DriverInterface $driver, bool $implements)
    {
        $this->containerConfigurator->set(DriverInterface::class, $driver);
        $this->container->get(Queue::class);

        $this->assertEquals($implements, $driver->queue instanceof Queue);
    }
}
