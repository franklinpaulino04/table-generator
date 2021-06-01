<div class="container py-4">
	<header class="pb-3 mb-4 border-bottom">
		<div class="row">
			<div class="col-md-6">
				<a href="javascript:void(0);" class="d-flex align-items-center text-dark text-decoration-none"><span class="fs-4">Table Generator add</span></a>
			</div>
			<div class="col-md-6" style="text-align: right">
				<a href="<?php echo base_url('tablegenerator');?>" class="btn btn-danger">exit</a>
			</div>
		</div>
	</header>

	<div class="p-2 mb-4 bg-light rounded-3">

		<form role="form" id="form" name="form" method="POST" action="<?php echo base_url('tablegenerator/insert');?>" onsubmit="return false;">
			<div class="mb-3">
				<div class="response"></div>
			</div>

			<div class="mb-3">
				<label for="exampleFormControlInput1" class="form-label">Table Name</label>
				<input type="text" class="form-control col-md-5" id="table-name" name="table_name" value="" placeholder="Table Name">
				<input type="hidden" name="modifier" value="">
			</div>

			<br><br>
			<hr>
			<div id="mytable" class="table-responsive table-responsive-xl">
				<table class="table" width="100%">
					<thead>
					<tr>
						<th>Field Primary Key</th>
						<th>Field Name</th>
						<th>Field Type</th>
						<th>Field Length</th>
						<th>Default</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<tr class="row-item inline">
						<td>
							<input type="hidden" name="itemId[]" class="" value="">
							<input type="checkbox" name="field_key[]" class="" value="1">
						</td>
						<td><input type="text" name="field_name[]" class="" value=""></td>
						<td><?php echo form_dropdown('field_typeId[]', $types, 0, " class='field_typeId' data-placeholder='Select a type'");?></td>
						<td><input type="text" name="field_length[]" class="" value=""></td>
						<td><input type="text" name="field_default[]" class="" value=""></td>
						<td><a href="javascript:void(0)" data-itemId="0" data-tableId="0" class="btn btn-danger remove_row">x</a></td>
					</tr>
					</tbody>
					<tfoot>
					<tr>
						<td colspan="8">
							<a href="javascript:void(0);" id="add-row" class="btn btn-secondary"><i class="ti-plus"></i> + add</a>
						</td>
					</tr>
					</tfoot>
				</table>
			</div>

			<div class="row">
				<div class="col-md-6"></div>
				<div class="col-md-6" style="text-align: right"><button type="submit" id="submit" class="btn btn-primary">Submit</button></div>
			</div>
		</form>
	</div>
</div>

<table class="hidden" id="row-table-hidden">
	<tbody>
		<tr class="row-item inline">
			<td>
				<input type="hidden" name="itemId[]" class="" value="0">
				<input type="checkbox" name="field_key[]" value="1" class="">
			</td>
			<td><input type="text" name="field_name[]" class=""></td>
			<td><?php echo form_dropdown('field_typeId[]', $types, 0, "id='field_typeId' class=' field_typeId' data-placeholder='Select a type'");?></td>
			<td><input type="text" name="field_length[]" class=""></td>
			<td><input type="text" name="field_default[]" class="" value=""></td>
			<td><a href="javascript:void(0)" data-itemId="0" data-tableId="0" class="btn btn-danger remove_row">x</a></td>
		</tr>
	</tbody>
</table>

