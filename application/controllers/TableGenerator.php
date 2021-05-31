<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TableGenerator extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		//doc
//		https://www.kodingmadesimple.com/2016/09/create-database-and-table-in-codeigniter-if-not-exists.html
//		https://codeigniter.com/userguide3/database/forge.html#adding-keys

		// Load Model
		$this->load->model('tablegenerator_model');
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
			'hidden' => 1,
		);

		if($tableId = $this->tablegenerator_model->save($data))
		{
			redirect(base_url().'tablegenerator/edit/'.$tableId);
		}
	}

	public function edit($tableId)
	{
		$data = array(
			'tableId' 	=> $tableId,
			'row' 		=> $this->tablegenerator_model->row(),
			'items' 	=> $this->tablegenerator_items_model->items(),
			'types' 	=> $this->field_types_model->data_dropdown(),
			'content' 	=> 'table-generator/edit_view',
		);

		$this->load->view('include', $data);
	}

	public function update($tableId)
	{

	}

	public function hide_items($itemId)
	{
		if($this->tablegenerator_items_model->update($itemId, array('hidden' => 1)))
		{
			echo json_encode(array('result' => 1));
		}
	}
}
