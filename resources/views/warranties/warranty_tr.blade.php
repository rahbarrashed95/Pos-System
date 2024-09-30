@foreach($transactions as $tr)
    <tr>
        <td>{{ $tr->transaction_id }}</td>
        <td>{{ \Carbon\Carbon::parse($tr->transaction_date)->format('d-m-Y') }}</td>
        <td>{{ $tr->contact_name }}</td>
        <td>{{ $tr->customer_address }}</td>
        <td>{{ $tr->customer_mobile }}</td>
        <td>{{ $tr->product_name }}</td>
        <td>{{ $tr->product_warranty - $tr->days_diff }}</td>
    </tr>
@endforeach