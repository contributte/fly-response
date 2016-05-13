<?php

namespace Minetro\FlyResponse\Adapter;

use Nette\Http\IRequest;
use Nette\Http\IResponse;

interface Adapter
{

    /**
     * @param IRequest $request
     * @param IResponse $response
     * @return void
     */
    public function send(IRequest $request, IResponse $response);

}
