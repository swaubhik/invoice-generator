<!DOCTYPE html>
<html>

<head>
  <title>Create Bill</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .remove-btn {
      cursor: pointer;
      color: red;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <h2>Create Bill for: {{ $application->title }}</h2>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('bills.store', $application->id) }}" method="POST">
      @csrf
      <table class="table-bordered table" id="bill-items-table">
        <thead>
          <tr>
            <th>Description</th>
            <th>Rate (₹)</th>
            <th>Quantity</th>
            <th>Amount (₹)</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="text" name="items[0][description]" class="form-control" required></td>
            <td><input type="number" step="0.01" name="items[0][rate]" class="form-control rate" required></td>
            <td><input type="number" name="items[0][quantity]" class="form-control quantity" required></td>
            <td><input type="number" step="0.01" name="items[0][amount]" class="form-control amount" readonly></td>
            <td><span class="remove-btn">Remove</span></td>
          </tr>
        </tbody>
      </table>
      <button type="button" class="btn btn-secondary" id="add-item">Add Item</button>
      <hr>
      <div class="row">
        <div class="col-md-6 offset-md-6">
          <table class="table">
            <tr>
              <th>Subtotal:</th>
              <td>₹ <span id="subtotal">0.00</span></td>
            </tr>
            <tr>
              <th>GST (18%):</th>
              <td>₹ <span id="gst">0.00</span></td>
            </tr>
            <tr>
              <th>Grand Total:</th>
              <td>₹ <span id="grand_total">0.00</span></td>
            </tr>
          </table>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Generate Bill</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    let itemIndex = 1;

    // Function to recalculate amounts
    function recalculate() {
      let subtotal = 0;
      $('#bill-items-table tbody tr').each(function() {
        let rate = parseFloat($(this).find('.rate').val()) || 0;
        let quantity = parseInt($(this).find('.quantity').val()) || 0;
        let amount = rate * quantity;
        $(this).find('.amount').val(amount.toFixed(2));
        subtotal += amount;
      });
      let gst = subtotal * 0.18;
      let grand_total = subtotal + gst;

      $('#subtotal').text(subtotal.toFixed(2));
      $('#gst').text(gst.toFixed(2));
      $('#grand_total').text(grand_total.toFixed(2));
    }

    // Add new item row
    $('#add-item').click(function() {
      let newRow = `
                <tr>
                    <td><input type="text" name="items[${itemIndex}][description]" class="form-control" required></td>
                    <td><input type="number" step="0.01" name="items[${itemIndex}][rate]" class="form-control rate" required></td>
                    <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" required></td>
                    <td><input type="number" step="0.01" name="items[${itemIndex}][amount]" class="form-control amount" readonly></td>
                    <td><span class="remove-btn">Remove</span></td>
                </tr>
            `;
      $('#bill-items-table tbody').append(newRow);
      itemIndex++;
    });

    // Remove item row
    $(document).on('click', '.remove-btn', function() {
      $(this).closest('tr').remove();
      recalculate();
    });

    // Recalculate on input change
    $(document).on('input', '.rate, .quantity', function() {
      recalculate();
    });

    // Initial calculation
    recalculate();
  </script>
</body>

</html>
