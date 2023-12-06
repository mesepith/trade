<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Create_subscribed_users_table extends CI_Migration {

    public function __construct() {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up() {

        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'cookie_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 60,
                'null' => TRUE,
            ),
            'email_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
            ),
            'contact_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 18,
                'null' => TRUE,
            ),
            'created_at datetime NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'status' => array(
                'type' => 'INT',
                'constraint' => 2,
                'default' => 1,
            )
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('subscribed_users', TRUE);
    }

    public function down() {
        $this->dbforge->drop_table('subscribed_users', TRUE);
    }

}

/* End of file '20180521235111_create_subscribed_users_table' */
/* Location: .//var/www/html/startz/application/migrations/20180521235111_create_subscribed_users_table.php */
