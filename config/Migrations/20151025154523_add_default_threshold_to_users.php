<?php
use Migrations\AbstractMigration;

class AddDefaultThresholdToUsers extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('default_threshold', 'integer', [
            'default' => 67,
            'null' => false
        ]);
        $table->update();
    }
}
