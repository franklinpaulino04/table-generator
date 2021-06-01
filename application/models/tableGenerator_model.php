<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tableGenerator_model extends CI_Model
{
	private $table 			= 'ai_tables';
	private $primary_key 	= 'tableId';

	public function datatable()
	{
		$query = $this->db->where(array('hidden' => 0))->get($this->table);
		return $query->result();
	}

	public function row($tableId)
	{
		return $this->db->query("SELECT * FROM ai_tables WHERE tableId = $tableId")->row();
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

	public function count_by($where)
	{
		$result = $this->db->where($where)->from($this->table)->count_all_results();
		return $result;
	}
}
