<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TableGenerator extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->form_validation->set_error_delimiters('<li>', '</li>');

		// Load Model
		$this->load->model('tablegenerator_model');
		$this->load->model('field_query_model');
		$this->load->model('tablegenerator_items_model');
		$this->load->model('field_types_model');
	}

	public function index()
	{
		$data['content']  = 'table-generator/index_view';
		$this->load->view('include', $data);
	}

	public function datatables()
	{
		$data = $this->tablegenerator_model->datatable();

		echo json_encode(array('data' => $data));
	}

	public function add()
	{
		$data = array(
			'content' 	=> 'table-generator/add_view',
			'types' 	=> $this->field_types_model->data_dropdown(),
		);

		$this->load->view('include', $data);
	}

	public function edit($tableId)
	{
		$data = array(
			'tableId' 	=> $tableId,
			'row' 		=> $this->tablegenerator_model->row($tableId),
			'items' 	=> $this->tablegenerator_items_model->items($tableId),
			'types' 	=> $this->field_types_model->data_dropdown(),
			'content' 	=> 'table-generator/edit_view',
		);

		$this->load->view('include', $data);
	}

	public function insert()
	{
		$error 		= '';
		$table 		= '';
		$table_name = $this->input->post('table_name');

		$this->form_validation->set_rules('table_name', '<strong>Table Name</strong>', 'trim|required');

		if(isset($_POST['field_name']))
		{
			$p = 1;

			foreach ($_POST['field_name'] AS $key => $value)
			{
				$field_typeId  = $_POST['field_typeId'][$key];
				$field_length  = $_POST['field_length'][$key];
				$field_default = $_POST['field_default'][$key];

				$this->form_validation->set_rules('field_name['.$key.']', '<strong>Field Name</strong> in line '.$p, 'trim|required');
				$this->form_validation->set_rules('field_typeId['.$key.']', '<strong>Field Type</strong> in line '.$p, 'is_natural_no_zero|required|trim');
				$this->form_validation->set_rules('field_length['.$key.']', '<strong>Field Length</strong> in line '.$p, 'numeric|required|trim');

				if($this->get_type($field_typeId, $field_length) == FALSE)
				{
					$error      = '<li><strong>Field Length is not valid</strong>.</li>';
					$validation = FALSE;
				}

				if($this->get_type($field_typeId, $field_default) == FALSE)
				{
					$error      = '<li><strong>Field Default is not valid</strong>.</li>';
					$validation = FALSE;
				}

				$p++;
			}
		}

		$validation = $this->form_validation->run();

		if($this->array_is_unique($_POST['field_name']) == false)
		{
			$error      = '<li><strong>Field Name is duplicate not valid</strong>.</li>';
			$validation = FALSE;
		}

		if(!isset($_POST['field_key']) || (count($_POST['field_key']) > 1 || count($_POST['field_key']) == 0))
		{
			$error      = '<li><strong>Select One Field Primary Key</strong>.</li>';
			$validation = FALSE;
		}

		if($this->get_by_table($table_name,0) == FALSE)
		{
			$error      = '<li><strong>Table Name Exist</strong>.</li>';
			$validation = FALSE;
		}

		if($validation == FALSE)
		{
			$result = array('result' => 0, 'error' => display_error(validation_errors().$error));
			echo json_encode($result);
			exit();
		}
		else
		{
			$data = array(
				'table_name' => $this->clear_text($this->input->post('table_name')),
				'hidden' 	 => 0,
			);

			if($tableId = $this->tablegenerator_model->save($data))
			{
				$table.="CREATE TABLE ".$data['table_name']."(";

				if(isset($_POST['field_name']))
				{
					$num_columns = count($_POST['field_name']);

					foreach ($_POST['field_name'] AS $key => $value)
					{
						$items = array(
							'tableId' 	 		=> $tableId,
							'field_name' 		=> $this->clear_text($this->input->post('field_name')[$key]),
							'field_typeId'  	=> $this->input->post('field_typeId')[$key],
							'field_length'  	=> $this->input->post('field_length')[$key],
							'field_key'  		=> (isset($_POST['field_key'][$key]))? $this->input->post('field_key')[$key] : 0,
							'field_default' 	=> $this->input->post('field_default')[$key],
						);

						if($items['field_key'] == 1)
						{
							$table.= $items['field_name']." ".$this->type($items['field_typeId'])."(".$items['field_length'].") AUTO_INCREMENT PRIMARY KEY";
						}
						else
						{
							$default = (empty($items['field_default']))? 'NULL' : "'".$items['field_default']."'";
							$table.= $items['field_name']." ".$this->type($items['field_typeId'])."(".$items['field_length'].") DEFAULT $default ";
						}

						if(($key+1) != $num_columns)
						{
							$table.=",";
						}

						$this->tablegenerator_items_model->save($items);
					}

					$table.=");";
				}

				if($this->field_query_model->execute_query($table))
				{
					echo json_encode(array('result' => 1));
					exit();
				}
			}
		}
	}

	public function update($tableId)
	{
		$error 		= '';
		$table 		= '';
		$table_name = $this->input->post('table_name');

		$this->form_validation->set_rules('table_name', '<strong>Table Name</strong>', 'trim|required');

		if(isset($_POST['field_name']))
		{
			$p = 1;

			foreach ($_POST['field_name'] AS $key => $value)
			{
				$field_typeId 	= $_POST['field_typeId'][$key];
				$field_length 	= $_POST['field_length'][$key];
				$field_default 	= $_POST['field_default'][$key];

				$this->form_validation->set_rules('field_name['.$key.']', '<strong>Field Name</strong> in line '.$p, 'trim|required');
				$this->form_validation->set_rules('field_typeId['.$key.']', '<strong>Field Type</strong> in line '.$p, 'is_natural_no_zero|required|trim');
				$this->form_validation->set_rules('field_length['.$key.']', '<strong>Field Length</strong> in line '.$p, 'numeric|required|trim');

				if($this->get_type($field_typeId, $field_length) == FALSE)
				{
					$error      = '<li><strong>Field Length is not valid</strong>.</li>';
					$validation = FALSE;
				}

				if($this->get_type($field_typeId, $field_default) == FALSE)
				{
					$error      = '<li><strong>Field Default is not valid</strong>.</li>';
					$validation = FALSE;
				}

				$p++;
			}
		}

		$validation =  $this->form_validation->run();

		if($this->array_is_unique($_POST['field_name']) == false)
		{
			$error      = '<li><strong>Field Name is duplicate not valid</strong>.</li>';
			$validation = FALSE;
		}

		if(!isset($_POST['field_key']) || (count($_POST['field_key']) > 1 || count($_POST['field_key']) == 0))
		{
			$error      = '<li><strong>Select One Field Primary Key</strong>.</li>';
			$validation = FALSE;
		}

		if($this->get_by_table($table_name, $tableId) == FALSE)
		{
			$error      = '<li><strong>Table Name Exist</strong>.</li>';
			$validation = FALSE;
		}

		if($validation == FALSE)
		{
			$result = array('result' => 0, 'error' => display_error(validation_errors().$error));
			echo json_encode($result);
			exit();
		}
		else
		{
			$row = $this->tablegenerator_model->row($tableId);

			$data = array(
				'table_name' => $this->clear_text($this->input->post('table_name')),
				'hidden' 	 => 0,
			);

			if($this->field_query_model->execute_query("ALTER TABLE ".$row->table_name." RENAME TO ".$data['table_name']))
			{
				if($this->tablegenerator_model->update(array('tableId' => $tableId), $data))
				{
					if(isset($_POST['field_name']))
					{
						foreach ($_POST['field_name'] AS $key => $value)
						{
							$items = array(
								'tableId' 	 		=> $tableId,
								'field_name' 		=> $this->clear_text($this->input->post('field_name')[$key]),
								'field_typeId'  	=> $this->input->post('field_typeId')[$key],
								'field_length'  	=> $this->input->post('field_length')[$key],
								'field_key'  		=> (isset($_POST['field_key'][$key]))? $this->input->post('field_key')[$key] : 0,
								'field_default' 	=> $this->input->post('field_default')[$key],
							);

							if($_POST['itemId'][$key] == 0)
							{
								if($items['field_key'] == 1)
								{
									$alter_table 		= "ALTER TABLE ".$data['table_name']." DROP PRIMARY KEY, ADD PRIMARY KEY (  ".$items['field_name']." )";
									$this->field_query_model->execute_query($alter_table);
								}
								else
								{
									$default 			= (empty($items['field_default']))? 'NULL' : $items['field_default'];
									$table_add_columns  = "ALTER TABLE ".$data['table_name']." ADD ".$items['field_name']." ".$this->type($items['field_typeId'])."(".$items['field_length'].") DEFAULT ".$default." ";

									$this->field_query_model->execute_query($table_add_columns);
								}

								$this->tablegenerator_items_model->save($items);
							}
							else
							{
								$itemId   				= $_POST['itemId'][$key];
								$row_item 				= $this->tablegenerator_items_model->row($itemId);

								if($items['field_key'] == 1)
								{
									if($row_item->field_name != $items['field_name'])
									{
										$drop_primary 	= "ALTER TABLE ".$data['table_name']." DROP PRIMARY KEY, ADD PRIMARY KEY (  ".$row_item->field_name." )";
										$this->field_query_model->execute_query($drop_primary);

										$default 		= (empty($items['field_default']))? 'NULL' : $items['field_default'];
										$add_columns 	= "ALTER TABLE ".$data['table_name']." CHANGE $row_item->field_name ".$items['field_name']." ".$this->type($items['field_typeId'])."(".$items['field_length'].") AUTO_INCREMENT DEFAULT $default ";

										$this->field_query_model->execute_query($add_columns);
									}
									else
									{
										$drop_primary 	= "ALTER TABLE ".$data['table_name']." DROP PRIMARY KEY, ADD PRIMARY KEY (  ".$items['field_name']." )";
										$this->field_query_model->execute_query($drop_primary);
									}
								}
								else
								{
									$default 	 		= (empty($items['field_default']))? 'NULL' : "'".$items['field_default']."'";
									$add_columns 		= "ALTER TABLE ".$data['table_name']." CHANGE $row_item->field_name ".$items['field_name']." ".$this->type($items['field_typeId'])."(".$items['field_length'].") DEFAULT $default ";

									$this->field_query_model->execute_query($add_columns);
								}

								$this->tablegenerator_items_model->update(array('itemId' => $itemId), $items);
							}
						}
					}
				}

				echo json_encode(array('result' => 1));
				exit();
			}
		}
	}

	public function hide_items($itemId, $tableId)
	{
		$data       = $this->tablegenerator_model->row($tableId);
		$data_items = $this->tablegenerator_items_model->row($itemId);

		if($this->tablegenerator_items_model->update(array('itemId' => $itemId), array('hidden' => 1)))
		{
			$this->field_query_model->execute_query("ALTER TABLE $data->table_name DROP COLUMN $data_items->field_name ");

			echo json_encode(array('result' => 1));
		}
	}

	public function delete($tableId)
	{
		$data = $this->tablegenerator_model->row($tableId);

		if($this->tablegenerator_model->update(array('tableId' => $tableId), array('hidden' => 1)))
		{
			if(isset($data->table_name))
			{
				$this->field_query_model->execute_query("DROP TABLE ".$data->table_name);
			}

			if($this->tablegenerator_items_model->update(array('tableId' => $tableId), array('hidden' => 1)))
			{
				echo json_encode(array('result' => 1));
			}
		}
	}

	private function clear_text($texto)
	{
		$text = preg_replace('([^A-Za-z0-9])', '_', $texto);

		return $text;
	}

	private function type($typeId)
	{
		$type = array(1 => 'VARCHAR', 2 => 'INT', 3 => 'DECIMAL', 4 => 'TEXT');
		return $type[$typeId];
	}

	private function get_by_table($name, $tableId)
	{
		$where = array(
			'table_name' => $name,
			'tableId !=' => ($tableId != FALSE)? $tableId : 0,
			'hidden' 	 => 0,
		);

		return ($this->tablegenerator_model->count_by($where) > 0)? FALSE : TRUE;
	}

	private function get_type($typeId, $value)
	{
		$valid = true;

		switch ($typeId)
		{
			case 1:
				$valid = is_string($value);
				break;
			case 2:
				$valid = is_numeric($value);
				break;
			case 3:
				$valid = (bool)preg_match('/^[+\-]?\d+(\.\d+)?$/', $value);
				break;
			case 4:
				$valid = is_string($value);
				break;
		}

		return $valid;
	}

	private function array_is_unique($array) {
		return array_unique($array) == $array;
	}
}
