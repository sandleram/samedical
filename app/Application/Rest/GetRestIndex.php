<?php

namespace App\Application\Rest;

use App\Domain\Rest\RestApiResult;

final class GetRestIndex
{
    public function execute(): RestApiResult
    {
        return RestApiResult::failed();
    }
}
