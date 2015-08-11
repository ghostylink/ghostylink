<?php
use Phinx\Migration\AbstractMigration;

class AddStatusToLinks extends AbstractMigration
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
        $table = $this->table('links');
        $table->addColumn('status', 'boolean', [
            'default' => true,
            'null' => false,
        ]);
        $table->update();
    }
}
