<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Order Trans</th>
            <th>Season</th>
            <th>Buyer</th>
            <th>Brand</th>
            <th>Style</th>
            <th>Master PO</th>
            <th>Qty</th>
            <th>OCF</th>
            <th>GMT</th>
            <th>MR</th>
            <th>Sketch</th>
            <th>Remark</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ordermasters as $ordermaster)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $ordermaster->order_trans }}</td>
            <td>{{ $ordermaster->season_cat }} {{ $ordermaster->season_year }}</td>
            <td>{{ $ordermaster->buyer_name }}</td>
            <td>{{ $ordermaster->brand_name }}</td>
            <td>{{ $ordermaster->style_name }}</td>
            <td>{{ $ordermaster->po_master }}</td>
            <td>{{ $ordermaster->qty_order }}</td>
            <td>{{ $ordermaster->qty_ocf }}</td>
            <td>{{ $ordermaster->sum_raf_qty }}</td>
            <td>{{ $ordermaster->fu_name }}</td>
            <!-- <td>{{ $ordermaster->sketch_file }}</td> -->
            <td><img id="sketch" src="{{ asset('/sketch/' . $ordermaster->sketch_file) }}" width="200px"></td>
            <td>{{ $ordermaster->remark }}</td>
        </tr>
        @endforeach
    </tbody>
</table>