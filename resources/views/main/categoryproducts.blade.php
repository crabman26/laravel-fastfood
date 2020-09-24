@extends('main.master')
@section('content')
  <h2 align="center">Product catalogue</h2>
  <table class="table table-bordered">
    <tr>
      <th>Title</th>
      <th>Price(€)</th>
      <th>Description</th>
      <th>Available</th>
      <th>Action</th>
    </tr>
    @foreach($products as $product)
      <tr>
        <td>{{$product->Title}}</td>
        <td>{{$product->price}}</td>
        <td>{{$product->description}}</td>
        <td>{{$product->available}}</td>
        <td><a href="#" class="btn btn-xs btn-primary edit" id="{{$product->id}}"><i class="glyphicon glyphicon-edit"></i>Order</a></td>
      </tr>
    @endforeach
  </table>
  <div id="OrderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        @if(count($errors) > 0)
          <div class="alert alert-danger">
            <ul>
              @foreach($errors->all() as $error)
                <li>{{$error}}</li>
              @endforeach
            </ul>
          </div>
        @endif
        @if(\Session::has('success'))
          <div class="alert alert-success">
           <p>{{ \Session::get('success') }}</p>
          </div>
        @endif
        <form id="order_form" method="post">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add product</h4>
          </div>
          <div class="modal-body">
            {{csrf_field()}}
            <span id="form-output"></span>
            <span id="error_name"></span>
            <div class="form-group">
              <label for="Username">Username</label>
              <input type="text" name="Username" id="Username" class="form-control"/>
            </div>
            <div class="form-group">
              <input type="hidden" name="pid" id="pid"/>
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
            <input type="hidden" name="button_action" id="button_action" value="user_order"/>
            <input type="submit" value="Add" name="submit" id="action" class="btn btn-info"/>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<script>
  $(document).on('click','.edit',function(){
      var id = $(this).attr("id");
      $("#form-output").html('');
      $("#OrderModal").modal('show');
      $('#pid').val(id);

      $('#Username').blur(function(){
        var error_name = '';
        var username = $('#Username').val();
        var _token = $('input[name="_token"]').val();
        $.ajax({
          url:"{{ route('email_available.check') }}",
          method:"POST",
          data:{username:username, _token:_token},
          success:function(result){
           if(result == 'unique'){
            $('#error_name').html('<label class="text-danger">You must create a user profile to complete the order.</label>');
            $('#Username').addClass('has-error');
            $('#action').attr('disabled', 'disabled');
           } else{
            $('#error_name').html('<label class="text-success">Username Available</label>');
            $('#Username').removeClass('has-error');
            $('#action').attr('disabled', false);
           }
          }
     })
      }); 
    });

    $('#order_form').on('submit',function(event){
      event.preventDefault();
      var data = $(this).serialize();
      $.ajax({
        'url': "{{ route('order.orderuser') }}",
        'method': "POST",
        'data': data,
        'dataType': 'json',
        success:function(data){
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
          }
        }
      });
    });
</script>
@endsection

