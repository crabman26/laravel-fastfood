@extends('master')
@section('content')
	<br />
	<h3 align="center">Offer processing page</h3>
	<br />
	<div align="right">
		<button id="add_data" class="btn btn-success btn-sm" type="button" name="add">Add offer</button>
	</div>
	<br />
	<table id="offers_table" style="width:100%;" class="table table-bordered">
		<thead>
			<tr>
				<th><select class="form-control" id="Categories" name="Categories" class="form-control input-lg dynamic">
					<option>	
					</option>
				</th>
				<th>Title</th>
				<th>Discount(%)</th>
				<th>Begin Date</th>
				<th>Expire Date</th>
				<th>Actions</th>
				<th><button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></button></th>
			</tr>		
		</thead>
	</table>
	<div id="OfferModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="offers_form" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Add Offer</h4>
					</div>
					<div class="modal-body">
						{{csrf_field()}}
						<span id="form-output"></span>
						<div class="form-group">
							<label for="Category">Category</label>
							<select id="Category" name="Category" class="form-control input-lg dynamic">
								<option>- Select Category -</option>
							</select>
						</div>
						<div class="form-group">
							<label for="Title">Title</label>
							<input type="text" id="Title" name="Title" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="Discount">Discount(%)</label>
							<input type="text" id="Discount" name="Discount" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="Begin_date">Begin Date</label>
							<input type="text" id="Begin_date" name="Begin_date" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="Expire_date">Expire Date</label>
							<input type="text" id="Expire_date" name="Expire_date" class="form-control" />
						</div>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="offer_id" id="offer_id" value=""/>
						<input type="hidden" name="button_action" id="button_action" value="insert"/>
						<input type="submit" value="Add Offer" name="submit" id="action" class="btn btn-info"/>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>				
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		getCategories();
		fetch_category();
		$('#Categories').on('change',function(){
			var category = $(this).children('option:selected').val();
			$('#offers_table').DataTable().destroy();
			fetch_category(category);
		});
				
		function fetch_category(category = '',available = ''){
			$('#offers_table').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": 
				{
					url: "{{ route('offer.getdata') }}",
					data: {
						category:category
					}
				},
				"columns":[
					{"data": "Name", orderable:false,searchable:false},
					{"data": "Title"},
					{"data": "discount"},
					{"data": "Begin_Date"},
					{"data": "Expire_Date"},
					{"data": "action", orderable:false, searchable:false},
					{ "data":"checkbox", orderable:false, searchable:false}
				]
			});
		}
	});

	$("#add_data").click(function(){
		$("#OfferModal").modal('show');
		$("#offers_form")[0].reset();
		$("#form-output").html('');
		$("#button_action").val('insert');
		$("#action").val("Add");
	});

	$("#offers_form").on('submit',function(event){
		event.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			'url': "{{ route('offer.postdata') }}",
			'method': "POST",
			'data': form_data,
			'dataType': "json",
			success: function(data){
				if (data.error.length > 0){
					var error_html = '';
					for (var count = 0; count < data.error.length; count++){
						error_html += '<div class="alert alert-danger">'+data.error[count]+'</div>';
					}
					$("#form-output").html(error_html);
				} else {
					$("#form-output").html(data.success);
					$("#offers_form")[0].reset();
					$("#action").val("Add");
					$("#button_action").val('insert');
					$("#offers_table").DataTable().ajax.reload();
				}
			}
		});
	});

	$(document).on('click','.edit',function(){
		var id = $(this).attr('id');
		$("#form-output").html('');
		$.ajax({
			'url': "{{ route('offer.fetchdata') }}",
			'method': 'get',
			'data': {id:id},
			'dataType': 'json',
			success:function(data){
				$("#Category").val(data.Category);
				$("#Title").val(data.Title);
				$("#Discount").val(data.Discount);
				$("#Begin_date").val(data.Begin_Date);
				$("#Expire_date").val(data.Expire_Date);
				$("#offer_id").val(id);
				$("#OfferModal").modal('show');
				$("#action").val("Edit");
				$("#button_action").val('update');
				$(".modal-title").html('Edit Data');
			}
		})
	});

	$(document).on('click','.delete',function(){
		var id = $(this).attr('id');
		if (confirm("Are you sure you want to remove the data")){
			$.ajax({
				'url': "{{ route('offer.removedata') }}",
				'method': "get",
				'data': {id:id},
				success:function(data){
					alert(data);
					$("#offers_table").DataTable().ajax.reload();
				}
			})
		}
	});

	$(document).on('click','#bulk_delete',function(){
		var id = [];
		if (confirm("Are you sure you want to delete the data")){
			$('.offer_checkbox:checked').each(function(){
				id.push($(this).val());
			});
			if (id.length > 0){
				$.ajax({
					'url': "{{ route('offer.massremove') }}",
					'method': "get",
					'data': {id:id},
					success:function(data){
						alert(data);
						$("#offers_table").DataTable().ajax.reload();
					}
				})
			}
		}
	});

	function getCategories(){
		$.ajax({
			'url': "{{ route('categorydata.getcategories') }}",
			'method': "GET",
			'dataType': 'json',
			success: function(data){
				for(i=0;i<data.length;i++){
					$("#Category").append($("<option></option>").text(data[i]['Name']));
					$("#Categories").append($("<option></option>").text(data[i]['Name']));
				}
			}
		});
	}
</script>
@endsection