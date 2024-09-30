<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"> <span>{{ $product->product_name }} {{ $product->sub_sku }}</span> </h4>
        </div>
        <div class="modal-body">
            {{-- @php
            $row_count = $loop->iteration
        @endphp --}}
            <div class="imei_row_{{ $row_count }} imei_row_parent">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-barcode"></i>
                        </span>
                        <input type="text" class="form-control imei_search" id="imei_search">
                        <input type="hidden" class="product_id{{ $row_count }}" value="{{ $product->product_id }}">
                    </div>
                    <input type="hidden" class="row_count_{{ $row_count }} row_count" value="{{ $row_count }}">
                </div>
                @if(!empty($product->imei_details))
                    @foreach ($product->imei_details as $imei_detail )
                        <div class="row">
                            <div class=" col-xs-10 form-group">
                            <input type="text" class="form-control" name="" id="" value="{{ $imei_detail->imei_number }}">
                            <input type="hidden" class="form-control" name="products[{{ $row_count }}][imeis][]" id="" value="{{ $imei_detail->id }}">
                            </div>
                            <button type="button" class="btn btn-danger decrement"  data-parent = '{{ $row_count }}'>-</button>
                        </div>
                    @endforeach  
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success modalbutton" data-dismiss="modal">OK</button>
        </div>
    </div>
</div>