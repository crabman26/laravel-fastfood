@extends('master')
@section('content')
<br/>
<h3 align="center">User processing page</h3>
<br />
<div align="right">
    <button type="button" name="add" id="add_data" class="btn btn-success btn-sm">Add user</button>
</div>
<br />
<table id="users_table" class="table table-bordered" style="width:100%;">
    <thead>
        <tr>
            <th>Name</th>
            <th>E-mail</th>
            <th>Role</th>
            <th>Actions</th>
            <th><button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></button></th>
        </tr>
    </thead>
</table>
</div>
<div id="usersModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="users_form" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Add new user</h4>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <span id="form-output"></span>
                    <div class="form-group">
                        <label for="Name">Name:</label>
                        <input type="text" name="Name" id="Name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="E-mail">E-mail:</label>
                        <input type="text" name="E-mail" id="E-mail" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Password">Password:</label>
                        <input type="password" name="Password" id="Password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Role:</label>
                        <select name="Role" id="Role" class="form-control input-lg dynamic">
                            <option>Administrator</option>
                            <option>Member</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_id" id="user_id" value=""/>
                    <input type="hidden" name="button_action" id="button_action" value="insert" />
                    <input type="submit" name="submit" id="action" value="Add" class="btn btn-info" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#users_table').DataTable({
            "processing": true,
            "serverside": true,
            "ajax": "{{ route('usersajax.getdata') }}",
            "columns": [
                {"data": "name"},
                {"data": "email"},
                {"data": "Role"},
                {"data": "action",orderable:false,searchable:false},
                { "data": "checkbox", orderable:false, searchable: false}
            ]

        });

        $('#add_data').on('click',function(){
            $('#usersModal').modal('show');
            $('#users_form')[0].reset();
            $('#form-output').html('');
            $('#button_action').val('Insert');
            $('#action').val('Insert');
            $('.modal-title').text('Insert user');
        });

        $("#users_form").on('submit',function(){
            event.preventDefault();
            form_data = $(this).serialize();
            $.ajax({
                "url": "{{ route('usersajax.postdata') }}",
                "method": "post",
                "data": form_data,
                "dataType": "json",
                success: function(data){
                    if (data.error.length > 0){
                        var error_html = '';
                        for (var count = 0; count < data.error.length; count++){
                            error_html += "<div class='alert alert-danger'>"+data.error[count]+"</div>";
                        }
                        $('#form-output').html(error_html);
                    } else {
                        $('#form-output').html(data.success);
                        $('#users_form')[0].reset();
                        $('#action').val('Insert');
                        $('.modal-title').text('Insert user');
                        $('#button_action').val('Insert');
                        $('#users_table').DataTable().ajax.reload();
                    }
                }
            })
        });

        $(document).on('click','.edit',function(){
            var id = $(this).attr("id");
            $("#form-output").html('');
            $.ajax({
                "url": "{{ route('usersajax.fetchdata') }}",
                "method": "get",
                "data": {id:id},
                "dataType": 'json',
                success:function(data){
                    $("#Name").val(data.Name);
                    $("#E-mail").val(data.Email);
                    $("#Password").val(data.Password);
                    $('#user_id').val(id);
                    $('#usersModal').modal('show');
                    $('#action').val('Modify');
                    $('.modal-title').text('Modify user');
                    $('#button_action').val('Update');  
                }
            })
        });

        $(document).on('click','.delete',function(){
            var id = $(this).attr("id");
            if (confirm("Are you sure you want to delete the user???")){
                $.ajax({
                    "url": "{{ route('usersajax.removedata') }}",
                    "method": "get",
                    "data": {id:id},
                    success:function(data){
                        alert(data);
                        $('#users_table').DataTable().ajax.reload();
                    }
                })
            }
        });

        $(document).on('click','#bulk_delete',function(){
            var id = []
            if (confirm("Are you sure about the users deletion??")){
                $(".user_checkbox:checked").each(function(){
                    id.push($(this).val());
                    if (id.length > 0){
                        $.ajax({
                            "url": "{{ route('usersajax.massremove') }}",
                            "method": "get",
                            "data": {id:id},
                            success:function(data){
                                $('#users_table').DataTable().ajax.reload();
                            }
                        });
                    }
                })
            } else {
                alert("Please choose a user for delete.")
            }
        });

    });
</script>
@endsection