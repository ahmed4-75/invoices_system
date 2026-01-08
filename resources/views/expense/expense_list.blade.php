@extends('layouts.master')
@section('title','Expense List')
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
				<h4 class="content-title mb-0 my-auto">Sheets</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Expense List</span>
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
				<a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-fall" data-toggle="modal"href="#modaldemo1">Add Expense</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table key-buttons text-md-nowrap">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">expense number</th>
                                <th class="border-bottom-0">expense date</th>
                                <th class="border-bottom-0">creditor name</th>
                                <th class="border-bottom-0">expense value</th>
                                <th class="border-bottom-0">pay date</th>
                                <th class="border-bottom-0">status</th>
                                <th class="border-bottom-0">Attachments</th>
                                <th class="border-bottom-0">updated at</th>
                                <th class="border-bottom-0">Actions</th>
                                <th class="border-bottom-0">NOTES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$expense['expense_number']}}</td>
                                    <td>{{$expense['expense_date']->format('Y-m-d')}}</td>
                                    <td class="arabic-text">{{$expense['creditor_name']}}</td>
                                    <td class="text-primary"><strong>$ {{$expense['expense_value']}}</strong></td>
                                    <td>{{$expense['pay_date']->format('Y-m-d')}}</td>
                                    <td>
                                        <span class="@if($expense['value_status'] == 1)label text-success d-flex  @else label text-danger d-flex  @endif">
                                            <div class="@if($expense['value_status'] == 1)dot-label bg-success ml-1  @else dot-label bg-danger ml-1  @endif"></div>
                                            <strong>{{$expense['status']}}</strong>
                                        </span>
                                    </td>
                                    <td>
                                        @if (!empty($expense['file_name']))
                                        @php
                                            $files = explode(',', $expense['file_name'] ?? '');
                                        @endphp
                                        <div id="drop{{ $expense['id'] }}" class="drop">
                                            <button type="button" class="drop-btn" style="border:none;background:none;cursor:pointer;font-size:18px;color:#007bff;">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                            <div class="list" style="display:none;background:#f8f9fa;border:1px solid #ccc;padding:5px 10px;position:absolute;z-index:1000;min-width:120px;border-radius:5px;">
                                                @foreach ($files as $i => $file)
                                                    <div>
                                                        <a target="_blank" href="{{ route('expenseAttachments', ['creditorName' => $expense['creditor_name'],'fileName' => urldecode($file)]) }}">
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
                                    <td>{{$expense['updated_at']->format('Y-m-d')}}</td>
                                    <td>
                                        <a class="modal-effect btn btn-sm btn-warning" 
										data-effect="effect-rotate-bottom" 
										data-toggle="modal"
										data-id="{{$expense['id']}}"
										data-creditor_name="{{$expense['creditor_name']}}"
										data-pay_date="{{$expense['pay_date']->format('Y-m-d')}}"
										data-notes="{{$expense['notes']}}" 
										href="#modaldemo2"
										title="Edit"><i class="las la-pen" style="font-size: 20px;"></i></a>

										@if ($expense['value_status'] == 1)
										<a class="modal-effect btn btn-sm btn-danger mx-3" 
										data-effect="effect-rotate-bottom" 
										data-toggle="modal"
										data-id="{{$expense['id']}}"
										data-creditor_name="{{$expense['creditor_name']}}"
										href="#modaldemo3"
										title="Delete"><i class="las la-trash" style="font-size: 20px;"></i></a>
										@endif
                                    </td>
                                    <td class="arabic-text">{{$expense['notes']}}</td>
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
				<h6 class="modal-title">Add Expense</h6>
			</div>
			<form action="{{route('expense_list.store')}}"  method="POST" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
                    <div class="form-group">
                        <label for="expense_number">: Expense Number</label>
                        <input type="string" class="form-control" name="expense_number" id="expense_number" title="Enter Expense Number" placeholder="Expense Number" required>
                    </div>
                    <div class="form-group">
                        <label for="creditor_name">: Creditor Name</label>
						<input type="string" class="form-control arabic-text" name="creditor_name" id="creditor_name" title="Enter Creditor Name" placeholder="Creditor Name" required>
					</div>
                    <div class="row ">
                        <div class="col">
                            <label for="expense_value">: Expense Value</label>
                            <input type="decimal" class="form-control" name="expense_value" id="expense_value" title="Enter Expense Value" value="0.00" required>
                        </div>
                        <div class="col">
                            <label for="due_date">: Pay Date</label>
                            <input type="date" class="form-control" id="due_date" name="pay_date" required>
                        </div>
                    </div>
					<div class="form-group mt-4">
						<label for="notes">: Notes</label>
						<textarea class="form-control arabic-text" name="notes" id="notes" rows="6" title="Enter the Notes" placeholder="NOTES" required></textarea>
					</div>
                    <div class="form-group mt-4">
                        <div for="attaches" class="text-center text-primary"><b>ATTACHMENTS</b></div>
                        <input type="file" name="attaches[]" id="attaches" class="dropify" accept=".pdf,.jpg,.png,.image/jpeg,.image/png" data-height="200" multiple>
                        <div class="text-success"> format Attachments>> pdf, jpeg , jpg , png *</div>
                    </div>
				</div>
				<div class="modal-footer">
					<button class="btn ripple btn-success mx-5" id="saveBtn" type="submit">Save</button>
					<button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Add modal -->
<!--Edit model-->
<div class="modal" id="modaldemo2">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
                <h2 id="creditorName_display"></h2>
			</div>
			<form id="editForm" method="POST" enctype="multipart/form-data">
				@csrf
				@method('PUT')
				<div class="modal-body">
                    <div class="form-group">
                        <label for="due_date">: Pay Date</label>
                        <input type="date" class="form-control" id="due_date" name="pay_date" required>
                    </div>
					<div class="form-group">
						<label for="notes">: Notes</label>
						<textarea class="form-control arabic-text" name="notes" id="notes" rows="6" title="Enter the Notes" placeholder="NOTES" required></textarea>
					</div>
                    <div class="form-group">
                        <div for="attaches" class="text-center text-primary"><b>ATTACHMENTS</b></div>
                        <input type="file" name="attaches[]" id="attaches" class="dropify" accept=".pdf,.jpg,.png,.image/jpeg,.image/png" data-height="200" multiple>
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
<div class="modal" id="modaldemo3">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content tx-size-sm">
			<div class="modal-body tx-center pd-y-20 pd-x-20">
				<i class="icon icon ion-ios-close-circle-outline tx-100 tx-danger lh-1 mg-t-20 d-inline-block"></i>
				<h4 id="expense_display" class="mg-b-20 arabic-text"></h4>
				<form class="mt-5" id="deleteForm" method="POST">
					@method('DELETE')
					@csrf
					<button class="btn ripple btn-danger pd-x-25 mx-5" type="submit">Delete</button>
					<button aria-label="Close" class="btn ripple btn-secondary pd-x-25 mx-5" data-dismiss="modal">Close</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- End Delete modal -->
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
<!--Internal  Datatable js -->
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
	$('#modaldemo2').on('show.bs.modal',function(event) {
		var button =$(event.relatedTarget)
		var id =button.data('id')
		var creditor_name =button.data('creditor_name')
		var pay_date =button.data('pay_date')
		var notes =button.data('notes')
		var modal =$(this)
        modal.find('#creditorName_display').html(`Edit Expense <span class="tx-primary arabic-text">${creditor_name}</span>`);
		modal.find('.modal-body #due_date').val(pay_date);
		modal.find('.modal-body #notes').val(notes);
		var route = '{{ route("expense_list.update", ":id") }}';
		route = route.replace(':id', id);
		modal.find('#editForm').attr('action', route);
	})
</script>
<script>
	$('#modaldemo3').on('show.bs.modal',function(event) {
		var button =$(event.relatedTarget)
		var id =button.data('id')
		var creditor_name =button.data('creditor_name')
		var modal =$(this)
		modal.find('#expense_display').html(`<p class="tx-danger">: Are you sure about Deleting Expense</p> <p class="mt-3">${creditor_name}</p>`);
		var route = '{{ route("expense_list.destroy", ":id") }}';
		route = route.replace(':id', id);
		modal.find('#deleteForm').attr('action', route);
	})
</script>
<script>
	document.addEventListener('DOMContentLoaded', function () {

		let saveBtn = document.getElementById('saveBtn');
		let form    = document.querySelector('#modaldemo1 form');

		saveBtn.addEventListener('click', function (event) {

			event.preventDefault(); // منع الإرسال مباشرة

			if (confirm("Are you sure you want to SAVE this EXPENSE?")) {
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
