@extends('admin.main')
@section('content')
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="">Edit Profession <a href="{{route('qual_doc_type.index')}}" class="btn btn-sm btn-primary pull-right">Back</a></h3>
					</div>
					<div class="box-body">
						<form action="{{route('qual_doc_type.update',['id'=>$doc_type->id])}}" method="post" >
							@method('PATCH')
							@csrf 
							<div class="row form-group">							
								<div class="col-md-6">
									<label for="">Profession Name <span class="text-danger">*</span>
									</label>
									<input type="text" class="form-control" name="name" required="" placeholder="Enter Profession Name" value="{{old('name') ?? $doc_type->name}}"> 
									@error('name')
										<span class="text-danger">
											<strong>{{$message}}</strong>
										</span>
									@enderror
					            </div>
					             <div class="col-md-6">
									<label for="shrt_desc">Short Name</label>
									<input type="text" class="form-control" name="shrt_desc" placeholder="Enter short name" value="{{old('shrt_desc') ?? $doc_type->shrt_desc}}">
									@error('shrt_desc')
										<span class="text-danger">
											<strong>{{$message}}</strong>
										</span>
									@enderror
					            </div>
							</div>					
							<div class="row ">
								<div class="col-md-12 ">
									<input type="submit" value="Submit" class="btn btn-sm btn-primary">
								</div>								
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
