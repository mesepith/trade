<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Create_blog_admin_table extends CI_Migration {

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
      'username' => array(
        'type' => 'VARCHAR',
        'constraint' => 60
      ),
      'first_name' => array(
        'type' => 'VARCHAR',
        'constraint' => 60
      ),
      'last_name' => array(
        'type' => 'VARCHAR',
        'constraint' => 60
      ),
      'user_email' => array(
        'type' => 'VARCHAR',
        'constraint' => 255
      ),
      'password' => array(
        'type' => 'VARCHAR',
        'constraint' => 255
      ),
//    'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
//    'updated_at datetime DEFAULT CURRENT_TIMESTAMP',
//    'created_at TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"',
    //'created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
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
        $this->dbforge->create_table('blog_admin', TRUE);
    }

    public function down() {
        $this->dbforge->drop_table('blog_admin', TRUE);
    }

}

/* End of file '20180426171311_create_blog_admin_table' */
/* Location: .//var/www/html/startz/application/migrations/20180426171311_create_blog_admin_table.php */
