@extends('layouts.app')
@section('title', __('Sell Details'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('Sell Details')
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'Sell Details' )])
        @slot('tool')
            <div class="box-tools">
                <input type="text" class="form-control search_sn" placeholder="Enter Product Serial No:">
            </div>
        @endslot
        <table class="table table-bordered table-striped" id="warranty_table">
            <thead>
                <tr>
                    <th>@lang( 'Invoice No' )</th>
                    <th>@lang( 'Invoice Date' )</th>
                    <th>@lang( 'Customer Name' )</th>
                    <th>@lang( 'Customer Address' )</th>
                    <th>@lang( 'Customer Phone' )</th>
                    <th>@lang( 'Product Name' )</th>
                    <th>@lang( 'Product Warranty Remain' )</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
            
        </table>
    @endcomponent

</section>
<!-- /.content -->
@stop

@section('javascript')
<script type="text/javascript">
    $(document).ready( function(){
        $(document).on('keyup','input.search_sn',function(){
            let search_value = $(this).val();
            $.ajax({
                url: "{{ route('checking_warrnty') }}",
                method:"GET",
                data: {search_value},
                success:function(res){
                    if(res.success){
                        $('tbody').html(res.html);
                    }
                }
            });
        });
    });
</script>
@endsection
