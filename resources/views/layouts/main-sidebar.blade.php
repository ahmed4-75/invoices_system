<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
	<div class="main-sidebar-header active">
		<a class="desktop-logo logo-light active" href="{{ Route('home') }}"><img src="{{URL::asset('assets/img/brand/logo.png')}}" class="main-logo" alt="logo"></a>
		<a class="desktop-logo logo-dark active" href="{{ Route('home') }}"><img src="{{URL::asset('assets/img/brand/logo-white.png')}}" class="main-logo dark-theme" alt="logo"></a>
		<a class="logo-icon mobile-logo icon-light active" href="{{ Route('home') }}"><img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="logo-icon" alt="logo"></a>
		<a class="logo-icon mobile-logo icon-dark active" href="{{ Route('home') }}"><img src="{{URL::asset('assets/img/brand/favicon-white.png')}}" class="logo-icon dark-theme" alt="logo"></a>
	</div>
	<div class="main-sidemenu">
		<div class="app-sidebar__user clearfix">
			<div class="dropdown user-pro-body">
				<div class="">
					<img alt="user-img" class="avatar avatar-xl brround" src="{{URL::asset('assets/img/faces/18.jpg')}}">
					<span class="avatar-status profile-status bg-green"></span>
				</div>
				@auth
					<div class="user-info">
						<h4 class="font-weight-semibold mt-3 mb-0">{{Auth::user()->name}}</h4>
						<span class="mb-0 text-muted">{{Auth::user()->email}}</span>
					</div>
				@else
					<script> window.location.href = "{{ route('login') }}"; </script>
				@endauth
			</div>
		</div>
		<ul class="side-menu">
			<li class="slide">
				<a class="side-menu__item" href="{{ route('home') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">Main Page</span><span class="badge badge-success side-badge">1</span></a>
			</li>
			<li class="side-item side-item-category">SHEETS</li>
			<li class="slide">
				<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 5H5v14h14V5zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" opacity=".3"/><path d="M3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2zm2 0h14v14H5V5zm2 5h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg><span class="side-menu__label">Sheets</span><i class="angle fe fe-chevron-down"></i></a>
				<ul class="slide-menu">
					<li><a class="slide-item" href="{{ route('invoices_list.index') }}">Invoices List</a></li>
					<li><a class="slide-item" href="{{ route('receipts_list.index') }}">Receipts List</a></li>
					<li><a class="slide-item" href="{{ route('expense_list.index') }}">Expense List</a></li>
				</ul>
			</li>
			<li class="side-item side-item-category">Goods</li>
			<li class="slide">
				<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg><span class="side-menu__label">Goods</span><i class="angle fe fe-chevron-down"></i></a>
				<ul class="slide-menu">
					<li><a class="slide-item" href="{{ route('sections.index') }}">Sections</a></li>
					<li><a class="slide-item" href="{{ route('products.index') }}">Products</a></li>
				</ul>
			</li>
			<li class="side-item side-item-category">Human Resource</li>
			<li class="slide">
				<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M6 20h12V10H6v10zm6-7c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" opacity=".3"/><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg><span class="side-menu__label">Users & Permissions</span><i class="angle fe fe-chevron-down"></i></a>
				<ul class="slide-menu">
					<li><a class="slide-item" href="{{ route('users.index') }}">Users</a></li>
					<li><a class="slide-item" href="{{ route('rolesAndPermissions.index') }}">Roles & Permissions</a></li>
				</ul>
			</li>
			<li class="side-item side-item-category">archives</li>
			<li class="slide">
				<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M6.26 9L12 13.47 17.74 9 12 4.53z" opacity=".3"/><path d="M19.37 12.8l-7.38 5.74-7.37-5.73L3 14.07l9 7 9-7zM12 2L3 9l1.63 1.27L12 16l7.36-5.73L21 9l-9-7zm0 11.47L6.26 9 12 4.53 17.74 9 12 13.47z"/></svg><span class="side-menu__label">Archives</span><i class="angle fe fe-chevron-down"></i></a>
				<ul class="slide-menu">
					<li><a class="slide-item" href="{{ route('invoices_archive.index') }}">Invoices Archives </a></li>
					<li><a class="slide-item" href="{{ route('expenses_archive.index') }}">Expenses Archives </a></li>
					<li><a class="slide-item" href="{{ route('goods_archive.index') }}">Goods Archive</a></li>
				</ul>
			</li>
		</ul>
	</div>
</aside>
<!-- main-sidebar -->
