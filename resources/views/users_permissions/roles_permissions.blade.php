@extends('layouts.master')
@section('title','Roles & Permissions')
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
				<h4 class="content-title mb-0 my-auto">Users & Permissions</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Roles & Permissions</span>
			</div>
		</div>
	</div>
	<!-- breadcrumb -->
@endsection
@section('content')
<!-- row opened -->
<div class="row row-sm">
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created at</th>
								<th>Roles</th>
								<th>Permissions</th>
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
									<td>
										<ul style="margin:0; padding-left: 15px;">
											@foreach ($user['roles'] as $role)
												<li>{{ $role['name'] }}</li>
											@endforeach
										</ul>
									</td>
									<td>
										<ul style="margin:0; padding-left: 15px;">
											@foreach ($user['roles'] as $role)
												@foreach ($role['permissions'] as $permission)
													<li>{{ $permission['name'] }}</li>
												@endforeach
											@endforeach
										</ul>
									</td>
									<td>
										<a class="modal-effect btn btn-sm btn-warning" 
										data-effect="effect-rotate-bottom" 
										data-toggle="modal"
										data-id="{{ $user['id'] }}"
   										data-name="{{ $user['name'] }}"
   										data-roles='@json($user["roles"])'
										href="#modaldemo"
										title="Edit"><i class="las la-pen" style="font-size: 20px;"></i></a>
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
<!--Edit model-->
<div class="modal" id="modaldemo">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
				<h6 id="username_display" class="modal-title"> </h6>
			</div>
			<form id="editForm" method="POST">
				@csrf
				@method('PUT')
				<div class="modal-body">
					<h6>: Current Roles</h6>
					<ul id="current_roles"></ul>

					<h6>: Current Permissions</h6>
					<ul id="current_permissions"></ul> <hr>

					<h5>Select Roles</h5>
					<div class="form-group">
						<div class="row">
							@foreach ($roles as $role)
								<div class="col-md-4">
									<label>
										<input type="checkbox" class="role-checkbox"
											name="roles[]"
											value="{{ $role['id'] }}">
										{{ $role['name'] }}
									</label>
								</div>
							@endforeach
						</div>
					</div>   <hr>

					<h5>Select Permissions</h5>
					<div id="permissionsBox" class="row"></div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-success mx-5" type="submit">Save</button>
					<button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--End Edit model-->
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
$(document).ready(function(){
	// التعامل مع ظهور المودال (Edit Modal)
	$('#modaldemo').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		
		var user_id = button.data('id');
		var user_name = button.data('name');
		var user_roles_data = button.data('roles'); // قد تكون سلسلة JSON أو كائن/مصفوفة محولة تلقائياً
		var user_roles = [];
		// محاولة تحليل البيانات
		if (typeof user_roles_data === 'string') {
			try { 
				user_roles = JSON.parse(user_roles_data); 
			} catch(e){ 
				console.error("Failed parsing user roles JSON:", e);
				user_roles = []; 
			}
		} else if (Array.isArray(user_roles_data)) {
			user_roles = user_roles_data;
		} else {
			user_roles = [];
		}

		var modal = $(this);

		modal.find('#username_display').text('Edit Roles & Permissions OF ' + user_name);
		// 1. عرض الأدوار الحالية
		modal.find('#current_roles').html('');
		var roleIdsToSelect = [];
		user_roles.forEach(function(role){
			modal.find('#current_roles').append('<li>' + role.name + '</li>');
			roleIdsToSelect.push(role.id.toString());
		});
		// 2. عرض الصلاحيات الحالية (من جميع الأدوار)
		modal.find('#current_permissions').html('');
		var currentPermissionsHtml = '';
		var displayedPermissions = new Set(); // لمنع تكرار الصلاحيات

		user_roles.forEach(function(role){
			// تأكد أن 'permissions' موجودة ومصفوفة قبل التكرار
			if (role.permissions && Array.isArray(role.permissions)) {
				role.permissions.forEach(function(p){
					if (!displayedPermissions.has(p.name)) {
						currentPermissionsHtml += '<li>' + p.name + '</li>';
						displayedPermissions.add(p.name);
					}
				});
			}
		});
		modal.find('#current_permissions').html(currentPermissionsHtml);
		// 3. تعيين أكشن الفورم
		var route = '{{ route("rolesAndPermissions.update", ":id") }}';
		route = route.replace(':id', user_id);
		modal.find('#editForm').attr('action', route);
		// 4. تحديد الأدوار في الـ Checkboxes
		// إزالة أي تحديد سابق لجميع الأدوار
		$('.role-checkbox').prop('checked', false);
		// إعادة تحديد الأدوار الحالية
		roleIdsToSelect.forEach(function(roleId){
			$('.role-checkbox[value="' + roleId + '"]').prop('checked', true);
		});
		// جلب الصلاحيات المرتبطة بالأدوار المحددة حديثًا
		// (سيتم تشغيل الدالة change التي تقوم بـ AJAX)
		$('.role-checkbox').first().trigger('change');
	}); // إغلاق دالة show.bs.modal
	// 5. التعامل مع تغيير اختيار الأدوار (AJAX لجلب الصلاحيات)
	$(document).on('change', '.role-checkbox', function() {
		var selectedRoles = [];

		$('.role-checkbox:checked').each(function(){
			selectedRoles.push($(this).val());
		});

		var box = $('#permissionsBox');
		box.html('');

		if (selectedRoles.length === 0) return;

		$.ajax({
			url: "{{ route('get_permissions') }}",
			type: "GET",
			data: { role_ids: selectedRoles },
			success: function (response) {
				// ✅ النقطة المشابهة المطلوبة: قراءة المصفوفة من مفتاح 'data'
				var perms = response || [];				
				var html = '';
				
				perms.forEach(function(p){
                // يتم عرض الصلاحيات كـ checked بشكل افتراضي
                html += '<div class="col-md-4">';
                html += '<label><input type="checkbox" name="permissions[]" value="' + p.id + '" checked> ' + p.name + '</label>'; 
                html += '</div>';
            	});
				box.html(html);
			},
			error: function(xhr, status, error) {
            console.error("Error fetching permissions:", xhr.responseText);
            box.html('<p class="text-danger">Failed to load permissions.</p>');
        	}
		});
	}); // إغلاق دالة change
}); // إغلاق دالة $(document).ready()
</script>
@endsection