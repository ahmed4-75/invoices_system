@extends('layouts.master')
@section('title','Sections')
@section('css')
<!---Internal Owl Carousel css-->
<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet">
<!---Internal  Multislider css-->
<link href="{{URL::asset('assets/plugins/multislider/multislider.css')}}" rel="stylesheet">
<!--- Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">

<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">

<!-- Internal Arabic Font td css-->
<link href="{{ asset('assets/plugins/datatable/css/arabic-font.css') }}" rel="stylesheet">
@endsection
@section('page-header')
	<!-- breadcrumb -->
	<div class="breadcrumb-header justify-content-between">
		<div class="my-auto">
			<div class="d-flex">
				<h4 class="content-title mb-0 my-auto">Goods</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Sections</span>
			</div>
		</div>
	</div>
	<!-- breadcrumb -->
@endsection
@section('content')
<!-- row opened-->
<div class="row mb-3">
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

	<!--div-->
	<div class="col-xl-12">
		<div class="card">
			<div class="col-sm-6 col-md-4 col-xl-3 mg-t-20">
				<a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-fall" data-toggle="modal" href="#modaldemo1">Add Section</a>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table text-md-nowrap" id="example1">
						<thead>
							<tr>
								<th class="border-bottom-0">#</th>
								<th class="border-bottom-0">Section Name</th>
								<th class="border-bottom-0">Description</th>
								<th class="border-bottom-0">Created at</th>
								<th class="border-bottom-0">status</th>
								<th class="border-bottom-0">Updated at</th>
								<th class="border-bottom-0">Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($sections as $section)	
								<tr>
									<td class="arabic-text">{{$loop->iteration}}</td>
									<td class="arabic-text">{{$section['section_name']}}</td>
									<td class="arabic-text">{{$section['description']}}</td>
									<td>{{$section['created_at']->format('Y-m-d ')}}</td>
									<td>
                                        <span class="@if($section['status'] == 'Unempty')label text-success d-flex @else label text-danger d-flex  @endif">
											<div class="@if($section['status'] == 'Unempty')dot-label bg-success ml-1 @else dot-label bg-danger ml-1  @endif"></div>
											<strong>{{ $section['status'] }}</strong>
										</span>
                                    </td>
									<td>{{$section['updated_at']->format('Y-m-d ')}}</td>
									<td>
										<a class="modal-effect btn btn-sm btn-warning" 
										data-effect="effect-rotate-bottom" 
										data-toggle="modal"
										data-id="{{$section['id']}}"
										data-section_name="{{$section['section_name']}}"
										data-description="{{$section['description']}}" 
										href="#modaldemo2"
										title="Edit"><i class="las la-pen" style="font-size: 20px;"></i></a>

										@if ($section['status'] == 'Empty')
										<a class="modal-effect btn btn-sm btn-danger mx-3" 
										data-effect="effect-rotate-bottom" 
										data-toggle="modal"
										data-id="{{$section['id']}}"
										data-section_name="{{$section['section_name']}}"
										href="#modaldemo3"
										title="Delete"><i class="las la-trash" style="font-size: 20px;"></i></a>
										@endif
									</td>
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
				<h6 class="modal-title">Add Section</h6>
			</div>
			<form action="{{route('sections.store')}}" method="POST">
				@csrf
				<div class="modal-body">
					<div class="form-group">
						<label for="exampleInputEmail1">Section Name : </label>
						<input type="string" class="form-control arabic-text" name="section_name" id="section_name" required>
					</div>
					<div class="form-group">
						<label for="description">Description : </label>
						<textarea class="form-control arabic-text" name="description" id="description" rows="6" required></textarea>
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
<!-- End Add modal -->
<!--Edit model-->
<div class="modal" id="modaldemo2">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
				<h6 class="modal-title">Edit Section</h6>
			</div>
			<form id="editForm" method="POST">
				@csrf
				@method('PUT')
				<div class="modal-body">
					<div class="form-group">
						<label for="section_name" class="col-form-label">Section Name : </label>
						<input type="string" class="form-control arabic-text" name="section_name" id="section_name">
					</div>
					<div class="form-group">
						<label for="description">Description : </label>
						<textarea class="form-control arabic-text" name="description" id="description" rows="6"></textarea>
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
				<h4 id="section_display" class="mg-b-20 arabic-text"></h4>
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
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')
<!--Internal  Datepicker js -->
<script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
<!-- Internal Select2 js-->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!-- Internal Modal js-->
<script src="{{URL::asset('assets/js/modal.js')}}"></script>

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
<!-- Internal Chart js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<!--Internal  Datatable js   #example-->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!--Internal Datatable Actions js-->
<script>
	$('#modaldemo2').on('show.bs.modal',function(event) {
		var button =$(event.relatedTarget)
		var id =button.data('id')
		var section_name =button.data('section_name')
		var description =button.data('description')
		var modal =$(this)
		modal.find('.modal-body #section_name').val(section_name);
		modal.find('.modal-body #description').val(description);
		var route = '{{ route("sections.update", ":id") }}';
		route = route.replace(':id', id);
		modal.find('#editForm').attr('action', route);
	})
</script>
<script>
	$('#modaldemo3').on('show.bs.modal',function(event) {
		var button =$(event.relatedTarget)
		var id =button.data('id')
		var section_name =button.data('section_name')
		var modal =$(this)
		modal.find('#section_display').html(`<p class="tx-danger">: Are you sure about Deleting Section</p> <p class="mt-3">${section_name}</p>`);
		var route = '{{ route("sections.destroy", ":id") }}';
		route = route.replace(':id', id);
		modal.find('#deleteForm').attr('action', route);
	})
</script>
@endsection