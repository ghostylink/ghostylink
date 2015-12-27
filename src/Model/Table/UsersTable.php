<?php

/**
 * User table file
 */

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use App\Mailer\UserMailer;

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
        $this->displayField('email_validated');
        $this->displayField('email_validation_link');
        $this->displayField('subscribe_notifications');
        $this->addBehavior('User');
        $this->primaryKey('id');
        $this->hasMany('Links', [
            'className' => 'Links',
            'foreignKey' => 'user_id',
            'dependent' => true
        ]);
        $this->eventManager()->on(UserMailer::getInstance());
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
                // Attach the UserStatistic object to the Order's event manager

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

        $validator->requirePresence("confirm_password", function ($context) {
            // Confirm password required on new record but only if password is set for modifications
            if ($context['newRecord']) {
                return true;
            } else {
                $passwordDefined = isset($context['data']['password']) && $context['data']['password'] != "";
                return $passwordDefined;
            }
        })
                        ->add("confirm_password", [
                                            "match" => [
                                                "rule" => ["compareWith", "password"],
                                                "message" => 'The passwords do not match!'
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

        $validator->add("subscribe_notifications", 'boolean', ["rule" => "boolean"]);

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
        ->andWhere(["email_validated" => true])
        ->andWhere(['subscribe_notifications' => true])
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
