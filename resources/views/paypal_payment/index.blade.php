<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details</title>
</head>
<body>
    <div>
        <h1>Item Details</h1>
        <p>Item Name: Product X</p>
        <p>Item Description: Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        <p>Item Price: $200</p>
        
        <!-- Payment Option -->
        <form action="{{route('payment')}}" method="post">
            @csrf
            <input type="hidden" name="amount" value="200">
            <input type="submit" value="Buy Now">
        </form>
    </div>
</body>
</html>
