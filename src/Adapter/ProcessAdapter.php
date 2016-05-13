<?php

namespace Minetro\FlyResponse\Adapter;

use Minetro\FlyResponse\Buffer\ProcessBuffer;
use Nette\Http\IRequest;
use Nette\Http\IResponse;

class ProcessAdapter implements Adapter
{

    /** @var string */
    private $command;

    /** @var string */
    private $mode;

    /** @var int */
    private $buffersize;

    /**
     * @param string $command
     * @param string $mode
     * @param int $buffersize
     */
    public function __construct($command, $mode = 'r', $buffersize = 8192)
    {
        $this->command = $command;
        $this->mode = $mode;
        $this->buffersize = $buffersize;
    }

    /**
     * @param IRequest $request
     * @param IResponse $response
     * @return void
     */
    public function send(IRequest $request, IResponse $response)
    {
        // Open file buffer
        $b = new ProcessBuffer($this->command, $this->mode);

        while (!$b->eof()) {
            // Read from buffer
            $output = $b->read($this->buffersize);

            // Goes to ouput
            echo $output;
        }

        // Close file buffer
        $b->close();
    }

}
