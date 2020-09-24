@extends('master')
@section('content')
	<h3 align="center">Order Processing Page</h3>
	<br/>
	<div align="right">
		<button type="button" name="add" id="add_data" class="btn btn-success btn-sm">Add order</button>
	</div>
	<table id="orders_table" style="width:100%;" class="table table-bordered">
		<thead>
			<tr>
				<th>
					<select name="Users" id="Users" class="form-control input-lg dynamic">
						<option></option>
					</select>
				</th>
				<th>
					<select name="Products" id="Products" class="form-control input-lg dynamic">
						<option></option>
					</select>
				</th>
				<th>FullName</th>
				<th>Adress</th>
				<th>Phone</th>
				<th>State</th>
				<th>
					<select name="Status" id="Status" class="form-control input-lg dynamic">
						<option></option>
						<option>Cancellation</option>
						<option>Delivery</option>
						<option>Preparation</option>
					</select>
				</th>
				<th>Action</th>
				<th><button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></button></th>
			</tr>
		</thead>
	</table>
	<div id="OrderModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="order_form" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Add Order</h4>
					</div>
					<div class="modal-body">
						{{csrf_field()}}
						<span id="form-output"></span>
						<div class="form-group">
							<label for="User">User</label>
							<select id="User" name="User" class="form-control input-lg dynamic">
								<option></option>
							</select>
						</div>
						<div class="form-group">
							<label for="Product">Product</label>
							<select id="Product" name="Product" class="form-control input-lg dynamic">
								<option></option>
							</select>
						</div>
						<div class="form-group">
							<label for="FullName">FullName</label>
							<input type="text" name="FullName" id="FullName" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="Adress">Adress</label>
							<input type="text" name="Adress" id="Adress" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="Phone">Phone</label>
							<input type="text" name="Phone" id="Phone" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="State">State</label>
							<select name="State" id="State" class="form-control input-lg dynamic">
								<option>Athens</option>
								<option>Galatsi</option>
								<option>Hrakleio</option>
								<option>Nea Ionia</option>
								<option>Perissos</option>
							</select>
						</div>
						<div class="form-group">
							<label for="Status">Status</label>
							<select id="Status" name="Status" class="form-control input-lg dynamic">
								<option>Preparation</option>
								<option>Delivery</option>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="order_id" id="order_id" value=""/>
						<input type="hidden" name="button_action" id="button_action" value="insert"/>
						<input type="submit" value="Add Order" name="submit" id="action" class="btn btn-info"/>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
<script>
	$(document).ready(function(){
		getProducts();
		getUsers();
		fetchdata();

		$("#Users").on('change',function(){
			var username = $(this).children('option:selected').val();
			$("#orders_table").DataTable().destroy();
			fetchdata(username,'','');
		});

		$("#Products").on('change',function(){
			var product = $(this).children('option:selected').val();
			$("#orders_table").DataTable().destroy();
				fetchdata('',product,'');
		});

		$('#Status').on('change',function(){
			var status = $(this).children('option:selected').val();
			$('#orders_table').DataTable().destroy();
			fetchdata('','',status)
		});

		function fetchdata(username = '',product = '',status = ''){
			$("#orders_table").DataTable({
				"processing" : true,
				"serverside": true,
				"ajax": { 
					url:"{{ route('order.getdata') }}",
					data: {
						username:username,
						product:product,
						status:status
					}
				},
				"columns":[
					{"data": "name",orderable:false, searchable:false},
					{"data": "Title",orderable:false, searchable:false},
					{"data": "FullName"},
					{"data": "Adress"},
					{"data": "Phone"},
					{"data": "State"},
					{"data": "Status",orderable:false, searchable:false},
					{"data": "action", orderable:false, searchable:false},
					{"data": "checkbox", orderable:false, searchable:false}
				]
			})
		}	
	});

	$("#add_data").click(function(){
		$("#OrderModal").modal('show');
		$("#order_form")[0].reset();
		$("#form-output").html('');
		$("#button_action").val('insert');
		$("#action").val("Add");
	});

	$("#order_form").on('submit',function(event){
		event.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			'url': "{{ route('order.postdata') }}",
			'method': "POST",
			'data' : form_data,
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
					$("#order_form")[0].reset();
					$("#action").val("Add");
					$("#button_action").val('insert');
					$("#orders_table").DataTable().ajax.reload();
				}
			}
		});
	});

	$(document).on('click','.edit',function(){
		var id = $(this).attr('id');
		$("#form-output").html('');
		$.ajax({
			"url": "{{ route('order.fetchdata') }}",
			"method": "GET",
			"data": {id:id},
			"dataType": "json",
			success:function(data){
				$("#User").val(data.User);
				$("#Product").val(data.Product);
				$("#FullName").val(data.FullName);
				$("#Adress").val(data.Adress);
				$("#Phone").val(data.Phone);
				$("#State").val(data.State);
				$("#Status").val(data.Status);
				$('#order_id').val(id);
				$("#OrderModal").modal('show');
				$("#action").val("Edit");
				$("#button_action").val('update');
				$(".modal-header").html('Edit Data');
			}
		})			
	});

	$(document).on('click','.delete',function(){
		var id = $(this).attr("id");
		if (confirm("Are you sure you want to delete this data??")){
			$.ajax({
				"url": "{{ route('order.removedata') }}",
				"method": "GET",
				"data": {id:id},
				success:function(data){
					alert(data);
					$("#orders_table").DataTable().ajax.reload();
				}
			})
		}
	});

	$(document).on('click','#bulk_delete',function(){
		var id = [];
		if (confirm("Are you sure you want to delete the data???")){
			$('.order_checkbox:checked').each(function(){
				id.push($(this).val());
			});
			if (id.length > 0){
				$.ajax({
					"url": "{{ route('order.massremove') }}",
					"method": "GET",
					"data": {id:id},
					success:function(data){
						alert(data);
						$("#orders_table").DataTable().ajax.reload();
					}
				})
			}
		}
	});

	function getProducts(){
		$.ajax({
			'url': "{{ route('product.getproducts') }}",
			'method': "GET",
			'dataType': "json",
			success: function(data){
				for(i=0;i<data.length;i++){
					$("#Product").append($("<option></option>").text(data[i]['Title']));
					$("#Products").append($("<option></option>").text(data[i]['Title']));
				}
			}
		})
	}

	function getUsers(){
		$.ajax({
			'url': "{{ route('order.getusers') }}",
			'method': "GET",
			'dataType': "json",
			success:function(data){
				for(i=0;i<data.length;i++){
					$("#User").append($("<option></option>").text(data[i]['name']));
					$("#Users").append($("<option></option>").text(data[i]['name']));
				}
			}
		})
	}


	</script>
@endsection