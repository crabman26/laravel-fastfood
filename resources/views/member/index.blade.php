@extends('master')
@section('content')
<h3 align="center">Member Order Page</h3>
	<br/>
	<table id="orders_table" style="width:100%;" class="table table-bordered">
		<thead>
			<tr>
				<th>Product</th>
				<th>Price(€)</th>
				<th>FullName</th>
				<th>Adress</th>
				<th>Phone</th>
				<th>State</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
		</thead>
	</table>
	<div id="OrderModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="order_form" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Add order</h4>
					</div>
					<div class="modal-body">
						{{csrf_field()}}
						<span id="form-output"></span>
						@php
							$name = Auth::user()->name;
						@endphp
						<input type="hidden" name="Username" id="Username" class="form-control" value="<?=$name?>"/>
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
					</div>
					<div class="modal-footer">
						<input type="hidden" name="order_id" id="order_id" value=""/>
						<input type="hidden" name="button_action" id="button_action" value="update"/>
						<input type="submit" value="Update Order" name="submit" id="action" class="btn btn-info"/>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<script>
	$(document).ready(function(){
		getProducts();
		var mail = $('#admin-link').attr('data-email');
		getUser(mail);

		function getUser(mail){
			$.ajax({
				'url': "{{ route('userajax.userid') }}",
				'method': "GET",
				'data': {mail: mail},
				success:function(data){
					fetchdata(data);
				}
			})
		}

		function fetchdata(id){
			$("#orders_table").DataTable({
				"processing" : true,
				"serverside": true,
				"ajax": { 
					url:"{{ route('order.memberorder') }}",
					data: {
						userid: id
					}
				},
				"columns":[
					{"data": "Title",orderable:false, searchable:false},
					{"data": "price"},
					{"data": "FullName"},
					{"data": "Adress"},
					{"data": "Phone"},
					{"data": "State"},
					{"data": "Status"},
					{"data": "action", orderable:false, searchable:false}
				]
			})
		}	
	

	$("#order_form").on('submit',function(event){
		event.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			'url': "{{ route('order.orderuser') }}",
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
					$("#orders_table").DataTable().ajax.reload();
				}
			}
		});
	})

	$(document).on('click','.edit',function(){
		var id = $(this).attr("id");
		$("#form-output").html('');
		$.ajax({
			"url": "{{ route('order.fetchdata') }}",
			"method": "GET",
			"data": {id:id},
			"dataType": "json",
			success:function(data){
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
				$(".modal-header").html('Edit Order');
			}
		})			
	})

	$(document).on('click','.delete',function(){
		var id = $(this).attr("id");
		if (confirm("Are you sure you want to cancel the order??")){
			$.ajax({
				"url":"{{ route('order.cancelorder') }}",
				"method": "GET",
				"data": {id:id},
				success:function(data){
					alert(data);
				}

			})
		}
	})

	});

	function getProducts(){
		$.ajax({
			'url': "{{ route('product.getproducts') }}",
			'method': "GET",
			'dataType': "json",
			success: function(data){
				for(i=0;i<data.length;i++){
					$("#Product").append($("<option></option>").text(data[i]['Title']));
				}
			}
		})
	}
	
</script>
@endsection