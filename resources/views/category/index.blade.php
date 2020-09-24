@extends('master')
@section('content')
<br />
<h3 align="center">Category processing page</h3>
<br />
<div align="right">
    <button type="button" name="add" id="add_data" class="btn btn-success btn-sm">Add Category</button>
</div>
<br />
<table id="category_table" class="table table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Actions</th>
            <th><button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></button></th>
        </tr>
    </thead>
</table>
</div>
<div id="categoryModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="category_form">
                <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h4 class="modal-title">Add category</h4>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <span id="form_output"></span>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="Name" id="Name" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="category_id" id="category_id" value="" />
                    <input type="hidden" name="button_action" id="button_action" value="insert" />
                    <input type="submit" name="submit" id="action" value="Add Category" class="btn btn-info" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
     $('#category_table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "{{ route('category.getdata') }}",
        "columns":[
            { "data": "Name" },
            { "data": "action",ordarable:false, searchable:false},
            { "data":"checkbox", orderable:false, searchable:false}
        ]
     });

    $('#add_data').click(function(){
        $('#categoryModal').modal('show');
        $('#category_form')[0].reset();
        $('#form_output').html('');
        $('#button_action').val('insert');
        $('#action').val('Add');
    });

    $('#category_form').on('submit', function(event){
        event.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url:"{{ route('category.postdata') }}",
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
                    $('#category_form')[0].reset();
                    $('#action').val('Add');
                    $('.modal-title').text('Add Category');
                    $('#button_action').val('insert');
                    $('#category_table').DataTable().ajax.reload();
                }
            }
        })
    });

    $(document).on('click', '.edit', function(){
        var id = $(this).attr("id");
        $('#form_output').html('');
        $.ajax({
            url:"{{route('categorydata.fetchdata')}}",
            method:'get',
            data:{id:id},
            dataType:'json',
            success:function(data)
            {
                $('#Name').val(data.Name);
                $('#category_id').val(id);
                $('#categoryModal').modal('show');
                $('#action').val('Edit');
                $('.modal-title').text('Edit Data');
                $('#button_action').val('update');
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var id = $(this).attr('id');
        if(confirm("Are you sure you want to Delete this data?"))
        {
            $.ajax({
                url:"{{route('categorydata.removedata')}}",
                method:"get",
                data:{id:id},
                success:function(data)
                {
                    alert(data);
                    $('#category_table').DataTable().ajax.reload();
                }
            })
        }
        else
        {
            return false;
        }
    });

    $(document).on('click', '#bulk_delete', function(){
        var id = [];
        if(confirm("Are you sure you want to Delete this data?"))
        {
            $('.category_checkbox:checked').each(function(){
                id.push($(this).val());
            });
            if(id.length > 0)
            {
                $.ajax({
                    url:"{{ route('categorydata.massremove')}}",
                    method:"get",
                    data:{id:id},
                    success:function(data)
                    {
                        alert(data);
                        $('#category_table').DataTable().ajax.reload();
                    }
                });
            }
            else
            {
                alert("Please select at least one checkbox");
            }
        }
    }); 

});
</script>
@endsection

