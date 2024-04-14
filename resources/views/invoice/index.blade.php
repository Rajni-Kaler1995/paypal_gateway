<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Invoices</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <div class="container mt-5">
    <div class="row mb-3">
      <div class="col text-right">
        <a type="button" class="btn btn-primary" href="{{route('add-invoice')}}">Add New</a>
      </div>

    </div>
    <h2>Invoices</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Sr. No.</th>
          <th>Invoice No.</th>
          <th>Invoice Date</th>
          <th>Party Name</th>
          <th>Invoice Value</th>
          <th>User Name</th>
        </tr>
      </thead>
      <tbody>
        
          @foreach($dochdr as $invoices)
        <tr>
        <td>{{$invoices->HDRAutoID}}</td>
        <td>{{$invoices->Invoice_No}}</td>
        <td>{{$invoices->InvoiceDate}}</td>
        <td>{{$invoices->PARTY_NAME}}</td>
          <td>{{$invoices->totalamount}}</td>
          <td>{{$invoices->User}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add New Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Your form elements can go here -->
          <form>
            <div class="form-group">
              <label for="firstName">Name</label>
              <input type="text" class="form-control" id="name">
            </div>
            <div class="form-group">
              <label for="age">Age</label>
              <input type="text" class="form-control" id="age">
            </div>
            <div class="form-group">
              <label for="phone_number">Phone Number</label>
              <input type="phone_number" class="form-control" id="phone_number">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="create-student">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<script>
      $(document).ready(function(){
      // Function to handle form submission
      $("#create-student").click(function(){
        var name = $("#name").val();
        var age = $("#age").val();
        var phone_number = $("#phone_number").val();
        $.ajax({
          type: "POST",
          url: "/createStudent", // Specify your server-side script to handle the data
          data: { name: name, age: age, phone_number: phone_number },
          headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
          success: function(response){
            $('#dataTable tbody').append(response); // Append the new row to the table
            $('#exampleModal').modal('hide'); // Hide the modal after successful submission
            window.location.reload();
          },
          error: function(){
            alert("Error: Unable to add item.");
          }
        });
      });
    });
  </script>
</script>
