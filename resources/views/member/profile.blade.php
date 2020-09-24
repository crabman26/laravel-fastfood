@extends('master')
@section('content')
 <br />
    <h3 align="center">Edit Profile</h3>
    <br />
    <br />
    <table id="user_table" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>E-mail</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>

<div id="memberModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="member_form">
                <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h4 class="modal-title">Insert Data</h4>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <span id="form_output"></span>
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="Name" id="Name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>E-mail:</label>
                        <input type="e-mail" name="E-mail" id="E-mail" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"/>
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" name="Password" id="Password" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_id" id="user_id" value="" />
                    <input type="hidden" name="button_action" id="button_action" value="insert" />
                    <input type="submit" name="submit" id="action" value="Add" class="btn btn-info" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    var mail = $('#admin-link').attr('data-email');
    fetch_member(mail);
    function fetch_member(mail){
        $('#user_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "{{ route('usersajax.memberdata') }}",
                data: {
                    mail:mail,
                }
            },
            "columns":[
                { "data": "name" },
                { "data": "email" },
                { "data": "action", orderable:false, searchable: false}
            ]
         });

    }

    $('#member_form').on('submit', function(event){
        event.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url:"{{ route('usersajax.postdata') }}",
            method:"POST",
            data:form_data,
            dataType:"json",
            success:function(data)
            {
                if(data.error.length > 0)
                {
                    var error_html = '';
                    for(var count = 0; count < data.error.length; count++)
                    {
                        error_html += '<div class="alert alert-danger">'+data.error[count]+'</div>';
                    }
                    $('#form_output').html(error_html);
                }
                else
                {
                    $('#form_output').html(data.success);
                    $('#user_table').DataTable().ajax.reload();
                }
            }
        })
    });

    $(document).on('click', '.edit', function(){
        var id = $(this).attr("id");
        $('#form_output').html('');
        $.ajax({
            url:"{{ route('usersajax.fetchdata') }}",
            method:'get',
            data:{id:id},
            dataType:'json',
            success:function(data)
            {
                $('#Name').val(data.Name);
                $('#E-mail').val(data.Email);
                $('#Password').val(data.Password);
                $('#user_id').val(id);
                $('#memberModal').modal('show');
                $('#action').val('Edit');
                $('.modal-title').text('Edit data');
                $('#button_action').val('Update');
            }
        })
    });
});
</script>
@endsection