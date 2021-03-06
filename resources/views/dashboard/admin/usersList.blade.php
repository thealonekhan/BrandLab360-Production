@extends('dashboard.base')

@section('content')

<div class="container-fluid">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
				<div class="card">
						<div class="card-header">
							<i class="fa fa-align-justify"></i>{{ __('Users') }}
						</div>
						<div class="card-body">
							<div class="row">
								<a class="btn btn-lg btn-primary custom-btn-color ml-3" href="{{ route('users.create') }}">Add new User</a>
							</div>
							<br>
							<table class="table table-responsive-sm table-striped">
								<thead>
									<tr>
										<th>Username</th>
										<th>E-mail</th>
										<th>Roles</th>
										<th>Email verified at</th>
										<th></th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								
								<tbody>
									@foreach($users as $user)
										<tr>
											<td>{{ $user->name }}</td>
											<td>{{ $user->email }}</td>
											<td>{{ $user->menuroles }}</td>
											<td>{{ $user->email_verified_at }}</td>
											<td>
												<a href="{{ url('/users/' . $user->id) }}" class="btn btn-primary custom-btn-color">View</a>
											</td>
											<td>
												<a href="{{ url('/users/' . $user->id . '/edit') }}" class="btn btn-primary custom-btn-color">Edit</a>
											</td>
											<td>
												@if( $you->id !== $user->id )
												<a id="modal-delete-btn" data-attr="{{ route('users.delete', $user->id ) }}" class="btn btn-primary btn-danger pb-0">Delete</a>
												<!-- <form action="{{ route('users.destroy', $user->id ) }}" method="POST">
														@method('DELETE')
														@csrf
														<button class="btn btn-block btn-danger">Delete</button>
												</form> -->
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
	</div>
</div>

@endsection


@section('javascript')

@endsection

