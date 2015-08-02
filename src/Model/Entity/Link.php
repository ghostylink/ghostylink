<?php
namespace App\Model\Entity;

use Cake\I18n\Time;
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
        'max_views' => true,
        'views' => true,
        'death_time' => true,
        'user_id' => true
    ];
    
    /**
     * Get the remaining available view of a link
     * @return type
     */
    protected function _getRemainingViews()
    {
        if ($this->_properties['max_views'] == null) {
            return null;
        }
        return max($this->_properties['max_views'] - $this->_properties['views'], 0);
    }
    
    /**
     * Get the  life percentage of a link considering maw_views or death_time
     * @return type
     */
    protected function _getLifePercentage()
    {
        $percentageViews = 0;
        $percentageTime = 0;
        // FIXME this computation generate  warning on unit tests
        if ($this->_properties['max_views'] != null) {
            $percentageViews = (100 * $this->_properties['views']) / $this->_properties['max_views'];
        }
        if ($this->_properties['death_time'] != null) {
            $currentTime = new Time();
            $created = new Time($this->_properties['created']);
            $death = new Time($this->_properties['death_time']);
            $elapseTime = $currentTime->diffInSeconds($created);
            $totalTime = $death->diffInSeconds($created);
            if ($totalTime != 0) {
                $percentageTime = (100 * $elapseTime) / $totalTime;
            }
        }
        return min(100, max($percentageViews, $percentageTime));
    }
}
