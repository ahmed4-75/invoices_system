@extends('layouts.master')
@section('title','Invoices List')
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<!---Internal Fileupload css-->
<link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
<!---Internal Delete Modal Width-->
<style>.custom-width-modal {max-width: 700px !important;}</style>
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
				<h4 class="content-title mb-0 my-auto">Sheets</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Invoices List</span>
			</div>
		</div>
	</div>
	<!-- breadcrumb -->
@endsection
@section('content')
<div class="row row-sm">
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
	@if (session()->has('edit'))
		<div class="col-lg-4 col-md-4">
			<div class="card bd-0 mg-b-20 bg-info-transparent alert p-0">
				<div class="card-header text-info font-weight-bold">
					<i class="far fa-check-circle"></i> Edit Data
					<button aria-label="Close" class="close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button>
				</div>
				<div class="card-body text-info">
					<strong>{{ session()->get('edit') }}</strong>
				</div>
			</div>
		</div>
	@endif
	@if (session()->has('delete'))
		<div class="col-lg-4 col-md-4">
			<div class="card bd-0 mg-b-20 bg-danger-transparent alert p-0">
				<div class="card-header text-danger font-weight-bold">
					<i class="far fa-times-circle"></i> Delete Data
					<button aria-label="Close" class="close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button>
				</div>
				<div class="card-body text-danger">
					<strong>{{ session()->get('delete') }}</strong>
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
</div>
<!-- row opened -->
<div class="row row-sm">
	<!--div-->
	<div class="col-xl-12">
		<div class="card mg-b-20">
			<div class="col-sm-6 col-md-4 col-xl-3 mg-t-20">
				<a class="btn btn-outline-primary btn-block" href="{{ route('invoices_list.create') }}">Create Invoice</a>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="example" class="table key-buttons text-md-nowrap">
						<thead>
							<tr>
								<th class="border-bottom-0">#</th>
								<th class="border-bottom-0">invoice number</th>
								<th class="border-bottom-0">invoice date</th>
								<th class="border-bottom-0">customer name</th>
								<th class="border-bottom-0">total value</th>
								<th class="border-bottom-0">status</th>
								<th class="border-bottom-0">updated at</th>
								<th class="border-bottom-0">due date</th>
								<th class="border-bottom-0">actions</th>
								<th class="border-bottom-0">NOTES</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($invoices as $invoice)
								<tr>
									<td>{{$loop->iteration}}</td>
									<td>{{$invoice['invoice_number']}}</td>
									<td>{{$invoice['invoice_date']->format('Y-m-d')}}</td>
									<td class="arabic-text">{{$invoice['customer_name']}}</td>
									<td class="text-primary"><strong>$ {{$invoice['total_value']}}</strong></td>
									<td>
										<span class="@if($invoice['value_status'] == 2)label text-success d-flex  @elseif($invoice['value_status'] == 0)label text-danger d-flex  @else label text-info d-flex  @endif">
											<div class="@if($invoice['value_status'] == 2)dot-label bg-success ml-1  @elseif($invoice['value_status'] == 0)dot-label bg-danger ml-1  @else dot-label bg-info ml-1  @endif"></div>
											<strong>{{$invoice['status']}}</strong>
										</span>
									</td>									
									<td>{{$invoice['updated_at']->format('Y-m-d')}}</td>
									<td>{{$invoice['due_date']->format('Y-m-d')}}</td>
									<td>
										<a class="modal-effect btn btn-sm btn-secondary" 
											href="{{route('invoices_list.show',['invoices_list'=>$invoice['id']])}}"
											title="Details"><i class="las la-file-invoice" style="font-size: 15px;"></i></a>

										@if ($invoice['value_status'] == 1 or $invoice['value_status'] == 0)
										<a class="modal-effect btn btn-sm btn-warning mx-3"
											data-toggle="modal"
											data-id="{{$invoice['id']}}"
											data-customer_name ="{{$invoice['customer_name']}}"
											data-invoice_value="{{$invoice['invoice_value']}}"
											data-vat_value="{{$invoice['vat_value']}}"
											data-discount_value="{{$invoice['discount_value']}}"
											data-total_value="{{$invoice['total_value']}}"
											data-due_date="{{$invoice['due_date']->format('Y-m-d')}}"
											data-notes="{{$invoice['notes']}}"
											href="#modaldemo1"
											title="Edit"><i class="las la-pen" style="font-size: 15px;"></i></a>
										@endif
		
										<a class="modal-effect btn btn-sm btn-danger"
											data-toggle="modal"
											data-id ="{{$invoice['id']}}"
											data-invoice_number ="{{$invoice['invoice_number']}}"
											data-customer_name ="{{$invoice['customer_name']}}"
											href="#modaldemo2"
											title="Delete"><i class="las la-trash" style="font-size: 15px;"></i></a>
									</td>
									<td class="arabic-text">{{$invoice['notes']}}</td>
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
<!--Edit model-->
<div class="modal" id="modaldemo1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
				<h2 id="username_display"></h2>
			</div>
			<form class="mt-5" id="editForm" method="POST" enctype="multipart/form-data">
				@csrf
				@method('PUT')
				<div class="modal-body">
					<div class="row">
						<div class="col">
							<label for="discount" class="control-label">% Discount Percentage</label>
							<input type="string" id="discount" value=0.00 class="form-control" title="Enter Discount Percentage"  required>
						</div>
						<div class="col">
							<label for="vat" class="control-label">% VAT Percentage</label>
							<input type="string" id="vat" value=0.00 class="form-control" title="Enter VAT Percentage" required>
						</div>
					</div>
					<div class="row mt-2">
						<div class="col">
							<label for="discount_value" class="control-label">Discount Value</label>
							<input type="decimal" class="form-control" id="discount_value" name="discount_value" readonly>
						</div>
						<div class="col">
							<label for="vat_value" class="control-label">Vat Value</label>
							<input type="decimal" class="form-control" id="vat_value" name="vat_value" readonly>
						</div>
					</div>
					<div class="col mt-4">
						<label for="total_value" class="control-label">Total Invoice Value</label>
						<input type="decimal" class="form-control" id="total_value" name="total_value" readonly>
					</div>

					@role('Accountant')
					<div class="col mt-1 text-danger"><strong>. You are not Authorized to UPDATE Fields that are at the Top</strong></div><hr>
					@endrole
					
					<div class="col  mt-4">
						<label for="due_date">Due Date</label>
						<input type="date" class="form-control" id="due_date" name="due_date" placeholder="YYYY-MM-DD" required>
					</div>
					<div class="col mt-4 text-center">
						<div for="notes" class="control-label text-primary"><b>NOTES</b></div>
						<textarea class="form-control arabic-text" id="notes" name="notes" rows="3"></textarea>
					</div>
					<div class="col-sm-12 col-md-12 mt-4">
						<div for="attaches" class="text-center text-primary"><b>Add ATTACHMENTS</b></div>
						<input type="file" name="attaches[]" id="attaches" class="dropify" accept=".pdf,.jpg,.png,.image/jpeg,.image/png" data-height="100" multiple>
						<div class="text-success"> format Attachments>> pdf, jpeg , jpg , png *</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn ripple btn-success mx-5" type="submit">Save</button>
					<button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--End Edit model-->
<!--Delete modal-->
<div class="modal" id="modaldemo2">
	<div class="modal-dialog modal-dialog-centered custom-width-modal" role="document">
		<div class="modal-content tx-size-sm">
			<div class="modal-body tx-center pd-y-20 pd-x-20">
				<i class="icon icon ion-ios-close-circle-outline tx-100 tx-danger lh-1 mg-t-20 d-inline-block"></i>
				<div id="name_number_display" class="mg-b-20"></div>
				<form class="mt-5" id="deleteForm" method="POST">
					@method('DELETE')
					@csrf
					<button aria-label="Close" class="btn ripple btn-danger pd-x-25 mx-5" type="submit">Delete</button>
					<button aria-label="Close" class="btn ripple btn-secondary pd-x-25 mx-5" data-dismiss="modal">Close</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- End Delete modal -->
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
<!--Internal Datatable Actions js-->
<script>
    // دالة لإعادة الحسابات
    function recalculateValues() {
        let invoice_value = parseFloat($('#editForm').data('invoice_value')); // القيمة الأصلية
        let discount_percent = parseFloat($('#discount').val()) || 0;
        let vat_percent = parseFloat($('#vat').val()) || 0;
        // الحسابات
        let discount_value = (invoice_value * discount_percent) / 100;
        let vat_value = (invoice_value * vat_percent) / 100;
        let total_value = invoice_value + vat_value - discount_value;
        // إظهار النتائج
        $('#discount_value').val(discount_value.toFixed(2));
        $('#vat_value').val(vat_value.toFixed(2));
        $('#total_value').val(total_value.toFixed(2));
    }
    // عند فتح المودال
    $('#modaldemo1').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);

        var id = button.data('id');
        var customer_name = button.data('customer_name');
        var invoice_value = parseFloat(button.data('invoice_value'));
        var discount_value = parseFloat(button.data('discount_value'));
        var vat_value = parseFloat(button.data('vat_value'));
        var total_value = parseFloat(button.data('total_value'));
        var due_date = button.data('due_date');
        var notes = button.data('notes');

        var modal = $(this);
        // تعبئة البيانات
        modal.find('#username_display').html(`Edit Invoice <span class="tx-primary arabic-text">${customer_name}</span>`);
        modal.find('#discount').val(((discount_value / invoice_value) * 100).toFixed(2));
        modal.find('#vat').val(((vat_value / invoice_value) * 100).toFixed(2));
        modal.find('#discount_value').val(discount_value.toFixed(2));
        modal.find('#vat_value').val(vat_value.toFixed(2));
        modal.find('#total_value').val(total_value.toFixed(2));
        modal.find('#due_date').val(due_date);
        modal.find('#notes').val(notes);
        // تخزين invoice_value ليستخدم في الحسابات
        $('#editForm').data('invoice_value', invoice_value);
        // تجهيز route
        var route = '{{ route("invoices_list.update", ":id") }}'.replace(':id', id);
        modal.find('#editForm').attr('action', route);
        // إجراء الحسابات فورًا عند فتح المودال
        recalculateValues();
    });
    // إعادة الحساب عند تغيير الخصم أو الضريبة
    $('#discount, #vat').on('input', function() {
        recalculateValues();
    });
</script>
<script>
	$('#modaldemo2').on('show.bs.modal',function(event) {
		var button =$(event.relatedTarget)
		var id =button.data('id')
		var invoice_number =button.data('invoice_number')
		var customer_name =button.data('customer_name')
		var modal =$(this)
		modal.find('#name_number_display').html(`
			<h3 class="tx-danger">: Are you sure about Deleting the Invoice and Receipts</h3> 
			<h4 class="mt-4">Customer Name : <span class="tx-primary">${customer_name}</span></h4>
			<h4 class="mt-2">Invoice Number : ${invoice_number}</h4>
		`);
		var route = '{{ route("invoices_list.destroy", ":id") }}';
		route = route.replace(':id', id);
		modal.find('#deleteForm').attr('action', route);
	})
</script>
@endsection