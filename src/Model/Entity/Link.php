<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Link Entity.
 */
class Link extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'content' => true,
        'token' => true,
        'max_views' => true      
    ];
    
    /**
     * Get the remaining available view of a link
     * @return type
     */
    protected function _getRemainingViews()
    {  
        return max($this->_properties['max_views'] - $this->_properties['views'], 0);
    }
    
    /**
     * Get the  life percentage of a link
     * @return type
     */
    protected function _getLifePercentage()
    {
        return min((100 * $this->_properties['views']) / $this->_properties['max_views'], 100);
    }
}
