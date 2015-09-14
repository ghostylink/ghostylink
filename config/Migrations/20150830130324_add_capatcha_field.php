<?php
use Phinx\Migration\AbstractMigration;

class AddCapatchaField extends AbstractMigration
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
        $table->addColumn('google_captcha', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->update();
    }

    /**
     * Improvements on data bases
     */
    public function up()
    {
        $table = $this->table('links');
        $table->addColumn('google_captcha', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->update();
    }

    /**
     * Rollback modifications
     */
    public function down()
    {
        $table = $this->table('links');
        $table->removeColumn('google_catpcha');
        $table->update();
    }
}
