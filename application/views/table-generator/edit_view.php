<div class="container py-4">
	<header class="pb-3 mb-4 border-bottom">
		<div class="row">
			<div class="col-md-6">
				<a href="javacript:void(0);" class="d-flex align-items-center text-dark text-decoration-none"><span class="fs-4">Table Generator edit</span></a>
			</div>
			<div class="col-md-6" style="text-align: right">
				<a href="<?php echo base_url('tablegenerator');?>" class="btn btn-danger">exit</a>
			</div>
		</div>
	</header>

	<div class="p-2 mb-4 bg-light rounded-3">

		<form role="form" id="form" name="form" method="POST" action="<?php echo base_url('tablegenerator/update/'.$tableId);?>" onsubmit="return false;">

			<div class="mb-3">
				<label for="exampleFormControlInput1" class="form-label">Table Name</label>
				<input type="text" class="form-control col-md-5" id="table-name" name="table_name" placeholder="Table Name">
			</div>

			<br><br>
			<hr>
			<table id="mytable" class="table">
				<thead>
					<tr>
						<th>Field Primary Key</th>
						<th>Field Name</th>
						<th>Field Type</th>
						<th>Field Length</th>
						<th>Field Decimal</th>
						<th>Field Not Null</th>
						<th></th>
					</tr>
				</thead>
				<?php foreach ($items as $item_row):?>
					<tbody>
						<tr>
							<td>
								<?php echo $field_key 		= ($item_row->field_key == 1)? 'checked' : '';?>
								<?php echo $field_not_null  = ($item_row->field_not_null == 1)? 'checked' : '';?>

								<input type="hidden" name="itemId[]" class="" value="<?php echo $item_row->itemId;?>">
								<input type="checkbox" name="field_key[]" <?php echo $field_key; ?> value="1" class="">
							</td>
							<td><input type="text" name="field_name[]" class="" value="<?php echo $item_row->field_name;?>"></td>
							<td><?php echo form_dropdown('typeId[]', $types, $item_row->field_type, "id='typeId' class='typeId' data-placeholder='Select a type'");?></td>
							<td><input type="text" name="field_length[]" class="" value="<?php echo $item_row->field_length;?>"></td>
							<td><input type="text" name="field_decimal[]" class="" value="<?php echo $item_row->field_decimal;?>"></td>
							<td><input type="checkbox" name="field_not_null[]" <?php echo $field_not_null; ?> class="" value="1"></td>
							<td><a href="javascript:void(0)" data-itemId="<?php echo $item_row->itemId;?>" class="btn btn-danger remove_row">x</a></td>
						</tr>
					</tbody>
				<?php endforeach;?>
				<tfoot>
				<tr>
					<td colspan="7">
						<a href="javascript:void(0);" id="add-row" class="btn btn-secondary"><i class="ti-plus"></i> + add</a>
					</td>
				</tr>
				</tfoot>
			</table>

			<div class="row">
				<div class="col-md-6"></div>
				<div class="col-md-6" style="text-align: right"><button type="submit" id="submit" class="btn btn-primary">Submit</button></div>
			</div>
		</form>
	</div>
</div>

<table class="hidden">
	<tbody id="row-table-hidden">
		<tr class="row-item inline">
			<td>
				<input type="hidden" name="itemId[]" class="" value="0">
				<input type="checkbox" name="field_key[]" value="1" class="">
			</td>
			<td><input type="text" name="field_name[]" class=""></td>
			<td><?php echo form_dropdown('typeId[]', $types, 0, "id='typeId' class=' typeId' data-placeholder='Select a type'");?></td>
			<td><input type="text" name="field_length[]" class=""></td>
			<td><input type="text" name="field_decimal[]" class=""></td>
			<td><input type="checkbox" name="field_not_null[]" value="1" class=""></td>
			<td><a href="javascript:void(0)" data-itemId="0" class="btn btn-danger remove_row">x</a></td>
		</tr>
	</tbody>
</table>

