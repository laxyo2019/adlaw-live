<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title>Welcome Email</title>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-11 m-auto">
				<div class="card shadow-lg">
					<div class="card-header bg-white">
						<h5 class="text-center">
							<a class="navbar-brand p-2" href="{{url('/')}}"><img src="{{asset('images/adlaw-logo.png')}}" alt="Adlaw" style="width: 120px;"></a>
						</h5>
					</div>
					<div class="card-body">	
						<h5><b>Hi {{$user['name']}},</b></h5>
						<br>
						<div class="row">
							<div class="col-md-12">
								@if($user['parent_id'] == null)
									<p> Adlaw have created your account please verfiy your account. your username and password is below here</p>
								@elseif($user['user_catg_id'] == '6')
									<p> Adlaw Law College have created your account please verfiy your account. your username and password is below here</p>
								@elseif($user['user_catg_id'] == '2')
									<p> Adlaw Law Company have created your account please verfiy your account. your username and password is below here</p>
								@elseif($user['user_catg_id'] == '7')
									<p>Your Law college have created your account please verfiy your account. your username and password is below here</p>
								@endif

							</div>
							<div class="col-md-12 text-center mt-2 mb-2">
								<a href="{{url('user/verify', $user->verifyUser->token)}}" class="btn btn-md btn-success">Verify Email</a>
							</div>
							<div class="col-md-12 mt-2">
								<table class="table table-bordered">
									<tbody>
										<tr>
											<td>Username</td>
											<td>{{$user['email']}}</td>
										</tr>
										<tr>
											<td>Password</td>
											<td>{{$user['password']}}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</html>