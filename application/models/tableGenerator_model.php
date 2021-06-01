<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tableGenerator_model extends CI_Model
{
	private $table 			= 'ai_tables';
	private $primary_key 	= 'tableId';

	public function datatable()
	{
		$query = $this->db->get($this->table);
		return $query->result();
	}

	public function row($tableId)
	{
		return $this->db->query("SELECT * FROM ai_tables WHERE hidden = 0 AND tableId = $tableId")->row();
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
