<?php

namespace Kraken\Io;

use Kraken\Throwable\Runtime\InstantiationException;
use Kraken\Io\Http\Component\Router\HttpRouter;
use Kraken\Io\Http\Component\Router\HttpRouterInterface;
use Kraken\Io\Http\HttpServer;
use Kraken\Io\Socket\SocketServer;
use Kraken\Io\Socket\SocketServerInterface;
use Kraken\Ipc\Socket\SocketListenerInterface;
use Kraken\Loop\LoopInterface;
use Error;
use Exception;

class IoServer implements IoServerInterface
{
    /**
     * @var SocketServerInterface
     */
    protected $server;

    /**
     * @var HttpRouterInterface
     */
    protected $router;

    /**
     * @var SocketListenerInterface
     */
    protected $listener;

    /**
     * @param SocketListenerInterface $listener
     * @throws InstantiationException
     */
    public function __construct(SocketListenerInterface $listener)
    {
        try
        {
            $server = new SocketServer(
                new HttpServer(
                    $router = new HttpRouter()
                ),
                $listener
            );

            $this->server = $server;
            $this->router = $router;
            $this->listener = $listener;
        }
        catch (Error $ex)
        {
            throw new InstantiationException("[" . __CLASS__ . "] could not be created.", $ex);
        }
        catch (Exception $ex)
        {
            throw new InstantiationException("[" . __CLASS__ . "] could not be created.", $ex);
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->server);
        unset($this->router);
        unset($this->listener);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function addRoute($path, IoServerComponentInterface $component)
    {
        return $this->router->addRoute($path, $component);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function removeRoute($path)
    {
        return $this->router->removeRoute($path);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function close()
    {
        $this->listener->close();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function getLoop()
    {
        return $this->listener->getLoop();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function setLoop(LoopInterface $loop)
    {
        $this->listener->setLoop($loop);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function loop()
    {
        return $this->listener->loop();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function isPaused()
    {
        return $this->listener->isPaused();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function pause()
    {
        return $this->listener->pause();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function resume()
    {
        return $this->listener->resume();
    }
}