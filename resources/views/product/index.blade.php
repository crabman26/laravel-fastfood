@extends('master')
@section('content')
	<h3 align="center">Product processing page</h3>
	<br />
	<div align="right">
		<button type="button" name="add" id="add_data" class="btn btn-success btn-sm">Add product</button>
	</div>
	<br />
	<div style="width:200px;">
		<h3 align="center">Available:</h3>
	    <input type="radio" name="Availability" id="Active" value="Yes" class="form-check-input"/>
	    <label class="form-check-label" for="Active">Yes</label>
	    <input type="radio" name="Availability" id="Inactive" value="No" class="form-check-input" style="margin-left:10px;"/>
	    <label class="form-check-label" for="Inactive">No</label>
    </div>
	<table id="products_table" class="table table-bordered" style="width:100%">
		<thead>
			<tr>
				<th>
					<select class="form-control" id="Categories" name="Categories" class="form-control input-lg dynamic">
						<option>	
						</option>
					</select>
					</th>
					<th>Title</th>
					<th>Price(€)</th>
					<th>Description</th>
					<th>Actions</th>
					<th><button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></button></th>
				</tr>
			</thead>
		</table>
	</div>
	<div id="productModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="product_form" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Add Product</h4>
					</div>
					<div class="modal-body">
						{{csrf_field()}}
						<span id="form-output"></span>
						<div class="form-group">
							<label for="Category">Category</label>
							<select name="Category" id="Category" class="form-control input-lg dynamic">
								<option>-Select Category-</option>
							</select>
						</div>
						<div class="form-group">
							<label for="Title">Title</label>
							<input type="text" name="Title" id="Title" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="Price">Price</label>
							<input type="text" name="Price" id="Price" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="Description">Description</label>
							<input type="text" name="Description" id="Description" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="Available">Available</label>
							<select id="Available" name="Available" class="form-control input-lg dynamic"/>
								<option>Yes</option>
								<option>No</option>
						</div>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="product_id" id="product_id" value=""/>
						<input type="hidden" name="button_action" id="button_action" value="insert" />
						<input type="submit" name="submit" id="action" value="Add Product" class="btn btn-info"/>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<script>
	$(document).ready(function(){
		getCategories();
		fetch_category();
		$('#Categories').on('change',function(){
			var category = $(this).children('option:selected').val();
			$('#products_table').DataTable().destroy();
			fetch_category(category,'');
		});
		$('#Active, #Inactive').on('click',function(){
			var category = $('#Categories').children("option:selected").val();
			var available = $(this).val();
			if ($(this).is(":checked")){
				if (category != ''){
				    $("#products_table").DataTable().destroy();
				    fetch_category(category,available);
				} else {	
				    $("#products_table").DataTable().destroy();
				    fetch_category('',available);
				}
			}
		});
				
		function fetch_category(category = '',available = ''){
			$('#products_table').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": 
					{
						url: "{{ route('product.getdata') }}",
						data: {
							category:category,
							available:available
						}
					},
					"columns":[
						{"data": "Name", orderable:false, searchable: false},
						{"data": "Title"},
						{"data": "price"},
						{"data": "description"},
						{"data": "action", orderable:false, searchable:false},
						{"data":"checkbox", orderable:false, searchable:false}
					]
				});
			}
		});

		$("#add_data").click(function(){
			$("#productModal").modal('show');
			$("#product_form")[0].reset();
			$("#form-output").html('');
			$("#button_action").val('insert');
			$("#action").val('Add');
		});


		$("#product_form").on('submit',function(event){
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
				'url': "{{ route('product.postdata')}}",
				'method': "POST",
				'data': form_data,
				'dataType': "json",
				success:function(data){
					if (data.error.length > 0){
						var error_html = '';
						for (var count = 0; count < data.error.length; count++){
							error_html += '<div class="alert alert-danger">'+data.error[count]+'</div>'; 
						}
						$("#form-output").html(error_html);
					} else {
						$("#form-output").html(data.success);
						$("#product_form")[0].reset();
						$("#action").val('Add');
						$("#button_action").val('insert');
						$("#products_table").DataTable().ajax.reload();
					}
				}
			});
		});

		$(document).on('click','.edit',function(){
			var id = $(this).attr('id');
			$("#form-output").html('');
			$.ajax({
				url: "{{ route('product.fetchdata') }}",
				method: 'get',
				data:{id:id},
				dataType: 'json',
				success:function(data){
					$('#Category').val(data.Category);
					$('#Title').val(data.Title);
					$('#Price').val(data.Price);
					$('#Description').val(data.Description);
					$('#Available').val(data.Available);
					$('#product_id').val(id);
					$('#productModal').modal('show');
					$('#action').val('Edit');
					$('.modal-title').html('Edit Data');
					$('#button_action').val('update');
				}
			})
		});

		$(document).on('click','.delete',function(){
			var id = $(this).attr('id');
			if (confirm("Are you sure you want to delete data")){
				$.ajax({
					url: "{{ route('product.removedata') }}",
					method: 'get',
					data: {id:id},
					success:function(data){
						alert(data);
						$('#products_table').DataTable().ajax.reload();
					}
				})
			}
		});

		$(document).on('click','#bulk_delete',function(){
			var id = [];
			if (confirm("Are you sure you want to delete this data?")){
				$('.product_checkbox:checked').each(function(){
					id.push($(this).val());
				});
				if (id.length > 0){
					$.ajax({
						url: "{{ route('product.massremove')}}",
						method: 'get',
						data: {id:id},
						success:function(data){
							alert(data);
							$('#products_table').DataTable().ajax.reload();
						}
					});
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
	