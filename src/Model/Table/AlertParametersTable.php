<?php
namespace App\Model\Table;

use App\Model\Entity\AlertParameter;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AlertParameters Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Links
 */
class AlertParametersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('alert_parameters');
        $this->displayField('id');
        $this->displayField('type');
        $this->displayField('life_threshold');
        $this->displayField('subscribe_notifications');
        $this->primaryKey('id');

        $this->belongsTo('Links', [
            'foreignKey' => 'link_id',
            'joinType' => 'INNER'
        ]);
        $this->displayField('link_id');
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
            ->add('life_threshold', 'valid', ['rule' => 'numeric'])
            ->add('life_threshold', 'valid', ['rule' => ['range', 0, 100]]);

         $validator
            ->add('type', 'valid', ['rule' => [ 'inList', ['email']]]);
        $validator
            ->add('sending_status', 'valid', ['rule' => 'boolean'])
            ->add('subscribe_notifications', 'boolean', ['rule' => 'boolean']);
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
        $rules->add($rules->existsIn(['link_id'], 'Links'));
        return $rules;
    }
}
