<?php

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * User Entity.
 */
class User extends Entity {

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'username' => true,
        'password' => true,
        'email' => true,
        'links' => true,
        'default_threshold' => true
    ];

    protected function _setPassword($password) {
        return (new DefaultPasswordHasher)->hash($password);
    }

    /**
     * Get the user's link which are nearly ghostified
     */
    public function getLinksAlmostGhostified()
    {
        $linksTable = TableRegistry::get('Links');
        return $linksTable->find("rangeLife", ["min_life" => 66, "max_life" => 100])
                                    ->where(['Links.user_id' => $this->id])->all();

        //debug($this->links->find("all"));
    }
}
