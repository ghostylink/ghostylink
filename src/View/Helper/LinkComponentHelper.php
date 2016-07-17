<?php

/*
 * Interface all link component must implement
 */
namespace App\View\Helper;

use App\Model\Entity\Link;

interface LinkComponentHelper
{
    public function field(Link $link);
    public function component(Link $link);
}
