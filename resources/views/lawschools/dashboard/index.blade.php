@extends('lawschools.main')
@section('content')
<section class="content">
<div class="row">
  @role('lawcollege')
	 <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ count($user->members) }}</h3>

              <p>Total Teachers</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a href="{{route('teams.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{count($user->students)}}</h3>

              <p>Total Students</p>
            </div>
            <div class="icon">
              <i class="fa fa-graduation-cap"></i>
            </div>
            <a href="{{route('student.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{count($user->teams)}}</h3>

              <p>Total Teams</p>
            </div>
            <div class="icon">
              <i class="fa fa-group"></i>
            </div>
            <a href="{{route('teams.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{count($user->courses)}}</h3>

              <p>Total Courses</p>
            </div>
            <div class="icon">
              <i class="fa fa-gavel"></i>
            </div>
            <a href="{{route('course.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
 </div>	
 <div class="row">
   <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
              <h3>{{count($user->batches)}}</h3>

              <p>Total Batches</p>
            </div>
            <div class="icon">
              <i class="fa fa-book"></i>
            </div>
            <a href="{{route('batches.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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