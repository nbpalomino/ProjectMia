@extends('app')

@section('main')
<main class="page login">
	<section class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="form-group">
					<h1 class="text-center">
						<img src="{{ asset('assets/img/LogoBoshLogin.png') }}" alt="Logotype">
					</h1>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="panel">
					<div class="panel-heading text-center">
						<h3>Login</h3>
					</div>

					@if (count($errors) > 0)
					<div class="text-center">
						<div class="alert alert-danger">
							<strong>Oops!</strong> Existe algunos errores con los datos.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('api/auth/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="panel-body">
							<div class="form-group">
								<div class="input-group plain-addon">
									<div class="input-group-addon"><span class="fa fa-envelope-o fa-fw"></span></div>
									<input type="email" class="form-control" name="email" placeholder="Ingrese su E-mail" value="{{ old('email') }}">
								</div>
							</div>
							<div class="form-group">
								<div class="input-group plain-addon">
									<div class="input-group-addon"><span class="fa fa-unlock-alt fa-fw"></span></div>
									<input type="password" class="form-control" name="password" placeholder="Ingrese su Contraseña" value="{{ old('password') }}">
								</div>
							</div>
							<a class="btn btn-link" href="{{ url('/recovery') }}">Olvidaste tu clave?</a>
							<input type="submit"  class="btn btn-primary pull-right" value="Ingresar">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="remember" value="{{ old('remember') }}"> Recordarme
								</label>
							</div>
						</div>
					</form>
					<div class="panel-footer">
						<span class="text-center">No tienes usuario? <a href ui-sref="page.register" class="text-default"><strong>Registrate ahora!</strong></a></span>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="form-group">
					<h1 class="text-center">
						<img src="assets/img/logo-autorex.png" alt="Logotype">
					</h1>
				</div>
			</div>
		</div>
	</section>
</main>
@overwrite