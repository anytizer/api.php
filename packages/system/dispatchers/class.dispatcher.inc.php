<?php

namespace system\dispatchers;

interface dispatcher
{

    public function dispatch(string $event = "something.happened", string $message = "", array $data = array()): bool;
}
