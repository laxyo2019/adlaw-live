@extends('lawfirm.main')
@section('content')
<section class="content">
	<div class="row">
	    <div class="col-md-4 ">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{count($cases)}}</h3>
              <h4>Cases</h4>
              <span>Running: {{count($running_cases)}} | Closed : {{count($closed_cases)}} | Transfed/NOC : {{count($transferred_cases)}} | Direction Matter : {{count($direction_cases)}}  | Order Reserved : {{count($order_cases)}}</span>
            </div>
            <div class="icon">
              <i class="fa fa-briefcase"></i>
            </div>
            <a href="{{route('case_mast.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        @if(Auth::user()->parent_id ==null)
        <div class="col-md-4 ">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{count($user->clients)}}</h3>

              <h4>Total Clients</h4>
              </br></br>
              <span></span>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="{{route('clients.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        @endif
        <!-- ./col -->
        <div class="col-md-4 ">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{count($user->messages)}}</h3>

              <h4>Message</h4></br></br>
            </div>
            <div class="icon">
              <i class="fa fa-envelope"></i>
            </div>
            <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
  
        </div>
   
@if(Auth::user()->parent_id ==null)

 </div>

    <div class="row">
      <div class="col-md-4 ">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
              <h3>{{count($appointments)}}</h3>
              <h4>Appointment</h4>
              <span>Unconfirmed : {{count($unbookings)}} | Confirmed : {{count($booked)}} | Cancelled Request : {{count($cancelled)}} </span>
            </br><br>
            </div>
            <div class="icon">
              <i class="fa fa-calendar"></i>
            </div>
            <a href="{{route('booking.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
@endif
        <div class="col-md-4 ">
          <!-- small box -->
          <div class="small-box " style="color:white; background-color: #5c6a77">
            <div class="inner">
              <h3>0</h3>
              <h4>Document</h4>
            </br></br>
            </div>
            <div class="icon">
              <i class="fa fa-file"></i>
            </div>
            <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
         <div class="col-md-4 ">
      <!-- small box -->
        <div class="small-box" style="background-color: #23bab5; color: white">
          <div class="inner">
            <h3>{{count($todos)}}</h3>
            <h4>To-dos</h4>
            <span>Pending : {{count($pen_todos)}} | Complete: {{count($com_todos)}} | Awaiting: {{count($awt_todos)}} | Closed: {{count($clos_todos)}} | Missed : {{count($mis_todos)}}</span>
          </div>
          <div class="icon">
            <i class="fa fa-list-alt"></i>
          </div>
          <a href="{{route('todos.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
        
    </div>
    @if(Auth::user()->parent_id ==null)

    <div class="row">
       <div class="col-md-4 ">
  
          <div class="small-box" style="background-color: #df6c6f; color:white;">
            <div class="inner">
              <h3>{{count($user->teams)}} </h3>
              <h4>Teams</h4>
              </br></br>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="{{route('teams.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

      <div class="col-md-4 ">  
          <div class="small-box" style="background-color: #2d6c98de; color:white;">
            <div class="inner">
              <h3>{{count($user->members)}} </h3>
              <h4>Members</h4>
              </br></br>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a href="{{route('teams.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
      </div>
      <div class="col-md-4 ">  
          <div class="small-box" style="background-color: #3f315ade; color:white;">
            <div class="inner">
              <h3>0</h3>
              <h4>Agenda</h4>
              </br></br>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a href="{{route('agenda.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
      </div>
      {{-- <div class="col-md-4 ">
      <!-- small box -->
        <div class="small-box" style="background-color: #5da3d4; color: white">
          <div class="inner">
            <h3>0</h3>

            <h4>Invoices</h4>
            <span>Dialy : 0 | Saint: 0 | Paid: 0 | Partially Paid: 0 | Draft & Partially Paid : 0 |</span>
          </div>
          <div class="icon">
            <i class="fa fa-inr"></i>
          </div>
          <a href="{{route('case_diary.index',['caseBtn' =>'ca'])}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div> --}}
    </div>'
@endif
      <div class="row">
        <div class="col-md-6" >
            <div class="box box-primary " style="height: 300px;">
            <div class="box-header">
              <i class="fa fa-list"></i>
              <h3 class="box-title">Pending To-Dos</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" >
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list" style="height: 200px;">
                @foreach($pen_todos as $todo)
                <li>
                  <span><b><span>Start Date: &nbsp;{{date('M d, Y' , strtotime($todo->start_date))}}</span>  <p class="pull-right">End Date: &nbsp; {{date('M d, Y' , strtotime($todo->end_date))}}</p></b></span>
                  <br>
                  <span style="margin-right: 10px;"><b>Creator:</b> {{$todo->created_user->name}} </span> 
                  <span><b>Assignee:</b> {{$todo->assigned_user->name}}</span>
                  <br> 
                  <span><b>Title: </b></span>
                  
                  <span class="text-capitalize">{{ str_limit($todo->title, $limit = 40, $end = '...') }}</span>
                
                  <div class="tools">
                    <a href="{{route('todos.show',$todo->id)}}"><i class="fa fa-eye text-primary fa-icon" ></i></a>
                  @if($todo->user_id == Auth::user()->id)  <a href="{{route('todos.edit',$todo->id)}}"><i class="fa fa-edit text-success fa-icon" ></i></a>@endif
                   {{--  <a href="{{route('todos.delete',$todo->id)}}"><i class="fa fa-trash-o fa-icon"></i></a> --}}

                  </div>
                </li>
                @endforeach
               
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
              <a href="{{route('todos.index')}}" class="btn btn-default pull-right">More info <i class="fa fa-arrow-circle-right"></i></a>
              @if(Auth::user()->parent_id == null )<a href="{{route('todos.create')}}" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add Todo</a> @endif
            </div>
          </div>
        </div>

        <div class="col-md-6" >
            <div class="box box-primary " style="height: 300px;">
            <div class="box-header">
              <i class="fa fa-list"></i>
              <h3 class="box-title">Upcoming Hearing Dates</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list"  style="height: 200px;">
                @foreach($hearings as $hearing)
                <li>
                  <label>{{date('M d, Y', strtotime($hearing->hearing_date))}}</label>
                  <br>
                  <span>{{$hearing->case->case_title}}</span>
              
                  <div class="tools">
                    <a href="{{ route('case_mast.show', $hearing->case_id.',case_diary') }}"><i class="fa fa-eye text-primary fa-icon"></i></a>
                   @if($hearing->user_id == Auth::user()->id)  <a href="{{route('case_hearing.edit', $hearing->case_tran_id.',case_diary')}}"><i class="fa fa-edit text-success fa-icon" ></i></a>@endif
                  </div>
                </li>
                @endforeach
               
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
              <a href="{{route('upcomingHearings')}}" class="btn btn-default pull-right">More info <i class="fa fa-arrow-circle-right"></i></a>
             {{-- @if(Auth::user()->parent_id == null ) <a href="" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add Hearing</a>@endif --}}

            </div>
          </div>
        </div>
      </div>
   {{--    <div class="row">
        <div class="col-md-12">
          <div class="box box-primary" style="height: 300px;">
            <div class="box-header">
              <h3 class="box-title">Recent Activity</h3>
            </div>
            <div class="box-body" style="height: 200px;">
                 
              <ul class="todo-list">
                <li>
                  <label>September 17, 2019</label>
                  <br>
                  <span>Original Suit 1231 / 2019 - case</span>
                 
                  <!-- todo text -->
                  <span class="text">Design a nice theme</span>
                  <!-- Emphasis label -->
                  <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                  <!-- General tools such as edit or delete-->
                  <div class="tools">
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash-o"></i>
                  </div>
                </li>
               
              </ul>
            </div>
            <div class="box-footer clearfix no-border">
              <button type="button" class="btn btn-default pull-right">More info <i class="fa fa-arrow-circle-right"></i></button>
               
            </div>
          </div>
        </div>
      </div>
     {{-- @include('calendar.calendar_show') --}} 
    <br>
        @include('calendar.calendar_show')
    </br>
     </div>

	</section>
<script>
    $(document).ready(function(){
      var filestack_id = "{{Auth::user()->filestack_id}}";
      var parent_id = "{{Auth::user()->parent_id}}";
      if(parent_id == ''){
        user_filestackCreate(filestack_id);
      }
    });

</script>
@endsection


