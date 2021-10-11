<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
	
	<!-- Sidebar - Brand -->
		<a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route("admin.home") }}">
			<div class="sidebar-brand-icon rotate-n-15">
				<i class="fas fa-headset"></i>
			</div>
			<div class="sidebar-brand-text mx-3">Help Desk</div>
		</a>
		<!-- Divider -->
		<hr class="sidebar-divider my-0">

		<!-- Nav Item - Dashboard -->
		<li class="nav-item active">
			@can('dashboard_access')
			<a class="nav-link" href="{{ route("admin.home") }}">
				<i class="fas fa-fw fa-tachometer-alt"></i>
				<span>{{ trans('global.dashboard') }}</span>
			</a>
			@endcan
		</li>

	@can('user_management_access')
		<!-- Heading -->
		<div class="sidebar-heading">
			Managements
		</div>
		<!-- Nav Item - User Management -->
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser"
			aria-expanded="true" aria-controls="collapseTwo">
				<i class="fas fa-users"></i>
				<span>{{ trans('cruds.userManagement.title') }}</span>
			</a>

			<div id="collapseUser" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					@can('permission_access')
					<a class="collapse-item" href="{{ route("admin.permissions.index") }}">{{ trans('cruds.permission.title') }}</a>
					@endcan
					@can('role_access')
					<a class="collapse-item" href="{{ route("admin.roles.index") }}">{{ trans('cruds.role.title') }}</a>
					@endcan
					@can('user_access')
					<a class="collapse-item" href="{{ route("admin.users.index") }}">{{ trans('cruds.user.title')}} </a>
					@endcan 
					@can('audit_log_acces')
					<a class="collapse-item" href="{{ route("admin.audit-logs.index") }}">{{ trans('cruds.auditLog.t')}}</a>
					@endcan
				</div>
			</div>
		</li>
	@endcan

	<!-- Nav Item - Category -->
	@can('status_access')
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
			aria-expanded="true" aria-controls="collapseUtilities">
				<i class="fas fa-fw fa-wrench"></i>
				<span>{{ trans('cruds.setting.title') }}</span>
			</a>
			<div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					@can('status_access')
					<a class="collapse-item" href="{{ route("admin.statuses.index") }}">{{ trans('cruds.status.title') }}</a>
					@endcan
					@can('config_settings')
					<a class="collapse-item" href="{{ route("admin.priorities.index") }}">{{ trans('cruds.priority.title') }}</a>
					@endcan
					@can('user_access')
					<a class="collapse-item" href="{{ route("admin.categories.index") }}"> {{ trans('cruds.category.title') }}</a>
					@endcan
				</div>
			</div>
		</li>
	@endcan

	  <!-- Heading -->
		<div class="sidebar-heading">
		    Action
		</div>
	 
	 <!-- Nav Item - Ticket -->
	@can('ticket_access')
		<li class="nav-item">
			<a class="nav-link" href="{{ route("admin.tickets.index") }}">
			    <i class="fas fa-ticket-alt"></i>
			    <span>{{ trans('cruds.ticket.title') }}</span>
			</a>
		</li>
	@endcan
	<!-- Nav Item - Comments -->
	@can('comment_access')
		  <li class="nav-item">
		      <a class="nav-link" href="{{ route("admin.comments.index") }}">
		        <i class="fas fa-comments"></i>
		          <span> {{ trans('cruds.comment.title') }}</span>
		      </a>
		  </li>
	@endcan
	<!-- Nav Item - Project -->
	@can('comment_access')
	    <li class="nav-item">
	      <a class="nav-link" href="{{ route("admin.projects.index") }}">
	          <i class="fas fa-tasks"></i>
	          <span> {{ trans('cruds.project.title') }}</span>
	          </a>
	      </a>
	  	</li>
	@endcan

	<!-- Divider -->
	<hr class="sidebar-divider my-0">

	<!-- Nav Item - Logout -->
	<li class="nav-item">
	    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
	        <button class="btn btn-md btn-danger btn-block"> <i class="fas fa-sign-out-alt"></i>{{ trans('global.logout') }}</button>
	   	 </a> 
	</li>

	<!-- Divider -->
	<hr class="sidebar-divider my-0">
	<br>
	   <!-- Sidebar Toggler (Sidebar) -->
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>
</ul>
