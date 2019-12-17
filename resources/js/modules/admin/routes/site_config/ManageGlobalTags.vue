<template>
	<div class="card">
		<div class="card-header">
			<div class="card-title">
				<button class="btn btn-sm btn-primary" @click="env.formOpen = !env.formOpen">
					<i class="fe fe-plus"></i> Add New</button>
			</div>
			<div class="card-options">
				<a href="#" class="mr-3 mt-2" @click.prevent="newTagMode = !newTagMode">
					<i v-show="!newTagMode" class="fa fa-plus"></i>
					<i v-show="newTagMode" class="fa fa-minus"></i>
				</a>
				<span v-if="newTagMode">
					<input type="text" class="form-control" v-model="tag.type">
				</span>
				<span v-if="!newTagMode">
					<multiselect v-model="selectedTag" :options="distinct_tags" :preserve-search="true" placeholder="Select a Tag" label="tag" track-by="tag">
					</multiselect>
				</span>
			</div>
		</div>
		<div class="card-body">
			<div v-if="env.formOpen" class="col-10 offset-1">
				<form action="">
					<div class="row">
						<div class="form-group col-2">
							<input type="text" placeholder="Ident" class="form-control" @keypress="isNumber($event)" v-model="tag.ident">
							<div class="validation-message" v-text="validation.getMessage('ident')"></div>
						</div>
						<div class="form-group col-8">
							<input type="text" placeholder="Title" class="form-control" v-model="tag.name">
							<div class="validation-message" v-text="validation.getMessage('title')"></div>
						</div>

						<div class="form-group col-2">
							<input type="submit" @click.prevent="handleSubmit" class="btn btn-success form-control">
						</div>
					</div>
					
				</form>
			</div>
			<div class="card">
				<div class="card-header">
					<div class="card-title">Master Tags</div>
					<div class="card-options">
            <input type="text" class="form-control form-control-sm" placeholder="Search something..." name="s">
					</div>
				</div>
				<div class="card-body">
					<table class="table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Ident</th>
								<th>Name</th>
								<th>Tag</th>
								<th>Created At</th>
								<th>Updated At</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="tag in curatedList" :key="tag.id">
								<td>{{ tag.id }}</td>
								<td>{{ tag.ident }}</td>
								<td>{{ tag.name }}</td>
								<td>{{ tag.tag }}</td>
								<td>{{ tag.created_at }}</td>
								<td>{{ tag.updated_at }}</td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
	</div>
</template>

<script>
	import Validation from '../../../../utils/Validation.js';
	let BASE_URL = '/admin/site-config';
	export default {
		data() {
			return {
				validation: new Validation(),
				newTagMode: false,
				tags: [],
				distinct_tags: [],
				selectedTag: {},
				tag: this.emptyTagForm(),
				env: {
					formOpen: false
				}
			}
		},
		created() {
			window.axios.get(`${BASE_URL}/tags`).then(response => {
				this.tags = response.data.tags;
				this.distinct_tags = response.data.distincts;
			}).catch(error => console.log(error.response.data));
		},
		methods: {
			isNumber: function(evt) {
	      evt = (evt) ? evt : window.event;
	      var charCode = (evt.which) ? evt.which : evt.keyCode;
	      if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
	        evt.preventDefault();;
	      } else {
	        return true;
	      }
	    },
			emptyTagForm() {
				return {
					name: '',
					ident: '',
					type: '',
				}
			},
			handleSubmit() {
				if(this.tag.type.length > 0 || typeof this.selectedTag.tag !== "undefined")
				{
					let param = (this.tag.type.length > 0) ? this.tag.type : this.selectedTag.tag;
					this.submit(param);
				}	else {
					Vue.swal('Select the tag!');
				}
			},
			submit(tag) {
				window.axios.post(`${BASE_URL}/tags`, {
					title: this.tag.name,
					ident: this.tag.ident,
					tag: tag
				}).then(response => {
					if(response.data=='dulpicate ident'){
						Vue.swal({
						  type: 'warning',
						  title: 'Duplicate Ident',
						  text: "This Ident has already used.",	
						})
					}else if(response.data=='dulpicate Name'){
						Vue.swal({
						  type: 'warning',
						  title: 'Duplicate Title',
						  text: "This Title has already used.",	
						})
					}else{
					 Vue.toasted.success(`Created Successfully!!`, {
			      	duration: 2000
			      });
						this.curatedList.push(response.data);
						this.tag = this.emptyTagForm();
					}
				}).catch(error => {
	        if (error.response.status == 422) {
	          this.validation.setMessages(error.response.data.errors);
	        }else{
	        	console.log(error.response.data);
	        }
	      });
			}
		},
		computed: {
			curatedList() {
				let response = [];
				this.tags.forEach(e => {
				  if(e.tag === this.selectedTag.tag) {
				  	response.push(e)
				  }
				});

				return response;
			}
		}
	}
</script>