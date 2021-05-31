<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tableGenerator_items_model extends CI_Model
{
	private $table 			= 'ai_tables_items';
	private $primary_key 	= 'itemId';

	public function items()
	{
		return $this->db->query("SELECT * FROM ai_tables_items WHERE hidden = 0")->result_array();
	}

	public function save($array)
	{
		$this->db->insert($this->table, $array);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		$result = $this->db->where(array($this->primary_key => $id))->update($this->table, $data);
		return $result;
	}
}
