<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Add_title_thumbnail_in_articles_table extends CI_Migration
{
    public function __construct()
	{
	    parent::__construct();
		$this->load->dbforge();
	}
	
	public function up()
	{
	            
        $fields = array(
			'title' => array(
			'type' => 'VARCHAR',
			'constraint' => 100,
			'after' => 'userid',
			'null' => FALSE,
		  ),
			'thumbnail' => array(
			'type' => 'VARCHAR',
			'constraint' => 100,
			'after' => 'title'
			)
		); 
		$this->dbforge->add_column('articles', $fields); 
        
    }
    
	public function down()
	{
	    $this->dbforge->drop_column('articles', 'title');
	    $this->dbforge->drop_column('articles', 'thumbnail');
    }
}
/* End of file '20180429203648_add_title_thumbnail_in_articles_table' */
/* Location: .//var/www/html/startz/application/migrations/20180429203648_add_title_thumbnail_in_articles_table.php */
