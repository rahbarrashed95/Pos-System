<div class="modal fade" id="imei_modal{{$row_count}}" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ $product->name }} ({{ $variation->sub_sku }}) SN Numbers</h4>
            </div>
            <div class="modal-body">
                <div class="imei_row" id="imei_row_{{$row_count}}">
                    @for($i = 0; $i < $quantity_value; $i++)
                    <div class="row">
                        <div class=" col-xs-10 form-group">
                            <input type="text" class="form-control ime_input" data-id="{{$row_count}}" name="purchases[{{ $row_count }}][imei][{{$i}}][imei_number]">
                        </div>
                        @if($i === $quantity_value - 1)
                        <button type="button" class="btn btn-success increment button_{{$row_count}}" data-parent='{{ $row_count }}' onclick="addImeiInput({{$row_count}})">+</button>
                        @endif
                    </div>
                    @endfor
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>