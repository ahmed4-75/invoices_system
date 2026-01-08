@extends('layouts.master')
@section('title', 'Invoices Archive')
@section('css')
<!---Internal Owl Carousel css-->
<link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
<!---Internal  Multislider css-->
<link href="{{ URL::asset('assets/plugins/multislider/multislider.css') }}" rel="stylesheet">
<!--- Select2 css -->
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<!-- Internal Arabic Font td css-->
<link href="{{ asset('assets/plugins/datatable/css/arabic-font.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Archives</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Invoices Archive</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row row-sm">
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
    @if (session()->has('restore'))
		<div class="col-lg-4 col-md-4">
			<div class="card bd-0 mg-b-20 bg-success-transparent alert p-0">
				<div class="card-header text-success font-weight-bold">
					<i class="far fa-times-circle"></i> Restore Data
					<button aria-label="Close" class="close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button>
				</div>
				<div class="card-body text-success">
					<strong>{{ session()->get('delete') }}</strong>
				</div>
			</div>
		</div>
	@endif
    <!--div-->
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-md-nowrap" id="example2">
                        <thead>
                            <tr>
								<th class="border-bottom-0">#</th>
								<th class="border-bottom-0">invoice number</th>
								<th class="border-bottom-0">invoice date</th>
								<th class="border-bottom-0">customer name</th>
								<th class="border-bottom-0">total value</th>
								<th class="border-bottom-0">due date</th>
								<th class="border-bottom-0">updated at</th>
								<th class="border-bottom-0">Attachments</th>
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
									<td>{{$invoice['due_date']->format('Y-m-d')}}</td>
									<td>{{$invoice['updated_at']->format('Y-m-d')}}</td>
									<td>
                                        @if (!empty($invoice['file_name']))
                                        @php
                                            $files = explode(',', $invoice['file_name'] ?? '');
                                        @endphp
                                        <div id="drop{{ $invoice['id'] }}" class="drop">
                                            <button type="button" class="drop-btn" style="border:none;background:none;cursor:pointer;font-size:18px;color:#007bff;">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                            <div class="list" style="display:none;background:#f8f9fa;border:1px solid #ccc;padding:5px 10px;position:absolute;z-index:1000;min-width:120px;border-radius:5px;">
                                                @foreach ($files as $i => $file)
                                                    <div>
                                                        <a target="_blank" href="{{ route('invoiceAttachments', ['customerName' => $invoice['customer_name'],'fileName' => urldecode($file)]) }}">
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
									<td>
                                        <a class="btn btn-sm btn-success" 
                                            href="{{route('invoices_archive.restore',['id' => $invoice['id']])}}">RESTORE</a>
                                        
                                        <a class="modal-effect btn btn-sm btn-danger mx-3"
                                        data-effect="effect-rotate-bottom" 
                                        data-toggle="modal"
                                        data-id="{{$invoice['id']}}"
                                        data-customer_name="{{$invoice['customer_name']}}"
                                        href="#modaldemo1" >DELETE</a>
									</td>
									<td class="arabic-text">{{$invoice['notes']}}</td>
								</tr>
							@endforeach
                        </tbody>
                    </table>
                </div>
            </div><!-- bd -->
        </div><!-- bd -->
    </div>
    <!--/div-->
</div>
<!-- row closed -->
<!--Delete modal-->
<div class="modal" id="modaldemo1">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content tx-size-sm">
			<div class="modal-body tx-center pd-y-20 pd-x-20">
				<i class="icon icon ion-ios-close-circle-outline tx-100 tx-danger lh-1 mg-t-20 d-inline-block"></i>
				<h4 id="invoice_display" class="mg-b-20 arabic-text"></h4>
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
<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!-- Internal Select2 js-->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!-- Internal Modal js-->
<script src="{{ URL::asset('assets/js/modal.js') }}"></script>
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!-- Internal Chart js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<script>
	$('#modaldemo1').on('show.bs.modal',function(event) {
		var button =$(event.relatedTarget)
		var id =button.data('id')
		var customer_name =button.data('customer_name')
		var modal =$(this)
		modal.find('#invoice_display').html(`<p class="tx-danger">: Are you sure about deleting Invoice</p> <p class="mt-3">${customer_name}</p>`);
        var route = '{{ route("invoices_archive.destroy", ":id") }}';
		route = route.replace(':id', id);
		modal.find('#deleteForm').attr('action', route);
	})
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
