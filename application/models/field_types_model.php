<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class field_types_model extends CI_Model
{
	private $table 			= 'ai_field_types';
	private $primary_key 	= 'typeId';

	public function data_dropdown()
	{
		$options[''] = 'Select a type';

		$query = $this->db->get($this->table);

		foreach ($query->result() as $row)
		{
			$options[$row->typeId]   = $row->name;
		}

		return $options;
	}
}
