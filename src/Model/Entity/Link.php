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
        'user_id' => true,
        'status' => true,
        'google_captcha' => true,
        'life_percentage' => true,
        'time_limit' => true,
        "alert_parameter" => true
    ];

    /**
     * Get the time limit string
     * @return string
     */
    protected function _getTimeLimit()
    {
        if (! $this->death_time) {
            return null;
        }
        $strDifference = $this->death_time->diffForHumans($this->created);

        // It's a time limit component only for 1 day, 1 week and 1 month
        $strDifference = str_replace([" after", " ago", " from now"], "", $strDifference);
        if (in_array($strDifference, ['1 week', '1 month', '1 day'], true)) {
            return $strDifference;
        }

        return null;
    }

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
        if (isset($this->_properties['max_views'])) {
            $percentageViews = (100.0 * $this->views) / $this->_properties['max_views'];
        }
        if ($this->death_time != null) {
            $currentTime = new Time();
            $created = new Time($this->_properties['created']);
            $death = new Time($this->_properties['death_time']);
            $elapseTime = $currentTime->diffInSeconds($created);
            $totalTime = $created->diffInSeconds($death, false);
            if ($totalTime >= 0) {
                $percentageTime = (100 * $elapseTime) / $totalTime;
            } else {
                $percentageTime = 100;
            }
        }
        return min(100, max($percentageViews, $percentageTime));
    }
    
    public function getComponents()
    {
        $componentsList = [];
        if ($this->max_views || $this->errors('max_views')) {
            array_push($componentsList, "ViewsLimit");
        }
        if ($this->google_captcha || $this->errors("google_captcha")) {
            array_push($componentsList, "GoogleCaptcha");
        }
        if ($this->time_limit) {
            array_push($componentsList, "TimeLimit");
        } else if ($this->death_time || $this->errors("death_time")) {
            array_push($componentsList, "DateLimit");
        }
        if ($this->alert_parameter || $this->errors("alert_parameter.life_threshold")) {
            array_push($componentsList, "GhostyficationAlert");
        }
        return $componentsList;
    }
}
