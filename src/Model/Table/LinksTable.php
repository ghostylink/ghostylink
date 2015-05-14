<?php
namespace App\Model\Table;

use App\Model\Entity\Link;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Links Model
 */
class LinksTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('links');
        $this->displayField('title');
        $this->displayField('max_views');
        $this->displayField('death_time');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Tokenable');
        $this->addBehavior('Ghostable');
        // Example for association with User in the future
        /*$this->hasOne('Users', [
            'foreignKey' => 'link_id'
        ]);*/
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')
            ->requirePresence('title', 'create')
            ->notEmpty('title')
            ->requirePresence('content', 'create')
            ->notEmpty('content')
            ->requirePresence('token', 'create')
            ->notEmpty('token');
        
        $validator->allowEmpty('death_time', function ($context) {
            if(array_key_exists('max_views', $context['data'])) {
                return !($context['data']['max_views'] == '');
            }
            else {
                return false;
            }
        });
        $validator->allowEmpty('max_views', function ($context) {
            if(array_key_exists('death_time', $context['data'])) {
                return !($context['data']['death_time'] == '');
            }
            else {
                return false;
            }
        });
        return $validator;
    }
    
    /**      
     * increase the number of views
     *
     * @param Link $entity the Link entity to increase the view on
     * @return boolean true if the link has been deleted
     */
    public function increaseViews(Link $entity)
    {
        $ghost = $this->behaviors()->get('Ghostable');
        if (!$ghost->increaseViews($entity)) {
            $this->delete($entity);
            return false;
        }
        return true;
    }
}
