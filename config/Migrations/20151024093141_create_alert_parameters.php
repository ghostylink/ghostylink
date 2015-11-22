<?php
use Migrations\AbstractMigration;

class CreateAlertParameters extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('alert_parameters');
        $table->addColumn('life_threshold', 'integer', [
            'default' => 67,
            'null' => false
        ]);
        $table->addColumn('type', 'string',[
            'limit' => 10,
            'null' => false,
            'default' => 'email'
        ]);
        $table->addColumn('sending_status', 'boolean', [
            'default' => false,
            'null' => false
        ]);
        $table->addColumn('link_id', 'integer', [
            'null' => false
        ]);
        $table->addForeignKey('link_id', 'links', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));
        $table->addIndex('link_id', array('unique' => true));
        $table->create();
    }
}
