$(document).ready(function (){
	$('#table-generate').DataTable({
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
			id          = parseInt($(this).data('itemid')),
			data        = {type: 'get', url: url + 'tableGenerator/hide_items/' + id, selector: item};

		if(count > 1) {
			if(id === 'undefined' || id == 0){
				item.remove();

			}else{
				fetch(data.url).then(( response ) =>{
					data.selector.remove();
				}).catch( ( error ) =>{
					console.log(error);
				});
			}

		} else {
			alert("I can't delete all the items");
		}
	});

	if($('#mytable table tbody').length === 0) {
		addRow();
	}
});

var actionLinks = function (data) {
		let link            = url,
			id				= data.tableId,
			html            = '<div class="btn-group" role="group">';
			html           += '<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Options';
			html           += '</button>';
			html           += '<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
			html           += '<li><a class="dropdown-item" href="' + url + 'tableGenerator/edit/' + id + '">Edit</a></li>';
			html           += '<li><a class="dropdown-item" href="javascript:void(0)" data-url="' + url + 'tableGenerator/delete/' + id + '">Delete</a></li>';
			html           += '</ul></div>';

	return html;
};

var addRow = function (){
	let row = $('#row-table-hidden tbody').clone();
	$('.table tfoot').before(row);
}

async function postData(url = '', data = {}) {

	const response = await fetch(url, {
		method: 'POST',
		headers: {'Content-Type': 'application/x-www-form-urlencoded',},
		body: data,
	});

	return response.json();
}
