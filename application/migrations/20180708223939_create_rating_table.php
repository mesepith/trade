<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Create_rating_table extends CI_Migration
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
            'rating' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => TRUE,
            ),
            'rating_type' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => TRUE,
            ),
            'rating_by' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => TRUE,
            ),
            'feedback' => array(
				'type' => 'TEXT',
				'null' => TRUE,
			),
            'cookie_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 60,
                'null' => TRUE,
            ),
            'post_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
			'url' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->dbforge->create_table('rating', TRUE);
    }

    public function down() {
        $this->dbforge->drop_table('rating', TRUE);
    }

}
/* End of file '20180708223939_create_rating_table' */
/* Location: .//var/www/html/startz/application/migrations/20180708223939_create_rating_table.php */
