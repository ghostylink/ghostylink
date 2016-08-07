<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AlertParameter Entity.
 *
 * @property int $id
 * @property int $life_threshold
 * @property string $type
 * @property bool $sending_status
 * @property int $link_id
 * @property \App\Model\Entity\Link $link
 */
class AlertParameter extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'life_threshold' => true,
        'id' => false,
        'subscribe_notifications' => true
    ];
}
