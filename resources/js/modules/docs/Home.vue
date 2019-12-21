<template>
	<div>
	<div class="row mb-3">
		<div class="col-md-12 col-xs-12 col-sm-12 m-auto">
			<div class="card">
				<div class="card-body">
					<div class="row mb-5">
						<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 mb-5">
							<!-- <div class="input-group"> -->
								<input type="text" class="form-control" 
								placeholder="Search a filestack..." 
								v-model="searchKey" 
								@keyup="handleSearch">
							<!-- </div> -->
						</div>

						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
							<a class="text-muted pull-right text-decoration-none" href="filestack-mgmt" 
							v-if="logged_user.parent_id == null">
							<i class="fe fe-unlock"></i> Admin  
							</a>
							<span class="pull-right mr-5 ml-5" v-if="logged_user.parent_id == null"> | </span>
							<a class="text-muted pull-right text-decoration-none" 
							v-scroll-to="{ element: '#stack-'+logged_user.filestack_id, duration: 2000 }" ref="scrollBtn">
							<i class="fe fe-user"></i> My Folder
							</a>
						</div>
					</div>
					


				</div>
			</div>
		</div>
	</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-5" 
						v-for="gstack in gstacks" :key="gstack.id">

							<a v-if="(JSON.parse(gstack.permissions)).users.indexOf(logged_user.id) > -1"
							:href="`/docs/stacks/${gstack.id}`" class="text-decoration-none">
							
							<div class="card">
								<div class="card-status bg-blue"></div>
								<div class="card-body">
									<div class="media">
										<div class="media-left">
											<span class="avatar avatar-xxl avatar-blue mr-5"><i class="fe fe-globe"></i></span>					
										</div>								
										<div class="media-body">
											<h4 class="m-0" v-text="gstack.title"></h4>
											<ul class="social-links list-inline mb-0 mt-2">
												<li class="list-item">
												<a class="nav-link icon">
														      <i class="fe fe-folder"></i>
														    </a>
														    <span v-text="gstack.folder_count"></span>
												</li>
												<li class="list-item"> 
												<a class="nav-link icon">
														      <i class="fe fe-file-text"></i>
														    </a>
														    <span v-text="gstack.file_count"></span>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							</a>
						</div>		
					</div>
					<hr>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-5" 
						v-for="stack in stacks" :key="stack.id">

							<a :href="`/docs/stacks/${stack.id}`" v-bind:id="'stack-' + stack.id" class="text-decoration-none">
								<div class="card">

									<div class="card-status bg-green" v-if="stack.id === logged_user.filestack_id"></div>
									<div class="card-body">

										<div class="media">
											<div class="media-left">
												<span class="avatar avatar-xxl mr-5" :class="getRandomClass()">{{ getInitials(stack.title) }}</span>												
											</div>
										<!-- <span v-if="avatars[stack.user_owns.id] != ''" class="avatar avatar-xxl mr-5" :style="{'background-image': 'url('+ avatars[stack.user_owns.id]+')'}"></span> -->
										
										<div class="media-body">
										<h4 class="m-0" v-text="stack.title"></h4>
											<ul class="social-links list-inline mb-0 mt-2">
												<li class="list-item">
													<a class="nav-link icon">
													<i class="fe fe-folder"></i>
													</a>
													<span v-text="stack.folder_count"></span>
												</li>
												<li class="list-item"> 
													<a class="nav-link icon">
													<i class="fe fe-file-text"></i>
													</a>
													<span v-text="stack.file_count"></span>
												</li>
											</ul>
										</div>
										</div>
									</div>
								</div>
							</a>
						</div>		
					</div>



		
		
	  
		
	</div>
</template>

<script>
	const VueScrollTo = require('vue-scrollto');
	Vue.use(VueScrollTo)
	export default {
		props: ['people', 'general_stacks', 'filestacks', 'logged_user'],
		data() {
			return {
				admins: [],
				avatars: [],
				stacks: [],
				gstacks: [],
				searchKey: '',
				// test:[],
			}
		},
		created() {
			// this.getAvatars();
			this.stacks = this.filestacks;
			this.gstacks = this.general_stacks;


			// var d = this.gstacks.map((e) => {
			// 	return (JSON.parse(e.permissions)).users.indexOf(this.logged_user.id) > -1;
			// })
			// console.log(d);

			// this.stacks.forEach(function(e) {
			// 	e.folder_count = e.file_count = 0;
			// });
			// this.gstacks.forEach(function(e) {
			// 	e.folder_count = e.file_count = 0;
			// });
		},
		mounted() {
			// this.scrollEvent();
			// axios.get('/docs/get-counts').then(response => {
			// 	let counts = response.data;
			// 	counts.forEach()
			// 	this.stacks.forEach(function(e) {
			// 		e.folder_count = response.data.folder_count[];
			// 		e.file_count = response.data.folder_count;
			// 	});
			// }).catch(error => console.log(error.response.data));
		},
		methods: {
      getInitials(name) {
        let initials = name.match(/\b\w/g) || [];
  
        return ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
      },
			getRandomClass() {
			  let items = ['avatar-blue', 'avatar-azure','avatar-indigo','avatar-purple', 'avatar-pink','avatar-red',
			  'avatar-orange', 'avatar-yellow', 'avatar-lime', 'avatar-green', 'avatar-teal', 'avatar-cyan'];
			  return items[Math.floor(Math.random()*items.length)];
			},
			// getAvatars() {
			//   let ids = [];
			//   this.people.forEach(function(e){
			//     ids.push(e.id);
			//   });
			//   axios.post('/user/profile/get_avatars', {users: ids}).then(response => {
			//     console.log(response.data);
			//     this.avatars = response.data;
			//   }).catch(error => console.log(error.response.data));
			// },
			// gatePass(id) {
			// 	switch(id) {
			// 	  case 63:
			// 	    if(this.tender_docs_users.indexOf(this.logged_user.id) > -1)
			// 	    	return true;
			// 	    else
			// 	    	return false;
			// 	    break;
			// 	  case 64:
			// 	    if(this.consultancy_docs_users.indexOf(this.logged_user.id) > -1)
			// 	    	return true;
			// 	    else
			// 	    	return false;
			// 	    break;
			// 	  default:
			// 	    return true;
			// 	}
			// },
			scrollEvent($event) {
        const elem = this.$refs.scrollBtn;
        elem.click();
      },
			handleSearch() {
				let keyword = this.searchKey;
				if(keyword.length === 0){
					this.stacks = this.filestacks;
				}else{
					axios.get(`/docs/stacks/search/${keyword}`).then(response => {
						this.stacks = response.data;
					}).catch(error => console.log(error.response.data));
				}
				
			},

			// refreshStacks() {
			// 	axios.get('/docs/stacks').then(response => {
			// 			this.stacks = response.data;
			// 		}).catch(error => console.log(error.response.data));
			// }
		}
	}
</script>

<style scoped>
	
</style>