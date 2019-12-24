@extends('lawfirm.main')
@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h4 class="">To-Dos Details						
						<a href="{{route('todos.index')}}" class="btn btn-sm btn-info pull-right">Back</a>
						@if($todo->user_id == Auth::user()->id)
						<a href="{{route('todos.edit',$todo->id)}}" class="pull-right btn btn-sm btn-success" style="margin-right: 10px;">Edit</a>
						@if($todo->status == 'A')
						<button class="pull-right btn btn-sm btn-primary btnChecked" style="margin-right: 10px;">Approve</button>
						@endif
						@endif

					</h4>

				</div>
				<div class="box-body">
					<div class="row" style="margin-top:10px; ">
						<div class="col-md-12">
							@if($message = Session::get('success'))
								<div class="alert bg-success">{{$message}}</div>
							@endif
						</div>
					</div>	
					<div class="row">
						<div class="col-md-2 col-xl-2 col-sm-2"> <h4><b >Title : </b></h4> </div>
						<div class="col-md-6 col-xl-6 col-sm-6"> <h4 class="text-capitalize">{{$todo->title}}</h4> </div>
					</div>
					@if($todo->relate_to_case !=null)
						<div class="row">
							<div class="col-md-2 col-xl-2 col-sm-2"> <h4><b>Related Case Title : </b></h4> </div>
							<div class="col-md-6 col-xl-6 col-sm-6"> <h4 class="text-capitalize">{{$todo->relate_to_case->case_title}}</h4> </div>
						</div>
					@endif
					<div class="row">
						<div class="col-md-2 col-xl-2 col-sm-2"> <h4><b>Creator : </b></h4> </div>
						<div class="col-md-6 col-xl-6 col-sm-6"> <h4 class="text-capitalize">{{$todo->created_user->name}}</h4> </div>
					</div>
					<div class="row">
						<div class="col-md-2 col-xl-2 col-sm-2"> <h4><b>Assignee : </b></h4> </div>
						<div class="col-md-6 col-xl-6 col-sm-6"> <h4>{{$todo->assigned_user->name}}</h4> </div>
					</div>
					<div class="row">
						<div class="col-md-2 col-xl-2 col-sm-2"> <h4><b>Start Date : </b></h4> </div>
						<div class="col-md-6 col-xl-6 col-sm-6"> <h4>{{date('d-m-Y', strtotime($todo->start_date))}}</h4> </div>
					</div>
					<div class="row">
						<div class="col-md-2 col-xl-2 col-sm-2"> <h4><b>End Date : </b></h4> </div>
						<div class="col-md-6 col-xl-6 col-sm-6"> <h4>{{date('d-m-Y', strtotime($todo->end_date))}}</h4> </div>
					</div>
					<div class="row">
						<div class="col-md-2 col-xl-2 col-sm-2"> <h4><b>Status : </b></h4> </div>
						<div class="col-md-6 col-xl-6 col-sm-6"> <h4>{{ $todo->status == 'P' ? 'Pending' : ($todo->status == 'A' ? 'Awaiting' : ($todo->status == 'C' ? 'completed' : ($todo->status == 'M' ? 'Missed' : 'Closed')))}}</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-xl-12 col-sm-12"> <h4><b>Description : </b></h4> </div>
						<div class="col-md-12 col-xl-12 col-sm-12">
							@php 
								echo $todo->description;
							@endphp 
						</div>
					</div>	

					@if(Auth::user()->id != $todo->user_id && $todo->status == 'M') 

					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h1 class="panel-title ">Give a reason why todo missed .. </h1>
								</div>
								<div class="panel-body">	
									<div class="row">
										<div class="col-md-6 col-xl-12 col-sm-12 form-group">
											<textarea name="" class="form-control" rows="5" cols="5" placeholder="Type Here ..." id="reason"></textarea>
											<span class="text-danger error" style="display: none"><strong>This field is required</strong></span>	
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 form-group">
											<button  class="btn btn-sm btn-primary" id="btnSubmit">Submit</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>	
					@endif	

					@if($todo->status == 'O') 
						<div class="panel panel-default">
							<div class="panel-heading">
								<h1 class="panel-title ">Todo Missed Reason</h1>
							</div>
							<div class="panel-body">	
								<div class="row">
									<div class="col-md-6 col-xl-12 col-sm-12 form-group">
										@php 
											echo $todo->missed_reason;
										@endphp	
									</div>
								</div>
								
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</section>
<script >
	$(document).ready(function(){
		// var noti_id = "";		
 	// 	if(noti_id !=''){
 	// 		$.ajax({
		// 		type:'GET',
		// 		url:"{{route('mark_as_read')}}",
		// 		data:{noti_id:noti_id},
		// 		success:function(res){
		// 			if(res == 'true'){
		// 				console.log(res);
						
		// 				location.reload();
		// 			}else{
		// 				console.log(res);
		// 			}
		   		
		// 		}
		// 	});
 	// 	}
		$('#btnSubmit').on('click',function(e){
			e.preventDefault();
			var reason = $('#reason').val();
			var id = "{{$todo->id}}";
			if(reason != ''){
				$('.error').hide();
				$('#reason').css("border-color","#d2d6de");
				swal({
				  title: "Are you sure?",
				  text: "Once Completed, you will be send this reason again you can't send another reason of this to-dos closed",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				}).then((isConfirm) => {
					if(isConfirm){
						$.ajax({
							type:'GET',
							url:"{{route('todos.todo_closed_reason')}}",
							data:{id:id,reason:reason},
							success:function(res){
								swal({
					   				icon:'success',
					   				title: res,
					   				button: true,
					   			}).then((ok)=> {
					   				if(ok){
					   					location.reload();
					   				}
					   			});
							}
						});
					}else{
						swal("To-dos not submit");
					}

				})
				

			}else{
				$('.error').show();
				$('#reason').css("border-color","#cc7777");
			}
		});
		$('#reason').keypress(function(e){
			
			$('.error').hide();
			$('#reason').css("border-color","#d2d6de");
		});

		$('.btnChecked').on('click',function(e){
			e.preventDefault();
			var id = "{{$todo->id}}";
			$.ajax({
				type:'GET',
				url:"{{route('todos.awaiting_todo_update')}}",
				data:{id:id},
				success:function(res){
					swal({
		   				icon:'success',
		   				title: res,
		   				button: true,
		   			}).then((ok)=> {
		   				if(ok){
		   					location.reload();
		   				}
		   			});
				}
			});
		});

	});
</script>
@endsection