<?php

namespace App\Page\Component;

class AbstractComponent
{
    public function outputGet(callable $function)
    {
        ob_start();
        $function();
        return ob_get_clean();
    }
}