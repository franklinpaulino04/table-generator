<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tableGenerator_items_model extends CI_Model
{
	private $table 			= 'ai_tables_items';
	private $primary_key 	= 'itemId';

	public function row($itemId)
	{
		return $this->db->query("SELECT * FROM ai_tables_items WHERE hidden = 0 AND itemId = $itemId")->row();
	}

	public function items($tableId)
	{
		return $this->db->query("SELECT * FROM ai_tables_items WHERE hidden = 0 AND tableId = $tableId")->result();
	}

	public function save($array)
	{
		$this->db->insert($this->table, $array);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$result = $this->db->where($where)->update($this->table, $data);
		return $result;
	}
}
