@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Registro</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Oops!</strong> Existe algunos errores con los datos.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Nombre</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="nombre" value="{{ old('nombre') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Apellidos</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="apellidos" value="{{ old('apellidos') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Cargo</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="cargo" value="{{ old('cargo') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Teléfono</label>
							<div class="col-md-3">
								<input type="text" class="form-control" name="telefono" value="{{ old('telefono') }}">
							</div>
							<div class="col-md-3">
								<input type="text" class="form-control" name="celular" value="{{ old('celular') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Email</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Repetir Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Registrar
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
