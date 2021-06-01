<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class field_query_model extends CI_Model
{
	public function execute_query($sql)
	{
		$query = $this->db->query($sql);

		return $query;
	}
}
