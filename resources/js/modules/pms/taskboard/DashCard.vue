<template>
	<div class="card">
    <div class="card-header">
      <h3 class="card-title" v-text="title"></h3>
      <div class="card-options">
        <form action="">
          <div class="input-group">
            <input type="text" class="form-control form-control-sm" placeholder="Search something..." name="s">
            <span class="input-group-btn ml-2">
              <button class="btn btn-sm btn-default" type="submit">
                <span class="fe fe-search"></span>
              </button>
            </span>
          </div>
        </form>
      </div>
    </div>
    <div class="card-body">
      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima neque pariatur perferendis sed suscipit velit vitae voluptatem. A consequuntur, deserunt eaque error nulla temporibus!
    </div>
  </div>
</template>

<script>
	export default {
		props: ['title'],
		data() {
			return {
				searchKey: ''
			}
		},
		methods: {
			search() {
				let keyword = this.searchKey;
				if(keyword.length > 0) {
					console.log('test');
				}
			}
		}
	}
</script>

<style scoped>
	
</style>