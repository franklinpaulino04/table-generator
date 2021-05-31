$(document).ready(function (){
	$('#table-generate').DataTable({
		'dataType': 'json',
		"ajax": url + "tableGenerator/datatables",
		'order': [[1, "asc"]],
		"columns": [
			{"data": 'tableId',         "sClass": "dt-tableId",       "width": "5%",   "defaultContent": "<i class='na'>-</i>"},
			{"data": 'table_name',      "sClass": "dt-table_name",    "width": "15%", "defaultContent": "<i class='na'>-</i>"},
			{"data": 'action',          "sClass": "dt-action",         "width": "5%",  "defaultContent": "<i class='na'>-</i>"}
		],
		'createdRow': function (row, data, index) {
			$('.dt-action', row).html(actionLinks(data));
		}
	});

	$(document).on('click', '#add-row',function () {
		let row = $('#row-table-hidden').clone();
		$('.table tfoot').before(row);
	});

	$(document).on('click', '.remove_row', function () {
		var item        = $(this).closest('tbody'),
			count       = $('#mytable tr.row-item').length,
			id          = parseInt($(this).data('itemid')),
			data        = {type: 'get', url: url.baseUrl + 'tableGenerator/hide_items/' + id, doAfter: 'remove', selector: item};

		if(count > 1) {
			if(id === 'undefined' || id == 0){
				item.remove();

			}else{

			}

		} else {
			alert("I can't delete all the items");
		}
	});
});

var actionLinks = function (data) {
		let link            = url,
			id				= data.tableId,
			html            = '<div class="btn-group" role="group">';
			html           += '<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Options';
			html           += '</button>';
			html           += '<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
			html           += '<li><a class="dropdown-item" href="#">Edit</a></li>';
			html           += '<li><a class="dropdown-item" href="#">Delete</a></li>';
			html           += '</ul></div>';

	return html;
};
