<?php
namespace App\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;

class UserBehavior extends Behavior
{
    protected $_defaultConfig = [
        'email' => 'email'       
    ];

    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options)
    {
        if (array_key_exists('email', $data)) {
            if ($data['email'] == '') {
                $data['email'] = NULL;
            }
        }
    }
}
