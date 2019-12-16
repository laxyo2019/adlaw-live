@extends('lawschools.main')
@section('content')
<section class="content">
@include('student.header')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Manage Student</h4>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-2">
						<select class="form-control required" name="qual_catg_code" id="qual_catg">
							<option value="">Select Qualification</option>
							@foreach($qual_catgs as $qual_catg)
								<option value="{{$qual_catg->qual_catg_code}}">{{$qual_catg->qual_catg_desc}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-2">
						<select class="form-control required" name="qual_code" id="qual_course">

						</select>
					</div>

					<div class="col-md-2">
						<select class="form-control" name="batch">
							<option value="">Select Batch</option>
							@foreach($batches as $batch)
								<option value="{{$batch->id}}">{{$batch->name}}</option>
							@endforeach 
						</select>
					</div>
					<div class="col-md-2">
						<select class="form-control" name="year" id="mainYear"> 
							<option value="">Select Admission Year</option>
							<option value="1">1 year</option>
							<option value="2">2 year</option>
							<option value="3">3 year</option>
							<option value="4">4 year</option>
							<option value="5">5 year</option>
						</select>
					</div>
					<div class="col-md-2">
						<select class="form-control" name="semester" id="mainSemseter"> 
							<option value="">Select Semester</option>
							<option value="1">1st</option>
							<option value="2">2nd</option>
							<option value="3">3rd</option>
							<option value="4">4th</option>
							<option value="5">5th</option>
							<option value="6">6th</option>
							<option value="7">7th</option>
							<option value="8">8th</option>
							<option value="9">9th</option>
							<option value="10">10th</option>
						</select>
					</div>
					<div class="col-md-2">
						<button class="btn btn-sm btn-primary" id="btnFilter">Filter</button>
					</div>
						
				{{-- 	<div class="col-md-4">
						<span><b>Search &nbsp;</b></span><input type="text" name="search"/>
					</div> --}}
				</div>
				<div class="row" style="margin-top: 20px;">
					<div class="col-md-12 table-responsive" id="tableFilter">
						@include('student.student_detail.table')
					</div>
				</div>

				<div class="row" style="margin-top: 20px; display: none" id="tableFooter">
					<div class="col-md-12" >
						<button class="btn btn-sm btn-info pull-right" style="margin-left: 5px;" id="btnTransfer">Transfer</button>
						<button class="btn btn-sm btn-info pull-right" style="margin-left: 5px;" id="btnDropOut">Drop Out</button>
						<button class="btn btn-sm btn-info pull-right" style="margin-left: 5px;" id="btnForward">Forward</button>
					</div>
					<div class="col-md-12 text-right" style="margin-top: 20px;">
						<label>After Complete Qualification Running Student Transfer to Passout Student List. </label> 
						<button class="btn btn-sm btn-primary " id="btnPassout" style="margin-left: 10px;">Passout</button>
					</div>
				</div>

				<div class="row" style="margin-top: 10px; display: none" id="forwardDiv">
					<div class="col-md-4">
						<select class="form-control" name="forwardYear" id="forwardYear"> 
							<option value="">Select Admission Year</option>
							<option value="1">1 year</option>
							<option value="2">2 year</option>
							<option value="3">3 year</option>
							<option value="4">4 year</option>
							<option value="5">5 year</option>
						</select>					
					</div>
					<div class="col-md-4">
						<select class="form-control" name="forwardYear" id="forwardSemester"> 
							<option value="">Select Semester</option>
							<option value="1">1st</option>
							<option value="2">2nd</option>
							<option value="3">3rd</option>
							<option value="4">4th</option>
							<option value="5">5th</option>
							<option value="6">6th</option>
							<option value="7">7th</option>
							<option value="8">8th</option>
							<option value="9">9th</option>
							<option value="10">10th</option>
						</select>
					</div>
					<div class="col-md-4" id="forwardSuccess" style="display: none">
						<button class="btn btn-sm btn-default pull-right" style="margin-left: 5px;" id="forwardCancel">Cancel</button>
						<button class="btn btn-sm btn-success  pull-right" style="margin-left: 5px;" id="forwardNow">Forward Now</button>
					</div>
				</div>
				<div class="row" id="passoutDiv" style="display: none">
					<div class="col-md-4">
						<label>Students Passout Date:</label>
						<input type="text" name="passout_date" class="form-control datepicker" readonly="true" data-date-format="yyyy-mm-dd" placeholder="Enter Passout Date Here..." id="passoutDate" >
					</div>
		
					<div class="col-md-8" id="passoutSuccess" style="display: none;margin-top: 10px;">

						<button class="btn btn-sm btn-default pull-right" style="margin-left: 5px;" id="passoutCancel">Cancel</button>
						<button class="btn btn-sm btn-success pull-right" id="passoutNow" style="margin-left: 5px;"
						>Passout Now</button>
					</div>
				</div>
				<div class="row" id="dropoutDiv" style="display: none;">
					<div class="col-md-4">
						<label>Students Dropout Date:</label>
						<input type="text" name="dropout_date" class="form-control datepicker" readonly="true" data-date-format="yyyy-mm-dd" placeholder="Enter Dropout Date Here..." id="dropoutDate" >
					</div>		
					<div class="col-md-8" id="dropoutSuccess" style="display: none;margin-top: 10px;">

						<button class="btn btn-sm btn-default pull-right" style="margin-left: 5px;" id="dropoutCancel">Cancel</button>
						<button class="btn btn-sm btn-success pull-right" id="dropoutNow" style="margin-left: 5px;"
						>Drop Out Now</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
<script >
	$(document).ready(function(){
		$(function () {
			$(".datepicker").datepicker({ 

				singleDatePicker: true,
				showDropdowns: true,
			});
		});

		$('#qual_catg').on('change',function(e){
			e.preventDefault();
			var qual_catg_code = $(this).val();
			qual_course(qual_catg_code);
			qual_docs(qual_catg_code);
		});

		$('#btnFilter').on('click',function(e){
			e.preventDefault();
			var qual_catg_code = $('select[name="qual_catg_code"] option:selected').val();
			var qual_code = $('select[name="qual_code"] option:selected').val();
			var batch_id = $('select[name="batch"] option:selected').val();
			var qual_year = $('select[name="year"] option:selected').val();
			var semester = $('select[name="semester"] option:selected').val();
			var page = 'student_manage';
			if(batch_id !='' && qual_year != '' && semester !='' && qual_catg_code !=''  && qual_code != ''){
				$.ajax({
					type:'POST',
					url: "{{route('student_filter')}}",
					data: {qual_catg_code:qual_catg_code,qual_code:qual_code,batch_id:batch_id,qual_year:qual_year,semester:semester,page:page},
					success:function(res){
						$('#tableFilter').empty().html(res);
						$('#tableFooter').show();
					}
				});
			}else{
				alert('please select all field');
			}

		});


		$('#btnForward').on('click',function(){
			var id = [];
	        $('body .check:checked').each(function(i){
	          id[i] = $(this).val();
	        });
	        if(id.length != '0'){
	        	$('#tableFooter').hide();	        	
	        	$('#forwardDiv').show();
	        	// console.log(id);
	        }else{
	        	alert('Please check at least one checkbox');
	        }
		});

		$('#forwardYear, #forwardSemester').on('change',function(){
			var forwardYear = $('#forwardYear').val();
			var forwardSemester = $('#forwardSemester').val() ;
			if(forwardYear != '' && forwardSemester != ''){
				$('#forwardSuccess').show();
			}else{
				$('#forwardSuccess').hide();
			}
		})

		$('#forwardCancel').on('click',function(){
			$('#tableFooter').show();
			$('#forwardYear').val('');	 
			$('#forwardSemester').val('');      	
			$('#forwardSuccess').hide();
	        $('#forwardDiv').hide();
		});		


		$('#forwardNow').on('click',function(){
			var stud_id = [];
			var mainSemseter = $('#mainSemseter').val();
			// console.log(parseInt(mainSemseter) + 1 );
	        $('body .check:checked').each(function(i){
	          stud_id[i] = $(this).val();
	        });
	        var forwardYear = $('#forwardYear').val();
			var forwardSemester = $('#forwardSemester').val() ;
			if(mainSemseter == forwardSemester){
				alert('Students semester and forward semester are same');
			}else{
				if((parseInt(mainSemseter) + 1) != forwardSemester){
					alert('You did not forward student direct');
				}else{
					studentUpdate(stud_id,forwardYear,forwardSemester);
				}
			}
			
		});

		$('#btnTransfer').on('click',function(){
			var stud_id = [];
	        $('body .check:checked').each(function(i){
	          stud_id[i] = $(this).val();
	        });
	        if(stud_id.length != '0'){
	        	var mainSemseter = parseInt($('#mainSemseter').val());
				var mainYear = parseInt($('#mainYear').val());

				forwardYear = mainYear == 1 ? (mainSemseter != 1 ? mainYear + 1 : mainYear) : (mainYear == 2 ? (mainSemseter != 3 ? mainYear + 1 : mainYear) : (mainYear == 3 ? (mainSemseter != 5 ? mainYear + 1 : mainYear) : (mainYear == 4 ? (mainSemseter != 7 ? mainYear +1 : mainYear ) : mainYear ) ) ) ;

					forwardSemester = mainYear == 5 ? (mainSemseter == 9 ? mainSemseter + 1 : mainSemseter ) : mainSemseter + 1;
					if(mainSemseter == 10){
						alert('You cannot transfer students');
					}else{
						studentUpdate(stud_id,forwardYear,forwardSemester);
					}
				
	        }
	        else{
	        	alert('Please check at least one checkbox');
	        }


		});

		$('body').on('click','.selectAll' ,function(){	
			// console.log('select');
			 if ($(this).is( ":checked" )) {
				$('body .check').prop('checked',true);

			 }else{
				$('body .check').prop('checked',false);
			 }
		});

		$('#btnPassout').on('click',function(){
			var stud_id = [];
	        $('body .check:checked').each(function(i){
	          stud_id[i] = $(this).val();
	        });
	        if(stud_id.length !='0'){
	        	$('#tableFooter').hide();	 
	        	$('#passoutDiv').show();
	        	$('#passoutSuccess').show();
	        }else{
	        	alert('Please check at least one checkbox');
	        }
			
		});

		$('#passoutNow').on('click',function(){
			var passout_date = $('#passoutDate').val();
			var stud_id = [];
	        $('body .check:checked').each(function(i){
	          stud_id[i] = $(this).val();
	        });
			// console.log(passout_date);
			if(passout_date != ''){
				console.log(passout_date);
				swal({
				  title: "Are you sure?",
				  text: "Make Sure you choose correct student transfer to passout list. If you are not sure then close this pop up window",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((isConfirm) => {
				  if (isConfirm) {
				   	$.ajax({
				   		type:'post',
				   		url: "{{route('passout_student')}}",
				   		data:{passout_date:passout_date,stud_id:stud_id},
				   		success:function(res){
				   			// console.log(res);
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
				  } 
				});
			}else{
				alert('Enter Passout Date');
			}
		});

		$('#passoutCancel').on('click',function(){
			$('#tableFooter').show();
			$('#passoutDate').val('');	      	
			$('#passoutSuccess').hide();
	        $('#passoutDiv').hide();
		});

		$('#dropoutCancel').on('click',function(){
			$('#tableFooter').show();
			$('#dropoutDate').val('');	      	
			$('#dropoutSuccess').hide();
	        $('#dropoutDiv').hide();
		});


		$('#btnDropOut').on('click',function(){
			var stud_id = [];
	        $('body .check:checked').each(function(i){
	          stud_id[i] = $(this).val();
	        });
	        if(stud_id.length !='0'){
	        	$('#tableFooter').hide();	 
	        	$('#dropoutDiv').show();
	        	$('#dropoutSuccess').show();
	        }else{
	        	alert('Please check at least one checkbox');
	        }
			
		});

		$('#dropoutNow').on('click',function(){
			var dropout_date = $('#dropoutDate').val();
			var stud_id = [];
	        $('body .check:checked').each(function(i){
	          stud_id[i] = $(this).val();
	        });
			// console.log(passout_date);
			if(dropout_date != ''){
				swal({
				  title: "Are you sure?",
				  text: "Make Sure you choose correct student transfer to Drop Out list. If you are not sure then close this pop up window",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((isConfirm) => {
				  if (isConfirm) {
				   	$.ajax({
				   		type:'post',
				   		url: "{{route('dropout_student')}}",
				   		data:{dropout_date:dropout_date,stud_id:stud_id},
				   		success:function(res){
				   			 //console.log(res);
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
				  } 
				});
			}else{
				alert('Enter Passout Date');
			}
		});


		function studentUpdate(stud_id,forwardYear,forwardSemester){
			swal({
				  title: "Are you sure?",
				  text: "Make Sure you choose correct student to transfer and forward. If you are not sure then close this pop up window",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((isConfirm) => {
				  if (isConfirm) {
				   	$.ajax({
				   		type:'post',
				   		url: "{{route('forward_student')}}",
				   		data:{stud_id:stud_id,forwardYear:forwardYear,forwardSemester:forwardSemester},
				   		success:function(res){
				   			// console.log(res);
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
				  } 
				});
		}
	})
</script>
@endsection
