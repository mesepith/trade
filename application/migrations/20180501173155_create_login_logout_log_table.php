<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Create_login_logout_log_table extends CI_Migration
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
      'auth_user_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'default' => 0,
      ),
      'credential_data' => array(
        'type' => 'TEXT',
        'null' => TRUE,
      ),
      'browser_data' => array(
        'type' => 'TEXT',
        'null' => TRUE,
      ),
      'connection_info' => array(
        'type' => 'TEXT',
        'null' => TRUE,
      ),
      'login_or_logout' => array(
        'type' => 'VARCHAR',
        'constraint' => 20
      ),
      'cookie_id' => array(
        'type' => 'VARCHAR',
        'constraint' =>60
      ),
      'ci_session' => array(
        'type' => 'VARCHAR',
        'constraint' =>60
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
        $this->dbforge->create_table('admin_login_logout_log', TRUE);
    }

    
	public function down()
	{
	    $this->dbforge->drop_table('admin_login_logout_log', TRUE);
    }
}
/* End of file '20180501173155_create_login_logout_log_table' */
/* Location: .//var/www/html/startz/application/migrations/20180501173155_create_login_logout_log_table.php */
