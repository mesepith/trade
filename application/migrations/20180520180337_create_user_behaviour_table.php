<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Create_user_behaviour_table extends CI_Migration
{
    public function __construct()
	{
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
      'author_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'default' => 0,
      ),
      'user_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'default' => 0,
      ),
      'data' => array(
        'type' => 'TEXT',
        'null' => TRUE,
      ),
      'cookie_id' => array(
        'type' => 'VARCHAR',
        'constraint' =>60,
        'null' => TRUE,
      ),
      'ip' => array(
        'type' => 'VARCHAR',
        'constraint' =>60,
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
        $this->dbforge->create_table('user_behaviour', TRUE);
    }
    
	public function down()
	{
	    $this->dbforge->drop_table('user_behaviour', TRUE);
    }
}
/* End of file '20180520180337_create_user_behaviour_table' */
/* Location: .//var/www/html/startz/application/migrations/20180520180337_create_user_behaviour_table.php */
