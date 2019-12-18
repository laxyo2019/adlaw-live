@extends(Auth::user()->user_catg_id == 2 ? 'lawfirm.main' : (Auth::user()->user_catg_id == 3 ? 'lawfirm.main' : (Auth::user()->user_catg_id == 4 ? 'lawschools.main' : (Auth::user()->user_catg_id == 6 ? 'lawschools.main' : (Auth::user()->user_catg_id == 7 ? 'lawschools.main' : (Auth::use()->user_catg_id == 5 ? 'customer.main' : 'admin.main'))))))
@push('links')
	<script src="{{ asset('js/app.js') }}" defer></script>
@endpush
@section('content')
<section class="content" >
<docs-home 
      :people="{{ json_encode($users) }}"
      :general_stacks="{{ json_encode($general_stacks) }}"
      :filestacks="{{ json_encode($filestacks) }}" 
      :logged_user="{{ json_encode(auth()->user()) }}">      
</docs-home>
 
</section>

@endsection