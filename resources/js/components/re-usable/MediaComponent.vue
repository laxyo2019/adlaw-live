<template>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<span class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4" v-for="file in media">
					<div class="text-center">
						<span class="border" v-if="image_mimes.indexOf(file.mime_type) > -1">
							<img height="150" width="150" class="img-responsive" 
								:src="file.url" :alt="file.file_name" />
						</span>

						<div v-else class="card">
							<div class="card-body p-3 text-center">
						 		<span>
						 			<a :href="file.url" class="text-decoration-none">
							 			<div class="h1 m-0"><i class="fa fa-file-audio-o"></i></div>
							 			<div class="mt-1 mb-1">{{file.file_name}} <br> 
							 				<span class="text-muted" style="font-size: 0.8em;">46 KB</span>
							 			</div>
						 			</a>
					 			</span>
							</div>		
						</div>

					</div>	
				</span>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['logged_user', 'post_id'],
		data() {
			return {
				media: [],
				image_mimes: ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'],
			}
		},
		mounted() {
			this.getMedia();
		},
		methods: {
			getMedia() {
				axios.get(`/pms/get-media-url/${this.post_id}`).then(response => {
					console.log('media', response.data);
					this.media = response.data;
				});
			}
		}
	}
</script>