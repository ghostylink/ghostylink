<?php

/*
 * Interface all link component must implement
 */
namespace App\View\Helper;

use App\Model\Entity\Link;

interface LinkComponentHelper
{
    public function displayField(Link $link);
    public function displayComponent(Link $link);
}
