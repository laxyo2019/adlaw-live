@extends('layouts.tabler')
@section('title', 'TeamChat')

@section('navbar')
  @include('partials._tabler.navbar')
@endsection

@push('links')
{{-- 	<script src="{{ asset('tabler/assets/js/require.min.js') }}"></script>
	<script>
		requirejs.config({
				baseUrl: '/tabler'
		});
	</script> --}}

	<script src="{{ asset('tabler/assets/js/vendors/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('tabler/assets/js/vendors/bootstrap.bundle.min.js') }}"></script>

	<script src="{{ asset('js/app.js') }}" defer></script>
	{{-- <script src="{{ asset('tabler/assets/js/dashboard.js') }}"></script> --}}

	<link href="{{ asset('custom/custum.css') }}" rel="stylesheet" type="text/css">

	<style>
		footer {
		  display: none;
		}
		.status{
		  position: absolute;
		  right: 10px;
		}
		.usersList .list-group-item{
		  padding: 0px;
		}
	</style>

	<script>
	// require(['jquery'], function($) {
		// $(window).load(function() {
		  
		// });
	  $(document).on('click', '.groupusersmodel', function () {
	  	$("html, body").animate({ scrollTop: $(document).height() }, 1000);
	    var id = $(this).attr('data-id');
	    $.ajax({
	        url: "{{ route('getGroupMembers',$team->id) }}",
	        type: "GET",
	        dataType: "json",
	        success: function (data) {
	            var html='<ul class="list-group">';
	            var baseurl = '{{ url('') }}';
	            // console.log(baseurl);  
	            $.each(data, function (index, value) {
	              var img = 
	              html+='<li class="list-group-item"><table style="width:100%;"><tr><td style="width:20%"><img class="img" width="50px" src="/custom/alphabets/'+ value.user_name.toUpperCase().charAt(0) +'.png"></td><td style="width:60%;"><p style="line-height:3.5;margin-bottom:0px;" >'+ value.user_name +'</p></td></tr></table></li>';  
	            });
	            html+='</ul>';
	            $('.usersList').html(html);
	            $('#groupMembersModal').modal('show');
	        },
	        error: function () {
	        },
	    });

	  });
	// });
  </script>
@endpush

@section('content')
  <div class="container">
  	<div class="col-8 offset-2">
  		<div class="card mb-n2">
				<div class="card-header">
					<a href="{{ route('team.index', $team->id) }}"><i class="fa fa-arrow-left"></i></a>
					<span class="card-title mx-auto">
						<b><u>{{ $team->name }} <i class="fa fa-arrow-right"></i> Team Chat</u></b>
					</span>
	      </div>
  		</div>
  	</div>
		<div class="col-xs-12 col-sm-12 col-md-10 offset-md-1 col-lg-10 offset-lg-1 col-xl-10 offset-xl-1">
	    <div class="card mb-3">
	    	<div class="card-header">
	    		<div class="card-title">Teamchat</div>
	    		<div class="card-options">
	    			<button class="btn btn-pill btn-primary groupusersmodel">
	    				<span class="onlineuserscount">0</span> / <span class="totaluserscount"></span>
	    			</button>
	    		</div>
	    	</div>
	      <div class="card-body">
        	<team-chat :group="{{ $team }}" :group_users="{{ $team->users_in_team }}" 
          	:user="{{ auth()->user() }}"></team-chat> 
	      </div>
	    </div>  
	  </div>
  </div>
    <div class="modal fade" id="groupMembersModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
   <div class="modal-dialog" role="document">
     	<div class="modal-content">
        <div class="modal-header">
        	<span><b>Users</b></span>
          <button  type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body usersList"></div>
     	</div>
   </div>
</div>
@endsection