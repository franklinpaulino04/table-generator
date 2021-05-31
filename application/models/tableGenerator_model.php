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

	public function row()
	{
		return $this->db->query("SELECT * FROM ai_tables WHERE hidden = 0")->row();
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
