@extends('layouts.master')
@section('title','Users List')
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
@endsection
@section('page-header')
	<!-- breadcrumb -->
	<div class="breadcrumb-header justify-content-between">
		<div class="my-auto">
			<div class="d-flex">
				<h4 class="content-title mb-0 my-auto">Users & Permissions</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Users</span>
			</div>
		</div>
	</div>
	<!-- breadcrumb -->
@endsection
@section('content')
<!-- row opened -->
<div class="row row-sm">
	@if (session()->has('delete'))
		<div class="col-lg-4 col-md-4">
			<div class="card bd-0 mg-b-20 bg-danger-transparent alert p-0">
				<div class="card-header text-danger font-weight-bold">
					<i class="far fa-times-circle"></i> Delete User
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
		<div class="card mg-b-20">
            <div class="card-body">
				<div class="table-responsive">
					<table class="table text-md-nowrap" id="example1">
						<thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Created at</th>
                                <th>Updated at</th>
								<th>Actions</th>
                            </tr>
                        </thead>
						<tbody>
							@foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="arabic-text">{{ $user['name'] }}</td>
                                    <td>{{ $user['email'] }}</td>
									<td>{{ $user['created_at']->format('Y-m-d') }}</td>
									<td>{{ $user['updated_at']->format('Y-m-d') }}</td>
									<td>
										<a class="modal-effect btn btn-sm btn-danger mx-3" 
										data-effect="effect-rotate-bottom" 
										data-toggle="modal"
										data-id="{{$user['id']}}"
										data-name="{{$user['name']}}"
										href="#modaldemo"
										title="Delete"><i class="las la-trash" style="font-size: 20px;"></i></a>
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
<!--Delete modal-->
<div class="modal" id="modaldemo">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content tx-size-sm">
			<div class="modal-body tx-center pd-y-20 pd-x-20">
				<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button> <i class="icon icon ion-ios-close-circle-outline tx-100 tx-danger lh-1 mg-t-20 d-inline-block"></i>
				<h4 id="user_display" class="mg-b-20 arabic-text"></h4>
				<form id="deleteForm" method="POST">
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
<!--Internal Datatable Actions js-->
<script>
	$('#modaldemo').on('show.bs.modal',function(event) {
		var button =$(event.relatedTarget)
		var id =button.data('id')
		var name =button.data('name')
		var modal =$(this)
		modal.find('#user_display').html(`<p class="tx-danger">: Are you sure about Deleting User</p> <p class="mt-3">${name}</p>`);
		var route = '{{ route("users.delete", ":user") }}';
		route = route.replace(':user', id);
		modal.find('#deleteForm').attr('action', route);
	})
</script>
@endsection