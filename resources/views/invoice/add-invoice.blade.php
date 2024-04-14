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
      <div>
        <select id="partyIdInput">
            <option>--Select Party -- </option>
            @foreach($PartyMaster as $party)
                <option value="{{$party->PARTY_ID}}">{{$party->PARTY_NAME}}</option>
            @endforeach    
        </select>
            Invoice No. <input id="invoiceNumberInput" type="text" />
      </div> 
      <div>
            Invoice Date <input id="invoiceDateInput" type="date" />
      </div>
    </div>
    <h2>Add Invoice</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Sr. No.</th>
          <th>Item Name</th>
          <th>Units</th>
          <th>quantity</th>
          <th>Rate</th>
          <th>Value</th>
        </tr>
      </thead>
      <tbody>
        
        <tr class="item-row">
        <td>1</td>
          <td>
          <select class="itemSelect" onchange="getUnits(this)">
            <option value="">--Select Item -- </option>
            @foreach($ItemMaster as $item)
                <option value="{{$item->ITEM_ID}}">{{$item->ITEM_NAME}}</option>
            @endforeach    
        </select>
          </td>
          <td>
            <select class="unitSelect">
                <option>--Select Item -- </option>
                @foreach($uom as $units)
                    <option value="{{$units->UOM_ID}}">{{$units->UOM_NAME}}</option>
                @endforeach    
            </select>
          </td>
          <td><input class="quantityInput" type="number" /></td>
          <td><input class="rateInput" type="number" onchange="calculateTotal(this)" /></td>
          <td><input type="number" class="totalInput" readonly /></td>
        </tr>
        
      </tbody>
    </table>
    <div>
            Total Amount <input id="grandTotalInput" />
            <button type="button" id="submitButton">Submit</button>
            <a href="{{route('invoice.index')}}" type="button" >Cancel</button>
        </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<script>
    $(document).ready(function() {
        // Bind submitForm function to click event of submit button
        $('#submitButton').click(function() {
            submitForm();
        });
        
    });
    function getUnits(select){
        var itemId = select.value;
        $.ajax({
            type: "GET",
            url: "/getUnits/" + itemId,
            success: function(response){ console.log(response.UOM_ID);
                var UnitID = response.UOM_ID;
                    var unitsSelect = $(select).closest('tr').find('.unitSelect');

                    if (!UnitID) {
                        unitsSelect.val(''); // Reset units dropdown if no item selected
                        return;
                    }

                    unitsSelect.val(UnitID);
            },
            error: function(){
                alert("Error: Unable to fetch units.");
            }
        });
    }
function calculateTotal(input) {
    var row = $(input).closest('tr');
    var quantity = parseFloat(row.find('.quantityInput').val());
    var rate = parseFloat(row.find('.rateInput').val());
    var total = isNaN(quantity) || isNaN(rate) ? 0 : quantity * rate;
    row.find('.totalInput').val(total.toFixed(2));

    updateGrandTotal(); // Update grand total whenever a row total changes

    // Check if rate is entered and add a new row if necessary
    if (rate && !row.next().length) {
        var newRow = row.clone();
        newRow.find('input').val(''); // Clear input values
        row.after(newRow);
        updateGrandTotal(); // Update grand total after adding a new row
    }
}

function updateGrandTotal() {
    var grandTotal = 0;
    $('.totalInput').each(function() {
        var total = parseFloat($(this).val());
        grandTotal += isNaN(total) ? 0 : total;
    });
    $('#grandTotalInput').val(grandTotal.toFixed(2));
}

function submitForm() {
    var grandTotal = $('#grandTotalInput').val();
    var partyId = $('#partyIdInput').val(); // Assuming you have an input with ID 'partyIdInput' for party ID
    var invoiceNumber = $('#invoiceNumberInput').val(); // Assuming you have an input with ID 'invoiceNumberInput' for invoice number
    var invoiceDate = $('#invoiceDateInput').val(); // Assuming you have an input with ID 'invoiceDateInput' for invoice date

    var items = []; // Array to hold the items data
    $('.item-row').each(function() {
        var itemId = $(this).find('.itemSelect').val();
        var uomId = $(this).find('.unitSelect').val();
        var quantity = $(this).find('.quantityInput').val();
        var rate = $(this).find('.rateInput').val();
        var value = quantity * rate;

        if (itemId && uomId && quantity && rate) {
            var value = quantity * rate;
            items.push({ itemId: itemId, uomId: uomId, quantity: quantity, rate: rate, value: value });
        }
    });
  console.log(items);
    // Prepare the data to be sent in the AJAX request
    var formData = {
        grandTotal: grandTotal,
        partyId: partyId,
        invoiceNumber: invoiceNumber,
        invoiceDate: invoiceDate,
        items: items
    };

    // Send an AJAX request to submit the form data
    $.ajax({
        type: "POST",
        url: "{{ route('insert-invoice') }}",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response){
           // alert(response); // Show success message
           // location.reload.url = "{{route('invoice.index')}}"
            window.location.href = "{{route('invoice.index')}}"; 
        },
        error: function(xhr, status, error){
            console.error("Error:", error); // Log the error
            alert("Error: Unable to submit form.");
        }
    });
}
</script>
