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
		$this->load->model('db_query');
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
		if($tableId = $this->tablegenerator_model->save(array('hidden' => 1)))
		{
			redirect(base_url().'tablegenerator/edit/'.$tableId);
		}
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

	public function update($tableId)
	{
		$error = '';

		$this->form_validation->set_rules('table_name', '<strong>Table Name</strong>', 'trim|required');

		if(isset($_POST['field_name']))
		{
			$p = 1;

			foreach ($_POST['field_name'] AS $key => $value)
			{
				$this->form_validation->set_rules('field_name['.$key.']', '<strong>Field Name</strong> in line '.$p, 'trim|required');
				$this->form_validation->set_rules('field_typeId['.$key.']', '<strong>Field Type</strong> in line '.$p, 'is_natural_no_zero|required|trim');
				$this->form_validation->set_rules('field_length['.$key.']', '<strong>Field Length</strong> in line '.$p, 'numeric|required|trim');

				$p++;
			}
		}

		$validation =  $this->form_validation->run();

		if(!isset($_POST['field_key']) || (count($_POST['field_key']) > 1 || count($_POST['field_key']) == 0))
		{
			$error      = '<li><strong>Field Primary Key</strong>.</li>';
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

			if($this->tablegenerator_model->update(array('tableId' => $tableId), $data))
			{
				if(isset($row->table_name) && $row->table_name != $data['table_name'])
				{
					$this->dbforge->rename_table($row->table_name, $data['table_name']);
				}
				else
				{
					$this->dbforge->create_table($data['table_name'], true);
				}

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
							$this->tablegenerator_items_model->save($items);

							if($items['field_key'] == 1)
							{
								$fields = array(
									$items['field_name'] 	=> array(
										'constraint' 		=> $items['field_length'],
										'type' 				=> $this->type($items['field_typeId']),
										'auto_increment' 	=> TRUE,
									),
								);
							}
							else
							{
								$fields = array(
									$items['field_name'] 	=> array(
										'constraint' 		=> $items['field_length'],
										'type' 				=> $this->type($items['field_typeId']),
									),
								);
							}

							$this->dbforge->add_column($data['table_name'], $fields);
						}
						else
						{
							$itemId   = $_POST['itemId'][$key];
							$row_item = $this->tablegenerator_items_model->row($itemId);

							$fields = array(
								$row_item->field_name => array(
									'name' 				=> $items['field_name'],
									'constraint' 		=> $items['field_length'],
									'type' 				=> $this->type($items['field_typeId']),
								),
							);

							$this->dbforge->modify_column($data['table_name'], $fields);

							$this->tablegenerator_items_model->update(array('itemId' => $itemId), $items);
						}
					}
				}

				echo json_encode(array('result' => 1));
				exit();
			}
		}
	}

	public function hide_items($itemId)
	{
		$data       = $this->tablegenerator_model->row($itemId);
		$data_items = $this->tablegenerator_items_model->row($itemId);

		if($this->tablegenerator_items_model->update(array('itemId' => $itemId), array('hidden' => 1)))
		{
			if(isset($data->table_name) && isset($data_items->field_name))
			{
				$this->dbforge->drop_column($data->table_name, $data_items->field_name);
			}

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
				$this->dbforge->drop_table($data->table_name, true);
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
}
