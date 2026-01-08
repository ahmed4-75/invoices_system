@extends('layouts.master')
@section('title','Receipts List')
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> --}}
<!---Internal Fileupload css-->
<link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
<!-- Internal Arabic Font td css-->
<link href="{{ asset('assets/plugins/datatable/css/arabic-font.css') }}" rel="stylesheet">
<!-- Internal input-elements due_date css -->
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
	<!-- breadcrumb -->
	<div class="breadcrumb-header justify-content-between">
		<div class="my-auto">
			<div class="d-flex">
				<h4 class="content-title mb-0 my-auto">Sheets</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Receipts List</span>
			</div>
		</div>
	</div>
	<!-- breadcrumb -->
@endsection
@section('content')
<!-- row opened -->
<div class="row row-sm">
	<!--div-->
	@if (session()->has('add'))
		<div class="col-lg-4 col-md-4">
			<div class="card bd-0 mg-b-20 bg-success-transparent alert p-0">
				<div class="card-header text-success font-weight-bold">
					<i class="far fa-check-circle"></i> Add Data
					<button aria-label="Close" class="close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button>
				</div>
				<div class="card-body text-success">
					<strong>{{session()->get('add')}}</strong>
				</div>
			</div>
		</div>
	@endif
	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<div class="col-xl-12">
		<div class="card">
			<div class="col-sm-6 col-md-4 col-xl-3 mg-t-20">
				<a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-fall" data-toggle="modal"
					href="#modaldemo1">Add Receipt</a>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table text-md-nowrap" id="example">
						<thead>
							<tr>
								<th class="border-bottom-0">#</th>
								<th class="border-bottom-0">invoice number</th>
								<th class="border-bottom-0">customer name</th>
								<th class="border-bottom-0">Receipt Value</th>
								<th class="border-bottom-0">Receipt Date</th>
								<th class="border-bottom-0">Attachments</th>
								<th class="border-bottom-0">Total Due</th>
								<th class="border-bottom-0">Due Date</th>
								<th class="border-bottom-0">NOTES</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($receipts as $receipt)
								<tr>
									<td>{{$loop->iteration}}</td>
									<td>{{$receipt['invoice']['invoice_number']}}</td>
									<td class="arabic-text">{{$receipt['invoice']['customer_name']}}</td>
									<td class="text-primary"><strong>$ {{$receipt['value']}}</strong></td>
									<td>{{$receipt['created_at']->format('Y-m-d')}}</td>
									<td> 
										@if (!empty($receipt['file_name']))
											@php
												$files = explode(',', $receipt['file_name'] ?? '');
											@endphp
											<div id="drop{{ $receipt['id'] }}" class="drop">
												<button type="button" class="drop-btn" style="border:none;background:none;cursor:pointer;font-size:18px;color:#007bff;">
													<i class="fas fa-chevron-down"></i>
												</button>
												<div class="list" style="display:none;background:#f8f9fa;border:1px solid #ccc;padding:5px 10px;position:absolute;z-index:1000;min-width:120px;border-radius:5px;">
													@foreach ($files as $i => $file)
														<div>
															<a target="_blank" href="{{ route('receiptAttachments', ['customerName' => $receipt['invoice']['customer_name'],'fileName' => urldecode($file)]) }}">
																Attache {{ $i + 1 }}
															</a>
														</div>
													@endforeach
												</div>
											</div>
										@else
											<strong> No Attachments </strong>
										@endif
									</td>
									<td><strong>$ {{$receipt['due_value']}}</strong></td>
									<td>{{$receipt['invoice']['due_date']->format('Y-m-d')}}</td>
									<td class="arabic-text">{{$receipt['invoice']['notes']}}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!--/div-->
</div>
<!-- row closed -->
<!-- Add modal -->
<div class="modal" id="modaldemo1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
				<h6 class="modal-title">Add Receipt</h6>
			</div>
			<form action="{{route('receipts_list.store')}}"  method="POST" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="form-group">
						<label>Invoice Number :</label>
						<input type="text" id="search_invoice_number" class="form-control" placeholder="Enter invoice number">
					</div>
					<div class="form-group">
						<label>Customer Name :</label>
						<input type="string" id="search_customer_name" class="form-control arabic-text" placeholder="Enter customer name">
					</div>
					<button type="button" id="searchBtn" class="btn btn-primary btn-block">Search Invoice</button> 
					<hr>
					<div id="invoiceData" style="display:none;">

						<input type="hidden" name="id" id="id">
					
						<div class="form-group">
							<label for="invoice_number">Invoice Number :</label>
							<input type="string" id="invoice_number" class="form-control" readonly>
						</div>
					
						<div class="form-group">
							<label for="customer_name">Customer Name :</label>
							<input type="string" id="customer_name" class="form-control arabic-text" readonly>
						</div>
					
						<div class="form-group">
							<label for="total_value">Invoice Value :</label>
							<input type="dicmal" id="total_value" class="form-control" readonly>
						</div>
					
						<div class="form-group">
							<label for="due_value">Due Value :</label>
							<input type="dicmal" id="due_value" class="form-control" readonly>
						</div>
					
						<div class="form-group">
							<label for="due_date">Due Date :</label>
							<input type="date" id="due_date" class="form-control" readonly>
						</div>
					
						<div class="form-group">
							<label for="status">Status :</label>
							<input type="string" id="status" class="form-control" readonly>
						</div>					
					</div>
					<div class="form-group">
						<label for="value">Receiptt Value :</label>
						<input type="dicemal" id="value" name="value" value="0.00" class="form-control" placeholder="Enter Receipt Value">
					</div>	
					<div class="col-sm-12 col-md-12 mt-4">
						<div for="attaches" class="text-center text-primary"><b>ATTACHMENTS</b></div>
						<input type="file" name="attaches[]" id="attaches" class="dropify" accept=".pdf,.jpg,.png,.image/jpeg,.image/png" data-height="150" multiple>
						<div class="text-success"> format Attachments>> pdf, jpeg , jpg , png *</div>
					</div>							
				</div>
				<div class="modal-footer">
					<button class="btn ripple btn-success mx-5" type="submit" id="saveBtn">Save</button>
					<button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Add modal -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js   #example-->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!--Internal  Datepicker js -->
<script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
<!-- Internal Select2 js-->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!--Internal Fileuploads js-->
<script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<!-- Internal Chart js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<!-- Internal input-elements due_date js -->
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/flatpickr/flatpickr-init.js') }}"></script>
<script>
	$(document).ready(function() {
		$('#searchBtn').click(function () {

			let invNumber = $('#search_invoice_number').val().trim();
			let custName  = $('#search_customer_name').val().trim();

			if(invNumber === "" || custName === ""){
				alert("Please enter invoice number and customer name.");
				return;
			}

			$.ajax({
				url: "{{ route('search_invoice') }}",
				type: "GET",
				data: {
					invoice_number: invNumber,
					customer_name: custName
				},
				success: function(response) {

					if(response.error){
						alert(response.message);
						$('#invoiceData').hide();
						return;
					}

					let data = response.data;

					let date = new Date(data.due_date);
					let formatted = date.toLocaleDateString('en-CA'); // YYYY-MM-DD

					let dueValue = data.receipts && data.receipts.length > 0
						? data.receipts[0].due_value
						: data.total_value;

					$('#id').val(data.id);
					$('#invoice_number').val(data.invoice_number);
					$('#customer_name').val(data.customer_name);
					$('#total_value').val(data.total_value);
					$('#due_value').val(dueValue);
					$('#due_date').val(formatted);
					$('#status').val(data.status);

					$('#invoiceData').show();
				},
				error: function(xhr) {
					alert("Error fetching invoice: " + xhr.status + " " + xhr.statusText);
					$('#invoiceData').hide();
				}
			});
		});
	});
</script>
<script>
	document.addEventListener('DOMContentLoaded', function () {

		let saveBtn = document.getElementById('saveBtn');
		let form    = document.querySelector('#modaldemo1 form');

		saveBtn.addEventListener('click', function (event) {

			event.preventDefault(); // منع الإرسال مباشرة

			if (confirm("Are you sure you want to SAVE this RECEIPT?")) {
				form.submit(); // إذا وافق → يتم حفظ البيانات
			}
		});

	});
</script>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.drop').forEach(function(drop) {
			let btn  = drop.querySelector('.drop-btn');
			let list = drop.querySelector('.list');
	
			if (!btn || !list) return;
			// إخفاء البداية
			list.style.display = 'none';
	
			btn.addEventListener('click', function(event) {
				event.stopPropagation();
	
				// Toggle
				let isOpen = list.style.display === 'block';
	
				list.style.display = isOpen ? 'none' : 'block';
			});	
		});
		// إغلاق عند الضغط خارج أي drop
		document.addEventListener('click', function(e) {
			document.querySelectorAll('.drop .list').forEach(function(list){
				if (list.style.display === 'block') {
					list.style.display = 'none';
				}
			});
		});
	});
</script>
@endsection