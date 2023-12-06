<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Create_articles_log_table extends CI_Migration
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
      'userid' => array(
        'type' => 'INT',
        'unsigned' => TRUE,
        'constraint' => 11
      ),
      'articleid' => array(
        'type' => 'INT',
        'unsigned' => TRUE,
        'constraint' => 11
      ),
      'article' => array(
        'type' => 'TEXT',
      ),
    'created_at datetime NOT NULL DEFAULT "0000-00-00 00:00:00"',
    'updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
      'approved' => array(
        'type' => 'INT',
        'constraint' => 2,
        'default' => 0,
      ),
      'status' => array(
        'type' => 'INT',
        'constraint' => 2,
        'default' => 1,
      ),
      'CONSTRAINT blog_admin_id FOREIGN KEY (userid) REFERENCES blog_admin(id) ON DELETE CASCADE ON UPDATE NO ACTION',
      'CONSTRAINT articles_id FOREIGN KEY (articleid) REFERENCES articles(id) ON DELETE CASCADE ON UPDATE NO ACTION'
    );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('articles_log', TRUE);
    }

    
	public function down()
	{
	    $this->dbforge->drop_table('articles_log', TRUE);
    }
}
/* End of file '20180429165825_create_articles_log_table' */
/* Location: .//var/www/html/startz/application/migrations/20180429165825_create_articles_log_table.php */
