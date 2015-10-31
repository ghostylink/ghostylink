<?php

/**
 * User table file
 */

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\Query;
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
        $this->displayField('default_threshold');
        $this->addBehavior('User');
        $this->primaryKey('id');
        $this->hasMany('Links', [
            'className' => 'Links',
            'foreignKey' => 'user_id',
            'dependent' => true
        ]);
        //$this->displayField('links');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator->add('id', 'valid', ['rule' => 'numeric'])
                ->allowEmpty('id', 'create');

        $validator->requirePresence('username', 'create')
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
                    ]
                  ]);

        $validator->requirePresence('password', 'create')
                ->notEmpty('password')
                ->add('password', [
                    'minLength' => [
                        'rule' => ['minLength', 4],
                        'message' => 'Password need to be at least 4 characters long',
                    ],
                    'maxLength' => [
                        'rule' => ['maxLength', 20],
                        'message' => 'Password need to be at most 20 characters long'
                    ]
                ]);

        $validator->add('email', 'valid', ['rule' => 'email',
                    'on' => function ($context) {
                        return !empty($context['data']['email']);
                    }])
                ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table',
                    'on' => function ($context) {
                        return !empty($context['data']['email']);
                    }])
                ->allowEmpty('email');

        $validator
                ->add('default_threshold', 'valid', ['rule' => ['range', 1, 100]]);
        return $validator;
    }

    /**
     * Find all users who need a mail alert
     * @param \Cake\ORM\Query query
     * @param options
     */
    public function findNeedMailAlert(Query $query, array $options)
    {
        return $query->find('all')->matching('Links', function ($q) {
                            return $q->find('needMailAlert');
        })->where(function ($exp, $q) {
            return $exp->isNotNull('email');
        })
        ->group('Users.id')
        ->having(['count(*) >' > 0]);
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
