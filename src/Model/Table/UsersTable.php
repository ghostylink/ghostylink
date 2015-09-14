<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('users');
        $this->displayField('id');
        $this->displayField('email');
        $this->displayField('password');
        $this->displayField('username');
        $this->addBehavior('User');
        $this->primaryKey('id');
        $this->hasMany('Links', [
            'className' => 'Links',
            'foreignKey' => 'user_id',
            'dependent' => true
        ]);
        //$this->displayField('Links');
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
                ->allowEmpty('id', 'create');

        $validator
                ->requirePresence('username', 'create')
                ->notEmpty('username')
                ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table'])
                ->add('username', [
                    'minLength' => [
                        'rule' => ['minLength', 4],
                        'message' => 'Username needs to be at least 4 characters long',
                    ],
                    'maxLength' => [
                        'rule' => ['maxLength', 20],
                        'message' => 'Username needs to be at most 20 characters long'
        ]]);

        $validator
                ->requirePresence('password', 'create')
                ->notEmpty('password')
                ->add('password', [
                    'minLength' => [
                        'rule' => ['minLength', 4],
                        'message' => 'Password need to be at least 4 characters long',
                    ],
                    'maxLength' => [
                        'rule' => ['maxLength', 20],
                        'message' => 'Password need to be at most 20 characters long'
        ]]);

        $validator
                ->add('email', 'valid', ['rule' => 'email',
                    'on' => function ($context) {
                        return !empty($context['data']['email']);
                    }])
                ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table',
                    'on' => function ($context) {
                        return !empty($context['data']['email']);
                    }])
                ->allowEmpty('email');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }

}
