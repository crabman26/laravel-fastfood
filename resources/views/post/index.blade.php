@extends('master')
@section('content')
	<h3 align="center">Post Processing Page</h3>
	<br/>
	<div align="right">
		<button id="add_data" class="btn btn-success btn-sm" name="add" type="button" >Add Post</button>
	</div>
	<br />
	<table id="posts_table" class="table table-bordered" style="width:100%">
		<thead>
			<tr>
				<th>Title</th>
				<th>Text</th>
				<th>Actions</th>
				<th><button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></button></th>
			</tr>
		</thead>
	</table>
	<div id="PostModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="posts_form" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Add Post</h4>
					</div>
					<div class="modal-body">
						{{ csrf_field() }}
						<span id="form-output"></span>
						<div class="form-group">
							<label for="Title">Title</label>
							<input type="text" name="Title" id="Title" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="Text">Text</label>
							<input type="text" name="Text" id="Text" class="form-control"/>
						</div>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="post_id" id="post_id" value=""/>
						<input type="hidden" name="button_action" id="button_action" value="insert"/>
						<input type="submit" value="Add Post" name="submit" id="action" class="btn btn-info"/>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$("#posts_table").DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('post.getdata') }}",
			"columns": [
				{"data": "title"},
				{"data": "Text"},
				{"data": "action", orderable:false, searchable:false},
				{ "data":"checkbox", orderable:false, searchable:false}
			]
		})
	});

	$("#add_data").click(function(){
		$("#PostModal").modal('show');
		$("#posts_form")[0].reset();
		$("#form-output").html('');
		$("#button_action").val('insert');
		$("#action").val('Add');
	});

	$('#posts_form').on('submit',function(event){
		event.preventDefault();
		var form_data = $(this).serialize();
			$.ajax({
				'url': "{{ route('post.postdata') }}",
				'method': "Post",
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
						$("#posts_form")[0].reset();
						$("#action").val('Add');
						$("#button_action").val('insert');
						$("#posts_table").DataTable().ajax.reload();
					}
				}
			});
		});

		$(document).on('click','.edit',function(){
			var id = $(this).attr('id');
			$('#form-output').html('');
			$.ajax({
				'url': "{{ route('post.fetchdata') }}",
				'method': "get",
				'data': { id:id },
				'dataType': "json",
				success: function(data){
					$("#Title").val(data.Title);
					$("#Text").val(data.Text);
					$("#post_id").val(id);
					$("#PostModal").modal('show');
					$("#action").val('Edit');
					$('.modal-title').html('Edit Data');
					$("#button_action").val('Update');
				}
			})
		});

		$(document).on('click','.delete',function(){
			var id = $(this).attr('id');
			if (confirm("Are you sure you want to delete the post?")){
				$.ajax({
					'url': "{{ route('post.removedata') }}",
					'method': "get",
					'data': {id:id},
					success:function(data){
						alert(data);
						$("#posts_table").DataTable().ajax.reload();
					}
				})
			}
		});

		$(document).on('click','#bulk_delete',function(){
			var id = [];
			if (confirm("Are you sure you want to delete this data????")){
				$('.post_checkbox:checked').each(function(){
					id.push($(this).val());
					if (id.length > 0){
						$.ajax({
							'url': "{{ route('post.massremove') }}",
							'method': "get",
							'data': {id:id},
							success:function(data){
								alert(data);
								$("#posts_table").DataTable().ajax.reload();
							}
						})
					}
				});
			}
		});
</script>
@endsection