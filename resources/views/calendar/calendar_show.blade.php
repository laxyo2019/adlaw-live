<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-md-12 text-center">
							<label><i class="fa fa-square" style="color: #3c8dbc; height: 25px;width: 20px;"></i> My To-dos</label>
							@if(Auth::user()->parent_id ==null )<label><i class="fa fa-square" style="color: #157b7f; height: 25px;width: 20px;"></i> Member To-dos</label>@endif
							<label><i class="fa fa-square" style="color: #0cab0a; height: 25px;width: 20px;"></i> Hearing Morning Session</label>
							<label><i class="fa fa-square" style="color: #1bada6; height: 25px;width: 20px;"></i> Hearing Evening Session</label>
						</div>
					</div>
					<div id="calendar"></div>
					<br>
					
				</div>
			</div>
		</div>
		
	</div>
	@include('calendar.calendar_modal')

<script>
 @if($message = Session::get('success'))
 	alert("{{$message}}");
 @endif
	$(document).ready(function() {
	
	$('#calendar').fullCalendar({
		header: {
		right: 'prev today next ',
		center: 'title',
		left: 'month,agendaWeek,agendaDay,listWeek'
		},
		height: 600,
		navLinks: true,
		editable: false,
		eventLimit: true,
		selectable : true,

		events: [

			@foreach($hearings as $hearing)
				{
					id: '{{$hearing->case_tran_id}}',
					title:'{{$hearing->case->case_title}}',
					description: 'Hearing Date: {{date('d-m-Y', strtotime( $hearing->hearing_date))}} <br> Client Name: {{$hearing->client->cust_name}}<br> Start Time: {{$hearing->start_time}} <br> Lawyer Name: <?php $law_data = json_decode($hearing->lawyer_names) ; 	
						foreach ($law_data as $value) {
						 	$user = \App\User::find($value);
						 	echo $user->name .', ';
						} 
					?> <br> Judges Name: <?php 
						$law_data = json_decode($hearing->judges_name) ;
						foreach ($law_data as $value) {
						 	echo $value .', ';
						} 
					?>',
					color:'{{date('G',strtotime($hearing->start_time)) > '12' ? '#0cab0a' : '#1bada6' }}',
					url: '{{route('case_mast.show',$hearing->case_id.',case_diary')}}',
					start: '{{$hearing->hearing_date}}',
					end: '{{$hearing->hearing_date}}',
			
				},
			@endforeach
			@foreach($todos as $todo)
			{	
				id: '{{$todo->id}}',
				title: '{{$todo->title}}',
				description: 'Creator : {{$todo->created_user->name}} <br> Assignee: {{$todo->assigned_user->name}} <br>Status: {{$todo->status == 'P' ? 'Pending' : ''}} <br> Start Date: {{date('d-m-Y', strtotime($todo->start_date))}} <br> End Date: {{date('d-m-Y', strtotime($todo->end_date))}}',
				color: '{{$todo->user_id1 == Auth::user()->id ? '#3c8dbc': '#157b7f'}}',
				url : '{{route('todos.show',$todo->id.'_')}}',
				start: '{{$todo->start_date}}',
				end: '{{$todo->end_date}}',
				
			},
			@endforeach
		],
		// eventRender: function(event, element) { 
  //           element.find('.fc-title').append("" + event.description); 
  //       }, 
		eventMouseover: function(calEvent, jsEvent) {
		    var tooltip = '<div class="tooltipevent text-center" style="width:200px;min-height:100px;background:orange;position:absolute;z-index:10001; padding:5px;">' + calEvent.description + '</div>';
		    $("body").append(tooltip);
		    $(this).mouseover(function(e) {
		        $(this).css('z-index', 10000);
		        $('.tooltipevent').fadeIn('500');
		        $('.tooltipevent').fadeTo('10', 1.9);
		    }).mousemove(function(e) {
		        $('.tooltipevent').css('top', e.pageY + 10);
		        $('.tooltipevent').css('left', e.pageX + 20);
		    });
		},

		eventMouseout: function(calEvent, jsEvent) {
		     $(this).css('z-index', 8);
		     $('.tooltipevent').remove();
		},
  

		select: function(start, end, jsEvent, view) {
			var show = "{{Auth::user()->parent_id}}" ;
	         if(show == ''){
					var start_date = moment(start).format('YYYY-MM-DD');

					var end_date =  moment(end).subtract(1, 'days').format('YYYY-MM-DD');
					// alert(end_date);
			        $('.start_date').val(start_date);
					$('.end_date').val(end_date);
					$('.h_date').val(start_date);
					$('#calendar_modal').modal({"backdrop": "static"});
		      }
	    }

	
		});

	
	});
  </script>