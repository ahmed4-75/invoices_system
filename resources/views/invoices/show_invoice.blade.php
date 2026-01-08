@extends('layouts.master')
@section('title','SHOW Invoice')
@section('css')
<!-- Internal Arabic Font td css-->
<link href="{{ asset('assets/plugins/datatable/css/arabic-font.css') }}" rel="stylesheet">
<style> @media print {
	#attache {display: none}
	#print_button {display: none}
	#send_button {display: none}
}</style>
@endsection
@section('page-header')
	<!-- breadcrumb -->
	<div class="breadcrumb-header justify-content-between">
		<div class="my-auto">
			<div class="d-flex">
				<h4 class="content-title mb-0 my-auto">Sheets</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ SHOW Invoice</span>
			</div>
		</div>
	</div>
				<!-- breadcrumb -->
@endsection
@section('content')
	<!-- row -->
	<div class="row row-sm">
		<div class="col-md-12 col-xl-12">
			<div class="main-content-body-invoice" id="print">
				<div class="card card-invoice">
					<div class="card-body">
						<div class="invoice-header">
							<h1 class="invoice-title">Invoice</h1>
						</div><!-- invoice-header -->
						<div class="row mg-t-20">
							<div class="col-md">
								<label class="tx-gray-600">Bill To Buyer</label>
								<div class="billed-to">
									<h6 class="arabic-text">{{$invoice['customer_name']}}</h6>
								</div>
							</div>
							<div class="col-md">
								<label class="tx-gray-600">Invoice Information</label>
								<p class="invoice-info-row"><span>Invoice No</span> <span>{{$invoice['invoice_number']}}</span></p>
								<p class="invoice-info-row"><span>Invoice Date:</span> <span>{{ $invoice['invoice_date']->format('Y-m-d')}}</span></p>
								<p class="invoice-info-row"><span>Due Date:</span> <span>{{ $invoice['due_date']->format('Y-m-d')}}</span></p>
							</div>
						</div>
						<div class="table-responsive mg-t-40">
							<table class="table table-invoice border text-md-nowrap mb-0">
								<thead>
									<tr>
										<th class="border-bottom-0">Section</th>
										<th class="border-bottom-0">Product</th>
										<th class="border-bottom-0">Description</th>
										<th class="border-bottom-0">Units</th>
										<th class="border-bottom-0">Unit Price</th>
										<th class="border-bottom-0">Total Price</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($invoice['products'] as $product)										
										<tr>
											<td class="arabic-text">{{$product['section']['section_name']}}</td>
											<td class="arabic-text">{{$product['product_name']}}</td>
											<td class="arabic-text">{{$product['description']}}</td>
											<td>{{$product['pivot']['units']}}</td>
											{{-- <td>{{ $product->pivot->units }}</td> --}}
											<td>$ {{number_format($product['value'],2,'.',' ')}}</td>
											<td>$ {{number_format($invoice['invoice_value'],2,'.',' ')}}</td>
										</tr>
									@endforeach

									<tr>
										<td class="valign-middle" colspan="3" rowspan="6"></td>										
									</tr>
									<tr>
										<td class="text-success" colspan="2">Discount</td>
										<td class="text-success" colspan="1">$ {{number_format($invoice['discount_value'],2,'.',' ')}} -</td>
									</tr>
									<tr>
										<td class="text-danger" colspan="2">Vat</td>
										<td class="text-danger" colspan="1">$ {{number_format($invoice['vat_value'],2,'.',' ')}} +</td>
									</tr>
									<tr>										
										<td colspan="2">Total Invoice Value</td>
										<td colspan="1"><strong>$ {{number_format($invoice['total_value'],2,'.',' ')}}</strong></td>
									</tr>
									<tr>
										<td colspan="2">Receipts</td>
										<td colspan="1">$ {{number_format($invoice['receiptsValue'],2,'.',' ')}} -</td>
									</tr>
									<tr>
										<td class="tx-uppercase tx-bold tx-inverse" colspan="2">Total Due</td>
										<td colspan="1">
											<h4 class="tx-primary tx-bold">$ {{number_format($invoice['totalDueValue'],2,'.',' ')}}</h4>
										</td>
									</tr>
									<tr>
										<td class="valign-middle" colspan="6" rowspan="3">
											<label class="main-content-label tx-13">Notes</label>
											<p class="arabic-text">{{$invoice['notes']}}</p>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="mb-2">
							@if (!empty($invoice['file_name']))
								@php
									$files = explode(',', $invoice['file_name'] ?? '');
									// dd($files);
								@endphp
								@foreach ($files as $i => $file)
								<a class="btn btn-purple float-left mt-3 mr-2"  target="_blank" id="attache"
									href="{{route('invoiceAttachments',['customerName' => $invoice['customer_name'],'fileName' => urldecode($file)])}}"
								> Attache ({{$i + 1}})</a>
								@endforeach
							@endif
							<div class="clearfix"></div>
						</div>
						<div class="mb-2">
							<a href="#" class="btn btn-success float-left mt-3 mr-2" id="print_button" onclick="printDiv()">
								<i class="mdi mdi-printer ml-1"></i>Print
							</a>
						</div>
					</div>
				</div>
			</div>
		</div><!-- COL-END -->
	</div>
	<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<script>
function printDiv(){
	var printContents = document.getElementById('print').innerHTML;
	var orignalContents = document.body.innerHTML;

	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = orignalContents;
}
</script>
@endsection