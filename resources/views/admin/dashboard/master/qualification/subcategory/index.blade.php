@extends('admin.main')
@section('content')
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header">
						<h3 class="">Qualification  SubCategory <a href="{{route('qual_subcategory.create')}}" class="btn btn-sm btn-primary pull-right">Add Subcategory</a></h3>
						<div class="row form-group">
							<div class="col-md-4">
								<label>Filter</label>
								<select name="qual_catg_code" class="form-control">
									<option value="0">Select Qualification Category </option>
									@foreach($categories as $category)
										<option value="{{$category->qual_catg_code}}">{{$category->qual_catg_desc}}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-1">
								<label></label>
								<button class="btn btn-sm btn-info form-control" id="btnFilter">Filter</button>
							</div>
						</div>
					</div>
					<div class="box-body " id="tableDiv">
						@if($message = Session::get('success'))
							<div class="alert bg-success">
								{{$message}}
							</div>
						@endif
						@include('admin.dashboard.master.qualification.subcategory.table')
					</div>
				</div>
			</div>
		</div>
	</section>
<script type="text/javascript">
	$(document).ready(function(){
	
		$('#myTable').DataTable();

		$('#btnFilter').on('click',function(e){
				e.preventDefault();
				var qual_catg_code = $('select[name="qual_catg_code"] option:selected').val();
				if(qual_catg_code !=0){
					$.ajax({
						type:'post',
						url : '{{route('qual_subCategoryFilter')}}',
						data : {qual_catg_code:qual_catg_code},
						success:function(data){
							$('#tableDiv').empty().html(data);
						}
					});
				}
				else{
					alert('select qualification category')
				}
			});
    });
</script>
@endsection