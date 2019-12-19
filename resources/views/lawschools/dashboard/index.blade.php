@extends('lawschools.main')
@section('content')
<section class="content">
@role('lawcollege')
<div class="row">
   <div class="col-md-4 ">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{count($user->students)}}</h3>
          <h4>Total Students</h4>
          <span>Running: {{count($running_student)}} | Passout : {{count($passout_student)}} | Dropout : {{count($dropout_student)}} </span>
        </div>
        <div class="icon">
          <i class="fa fa-users"></i>
        </div>
        <a href="{{route('student.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <div class="col-md-4 ">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>{{count($user->members)}}</h3>
          <h4>Total Teachers</h4>
          <br>          
        </div>
        <div class="icon">
          <i class="fa fa-users"></i>
        </div>
        <a href="{{route('teams.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <div class="col-md-4 ">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{count($user->teams)}}</h3>
          <h4>Total Teams</h4>
          <br>          
        </div>
        <div class="icon">
          <i class="fa fa-book"></i>
        </div>
        <a href="{{route('teams.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 ">
      <div class="small-box bg-blue">
        <div class="inner">
          <h3>{{count($user->courses)}}</h3>
          <h4>Total Courses</h4>
          <br>          
        </div>
        <div class="icon">
          <i class="fa fa-book"></i>
        </div>
        <a href="{{route('course.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-md-4 ">
      <div class="small-box bg-orange">
        <div class="inner">
          <h3>{{count($user->batches)}}</h3>
          <h4>Total Batches</h4>
          <br>          
        </div>
        <div class="icon">
          <i class="fa fa-briefcase"></i>
        </div>
        <a href="{{route('batches.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
</div>

<div class="row"> 
  <div class="col-md-6" >
    <div class="box box-primary bg-default" style="height: 300px;">
      <div class="box-header with-border bg-blue">
        <i class="fa fa-list"></i>
        <h3 class="box-title">Today's Birthday</h3>
      </div>
      <div class="box-body" >
        <ul class="todo-list" style="height: 200px;">
          @foreach($todays_birthday as $value)
            <li class="text-capitalize">{{$value->f_name .' '. $value->l_name}}</li> 
          @endforeach
        </ul>
      </div>
      <!-- /.box-body -->
      <div class="box-footer clearfix no-border">
        
      </div>
    </div>
  </div>
</div>
@endrole
	<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-body">
          <div id="calendar"></div>
          <br>
          {{-- <div class="row">
            <div class="col-md-12 text-center">
              <label><i class="fa fa-square" style="color: #ff4566; height: 25px;width: 20px;"></i> Member To-dos</label>
              <label><i class="fa fa-square" style="color: #fda503; height: 25px;width: 20px;"></i> Everyone To-dos</label>
              <label><i class="fa fa-square" style="color: #8259ff; height: 25px;width: 20px;"></i> Hearing Morning Session</label>
              <label><i class="fa fa-square" style="color: #0cab0a; height: 25px;width: 20px;"></i> Hearing Evening Session</label>
            </div>
          </div> --}}
        </div>
      </div>
    </div>
    
  </div>
{{--   @include('calendar.calendar_modal') --}}

</section>
<script>
  $(document).ready(function() {
  
      var filestack_id = "{{Auth::user()->filestack_id}}";
      var parent_id = "{{Auth::user()->parent_id}}";
      if(parent_id == ''){
        user_filestackCreate(filestack_id);
      }

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
    });
  });
</script>
@endsection