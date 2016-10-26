<?php

/*
 * Interface all link component must implement
 */
namespace App\View\Helper;

use App\Model\Entity\Link;

interface LinkComponentHelper
{
    /**
     * Return the html correspoding to the component fields
     * @param Link $link the link in creation
     * @param array $user logged in user
     */
    public function field(Link $link = null, array $user = null);

    /**
     * Display the component badge of the link
     * @param Link $link the link in creation
     * @param array $user the logged in user
     * @param type $content content to write in component description.
     */
    public function component(Link $link, array $user = null, $content = null);

    /**
     * Determine if the component is available in the current context
     * @param array $user the current logged user
     * @return boolean true if it is allowed
     */
    public function isAllowed(array $user = null);

    /**
     * Retrieve the value of the component
     * @param Link $link the link in creation
     * @return string the link component value
     */
    public function getValue(Link $link);
}
