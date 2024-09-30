<?php

namespace App\Http\Controllers;

use App\Warranty;
use App\ProductImei;
use App\Transaction;
use App\TransactionSellLine;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;

class WarrantyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');

        if (request()->ajax()) {
            $warranties = Warranty::where('business_id', $business_id)
                         ->select(['id', 'name', 'description', 'duration', 'duration_type']);

            return Datatables::of($warranties)
                ->addColumn(
                    'action',
                    '<button data-href="{{action(\'App\Http\Controllers\WarrantyController@edit\', [$id])}}" class="btn btn-xs btn-primary btn-modal" data-container=".view_modal"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>'
                 )
                 ->removeColumn('id')
                 ->editColumn('duration', function ($row) {
                     return $row->duration.' '.__('lang_v1.'.$row->duration_type);
                 })
                 ->rawColumns(['action'])
                 ->make(true);
        }

        return view('warranties.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('warranties.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');

        try {
            $input = $request->only(['name', 'serial','description', 'duration', 'duration_type']);
            $input['business_id'] = $business_id;

            $status = Warranty::create($input);

            $output = ['success' => true,
                'msg' => __('lang_v1.added_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function show(Warranty $warranty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (request()->ajax()) {
            $warranty = Warranty::where('business_id', $business_id)->find($id);

            return view('warranties.edit')
                ->with(compact('warranty'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (request()->ajax()) {
            try {
                $input = $request->only(['name','serial','description', 'duration', 'duration_type']);

                $warranty = Warranty::where('business_id', $business_id)->findOrFail($id);

                $warranty->update($input);

                $output = ['success' => true,
                    'msg' => __('lang_v1.updated_success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }
    
    public function check_warranty(){
        return view('warranties.check_warranties');
    }
    
    public function checking_warrnty(Request $request){
        
        $imeiNumber = $request->search_value;
        $imeiIds = ProductImei::where('imei_number', $imeiNumber)->pluck('id');
        $imeiIdsJson = json_encode($imeiIds->map('strval')->toArray());
     
        $transactions = Transaction::select(
            'transactions.id as transaction_id',
            'transactions.transaction_date as transaction_date',
            'c.first_name as contact_name',
            'c.shipping_address as customer_address',
            'c.mobile as customer_mobile',
            'p.name as product_name',
            'tsl.warranty as product_warranty',
            DB::raw('DATEDIFF(CURDATE(), transactions.transaction_date) as days_diff')
        )
        ->leftJoin('transaction_sell_lines as tsl', 'tsl.transaction_id', '=', 'transactions.id')
        ->leftJoin('products as p', 'tsl.product_id', '=', 'p.id')
        ->leftJoin('contacts as c', 'c.id', '=', 'transactions.contact_id')
        ->leftJoin('product_imeis as imei', 'imei.id', '=', 'tsl.imei_id')
        ->whereRaw('JSON_CONTAINS(imei_id, ?)', [$imeiIdsJson])
        ->groupBy(
            'transactions.id',
            'c.first_name',
            'c.shipping_address',
            'c.mobile',
            'p.name'
        )
        ->get();
        
     
        // $transactions = Transaction::select(
        // 'transactions.id as transaction_id',
        // 'c.first_name as contact_name',
        // 'c.shipping_address as customer_address',
        // 'c.mobile as customer_mobile',
        // 'p.name as product_name',
        // 'tsl.warranty as product_warranty'
        // )
        // ->leftJoin('transaction_sell_lines as tsl', 'tsl.transaction_id', '=', 'transactions.id')
        // ->leftJoin('products as p', 'tsl.product_id', '=', 'p.id')
        // ->leftJoin('contacts as c', 'c.id', '=', 'transactions.contact_id')
        // ->leftJoin('product_imeis as imei', 'imei.id', '=', 'tsl.imei_id') // Join on imei_id
        // ->whereRaw('JSON_CONTAINS(imei_id, ?)', [$imeiIdsJson])
        // ->groupBy(
        //     'transactions.id',
        //     'c.first_name',
        //     'c.shipping_address',
        //     'c.mobile',
        //     'p.name'
        // )
        // ->get();
                    
        $html = view('warranties.warranty_tr',compact('transactions'))->render();  
        
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warranty $warranty)
    {
        //
    }
}
