<?php

namespace Minetro\FlyResponse\Adapter;

use Minetro\FlyResponse\Buffer\FileBuffer;
use Nette\Http\IRequest;
use Nette\Http\IResponse;

class StdoutAdapter implements Adapter
{

    /** @var callable */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param IRequest $request
     * @param IResponse $response
     * @return void
     */
    public function send(IRequest $request, IResponse $response)
    {
        // Open file pointer
        $b = new FileBuffer('php://output', 'w');

        // Fire callback with buffer, request and response
        call_user_func_array($this->callback, [$b, $request, $response]);

        // Close resource
        $b->close();
    }

}
