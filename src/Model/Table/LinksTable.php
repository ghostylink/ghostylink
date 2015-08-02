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
        $this->displayField('views');
        $this->displayField('death_time');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Tokenable');
        $this->addBehavior('Ghostable');
        // Example for association with User in the future
        $this->hasOne('Users', [
            'foreignKey' => 'user_id'
        ]);
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
            ->add('title', ['length' => [
                            'rule' => ['maxLength', 100],
                            'message' => 'Titles need to be at least 10 characters long',
            ]])
            ->requirePresence('content', 'create')
            ->notEmpty('content')
            ->requirePresence('token', 'create')
            ->notEmpty('token')
            ->add('max_views', 'valid', ['rule' => 'numeric'])
            ->add('max_views', 'valid', ['rule' => ['range', 0, 1000]]);
        $validator->notEmpty('death_time', 'At least one component is required', function ($context) {
            if (!$context['newRecord']) {
                return false;
            }
            if (array_key_exists('max_views', $context['data'])) {
                return ($context['data']['max_views'] == '');
            } else {
                return false;
            }
        });
        $validator->notEmpty('max_views', 'At least one component is required', function ($context) {
            if (!$context['newRecord']) {
                return false;
            }
            if (array_key_exists('death_time', $context['data'])) {
                return ($context['data']['death_time'] == '');
            } else {
                return false;
            }
        });
        return $validator;
    }
    
    /**
     * increase the life of the link
     *
     * @param Link $entity the Link entity to increase the view on
     * @return boolean False if the link has been deleted
     */
    public function increaseLife(Link $entity)
    {
        $ghost = $this->behaviors()->get('Ghostable');
        if (!$ghost->increaseLife($entity)) {
            $this->delete($entity);
            $this->save($entity);            
            return false;
        }
        $this->save($entity);
        return true;
    }
    
    /**
     * check the life of the link
     * 
     * @param Link $entity the Link entity to increase the view on
     * @return boolean False if the link is dead
     */
    public function checkLife(Link $entity)
    {
        return $this->behaviors()->get('Ghostable')->checkLife($entity);
    }
}
