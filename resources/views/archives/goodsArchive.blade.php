@extends('layouts.master')
@section('title', 'Goods Archive')
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
                <h4 class="content-title mb-0 my-auto">Archives</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Goods Archive</span>
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
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">PRODUCTS ARCHIVE</h4>
                    <i class="mdi mdi-dots-horizontal text-gray"></i>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-md-nowrap" id="example2">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">product name</th>
                                <th class="border-bottom-0">value</th>
                                <th class="border-bottom-0">section name</th>
                                <th class="border-bottom-0">created_at</th>
                                <th class="border-bottom-0">updated_at</th>
                                <th class="border-bottom-0">Actions</th>
                                <th class="border-bottom-0">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="arabic-text">{{ $product['product_name'] }}</td>
                                    <td class="text-primary">$ {{ $product['value'] }}</td>
                                    <td class="arabic-text">{{$product['section']['section_name']}}</td>   
                                    <td>{{ $product['created_at']->format('Y-m-d ') }}</td>
									<td>{{ $product['updated_at']->format('Y-m-d ') }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-success" 
                                            href="{{route('product_restore',['id' => $product['id']])}}">RESTORE</a>
                                        
                                        <a class="modal-effect btn btn-sm btn-danger mx-3"
                                        data-effect="effect-rotate-bottom" 
                                        data-toggle="modal"
                                        data-id="{{$product['id']}}"
                                        data-product_name="{{$product['product_name']}}"
                                        href="#modaldemo1" >DELETE</a>
                                    </td>
                                    <td class="arabic-text">{{ $product['description'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!-- bd -->
        </div><!-- bd -->
    </div>
    <!--/div-->
    <!--div-->
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">SECTIONS ARCHIVE</h4>
                    <i class="mdi mdi-dots-horizontal text-gray"></i>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-md-nowrap" id="example1">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">section name</th>
                                <th class="border-bottom-0">Description</th>
                                <th class="border-bottom-0">created_at</th>
                                <th class="border-bottom-0">updated_at</th>
                                <th class="border-bottom-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sections as $section)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="arabic-text">{{ $section['section_name'] }}</td>
                                    <td class="arabic-text">{{ $section['description'] }}</td>
                                    <td>{{ $section['created_at']->format('Y-m-d ') }}</td>
                                    <td>{{ $section['updated_at']->format('Y-m-d ') }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-success" 
                                            href="{{route('section_restore',['id' => $section['id']])}}">RESTORE</a>
                                        
                                        <a class="modal-effect btn btn-sm btn-danger mx-3"
                                        data-effect="effect-rotate-bottom" 
                                        data-toggle="modal"
                                        data-id="{{$section['id']}}"
                                        data-section_name="{{$section['section_name']}}"
                                        href="#modaldemo2" >DELETE</a>
                                    </td>
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
				<h4 id="product_display" class="mg-b-20 arabic-text"></h4>
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
<!--Delete modal-->
<div class="modal" id="modaldemo2">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content tx-size-sm">
			<div class="modal-body tx-center pd-y-20 pd-x-20">
				<i class="icon icon ion-ios-close-circle-outline tx-100 tx-danger lh-1 mg-t-20 d-inline-block"></i>
				<h4 id="section_display" class="mg-b-20 arabic-text"></h4>
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
		var product_name =button.data('product_name')
		var modal =$(this)
		modal.find('#product_display').html(`<p class="tx-danger">: Are you sure about deleting Product</p> <p class="mt-3">${product_name}</p>`);
        var route = '{{ route("product_destroy", ":id") }}';
		route = route.replace(':id', id);
		modal.find('#deleteForm').attr('action', route);
	})
</script>
<script>
	$('#modaldemo2').on('show.bs.modal',function(event) {
		var button =$(event.relatedTarget)
		var id =button.data('id')
		var section_name =button.data('section_name')
		var modal =$(this)
		modal.find('#section_display').html(`<p class="tx-danger">: Are you sure about deleting Section</p> <p class="mt-3">${section_name}</p>`);
        var route = '{{ route("section_destroy", ":id") }}';
		route = route.replace(':id', id);
		modal.find('#deleteForm').attr('action', route);
	})
</script>
@endsection