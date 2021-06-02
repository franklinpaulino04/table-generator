$(document).ready(function (){
	let table = $('#table-generate').DataTable({
		'dataType': 'json',
		"ajax": url + "tableGenerator/datatables",
		'order': [[1, "asc"]],
		"columns": [
			{"data": 'tableId',    "sClass": "dt-tableId",    "width": "5%",   "defaultContent": "<i class='na'>-</i>"},
			{"data": 'table_name', "sClass": "dt-table_name", "width": "15%", "defaultContent": "<i class='na'>-</i>"},
			{"data": 'action',     "sClass": "dt-action",     "width": "5%",  "defaultContent": "<i class='na'>-</i>"}
		],
		'createdRow': function (row, data, index) {
			$('.dt-action', row).html(actionLinks(data));
		}
	});

	$(document).on('click', '#add-row', addRow);

	$(document).on('click', '#submit', function (){
		let formUrl 	= $('#form').attr('action'),
			formData 	= $('#form').serialize();

		postData(formUrl, formData)
			.then(data => {

				let {result, error} = data;

				if(result == 1){
					window.location.href = url + 'tableGenerator';
				}
				else{
					$('.response').html(error);
				}

			}).catch( (err) =>{
			console.log(err);
		});
	});

	$(document).on('click', '.remove_row', function () {
		var item        = $(this).closest('tbody'),
			count       = $('#mytable tr.row-item').length,
			itemId      = parseInt($(this).data('itemid')),
			tableId     = parseInt($(this).data('tableid')),
			data        = {url: url + 'tableGenerator/hide_items/' + itemId + '/' + tableId, selector: item};

		if(count > 1) {
			if(itemId === 'undefined' || itemId == 0){
				item.remove();

			}else{
				getData(data.url).then(( response ) =>{
					data.selector.remove();
				}).catch( ( error ) =>{
					console.log(error);
				});
			}

		} else {
			alert("I can't delete all the items");
		}
	});

	$(document).on('click', '.delete_row', function () {
		var url = $(this).data('url');

		getData(url).then(( response ) =>{
			table.ajax.reload();
		}).catch( ( error ) =>{
			console.log(error);
		});
	});

	$(document).on('click', '.field-key', function () {

		$('#mytable table tbody tr.row-item').each(function (key, value) {

			$(value).find('.field-key').prop('checked', false);
		});

		$(this).prop( "checked", true);
	});

	if($('#mytable table tbody').length === 0) {
		addRow();
	}
});

var actionLinks = function (data) {
		let id				= data.tableId,
			html            = '<div class="btn-group" role="group">';
			html           += '<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Options';
			html           += '</button>';
			html           += '<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
			html           += '<li><a class="dropdown-item" href="' + url + 'tableGenerator/edit/' + id + '">Edit</a></li>';
			html           += '<li><a class="dropdown-item delete_row" href="javascript:void(0)" data-url="' + url + 'tableGenerator/delete/' + id + '">Delete</a></li>';
			html           += '</ul></div>';

	return html;
};

var addRow = function (){
	let row = $('#row-table-hidden tbody').clone();
	$('.table tfoot').before(row);
}

async function postData(url = '', data = {}) {

	const response = await fetch(url, {
		method: "POST",
		headers: {'Content-Type': 'application/x-www-form-urlencoded',},
		body: data,
	});

	return response.json();
}

async function getData(url = '') {

	const response = await fetch(url, {
		method: "GET",
		headers: {'Content-Type': 'application/x-www-form-urlencoded',},
	});

	return response.json();
}
