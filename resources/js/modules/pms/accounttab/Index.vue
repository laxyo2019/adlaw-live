<template>
	<div class="accounttab__wrapper">

		<!-- Buttons -->
		<div class="row">
			<div class="form-group col-8">
				<button type="button"
		  		class="btn btn-pill btn-primary" @click="env.createEntry = !env.createEntry">
		  		<i class="fe fe-plus"></i> New Entry
		  	</button>
			</div> 

		  <div class="col-4">
	  	  <div class="input-group">
	  	    <input type="text" class="form-control" placeholder="Search a entry..." v-model="filter.searchKey" 
	  	    	@keyup="runFilter">
	  	    <span class="input-group-btn ml-2">
	  	      <button class="btn btn-sm btn-default" @click="runFilter">
	  	        <span class="fe fe-search"></span>
	  	      </button>
	  	    </span>
		     	<span class="input-group-btn ml-2">
		        <button class="btn btn-sm btn-default" @click="emptyFilter()">
		          <span class="fe fe-refresh-cw"></span>
		        </button>
		      </span>
	  	  </div>
	  	</div>
    </div>

    <!-- Form -->
		<span v-if="env.createEntry">
			<div class="row pl-2 pr-2 pt-5">
				<div class="form-group col-12">
					<label for=""><b>Entry Title</b></label>
	  			<input type="text" name="title" class="form-control" v-model="entry.title" required autofocus>
					<!-- <div class="validation-message" v-text="validation.getMessage('title')"></div> -->
	  		</div>

	  		<div class="form-group col-4">
					<label for=""><b>Entry Type</b></label>
	  			<select class="form-control" v-model="entry.type">
	  				<option value="">-- Select Type --</option>
	  				<option value="1">Invoice</option>
	  				<option value="2">Bill</option>
	  			</select>
					<!-- <div class="validation-message" v-text="validation.getMessage('title')"></div> -->
				</div>
				<div class="form-group col-4">
					<label for=""><b>Entry Amount</b></label>
	  			<input type="text" name="title" class="form-control" v-model="entry.amt" required>
					<!-- <div class="validation-message" v-text="validation.getMessage('title')"></div> -->
				</div>
				<div class="form-group col-4">
					<label for=""><b>Entry Date</b></label>
	  			<flat-pickr class="form-control" v-model="entry.date" :config="dateConfig"></flat-pickr>
					<!-- <div class="validation-message" v-text="validation.getMessage('title')"></div> -->
	  		</div>

				<div class="form-group col-12">
					<label for=""><b>Entry Description</b></label>
					<vue-editor v-model="entry.description" :editor-toolbar="customToolbar" />
					<!-- <div class="validation-message" v-text="validation.getMessage('description')"></div> -->
	  		</div>

	  		<div class="form-group col-12 text-center">
	  			<span>
	  				<button type="submit" 
	  					class="btn btn-pill btn-success" 
	  					:class="{ 'btn-loading': storing }" 
	  					@click.prevent="entryStore()"
	  					v-if="env.createEntry">Create
	  				</button>
	  			</span>

	  			<span>
			  		<button type="button" class="btn btn-pill btn-outline-dark ml-2" 
			  			@click="entry = emptyEntryForm(); env.createEntry = false">Cancel
			  		</button>
	  			</span>
	  		</div>
			</div>
		</span>

		<!-- Table -->
		<div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Invoices</h3>
        </div>
        <div class="table-responsive">
          <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer"><div class="dataTables_length" id="DataTables_Table_0_length"><label>Show <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class=""><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div><div id="DataTables_Table_0_filter" class="dataTables_filter"><label>Search:<input type="search" class="" placeholder="" aria-controls="DataTables_Table_0"></label></div><table class="table card-table table-vcenter text-nowrap datatable dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
            <thead>
              <tr role="row">
              	<th class="w-1">#</th>
              	<th class="w-1">No.</th>
              	<th>Invoice Subject</th>
              	<th>Client</th>
              	<th>VAT No.</th>
              	<th>Created</th>
              	<th>Status</th>
              	<th>Amount</th>
              	<th></th>
              	<th></th>
              </tr>
            </thead>
            <tbody>
            	<tr role="row" class="odd">
								<td>#</td>
                <td><span class="text-muted">001401</span></td>
                <td><a href="invoice.html" class="text-inherit">Design Works</a></td>
                <td>
                  Carlson Limited
                </td>
                <td>
                  87956621
                </td>
                <td>
                  15 Dec 2017
                </td>
                <td>
                  <span class="status-icon bg-success"></span> Paid
                </td>
                <td>$887</td>
                <td class="text-right">
                  <a href="javascript:void(0)" class="btn btn-secondary btn-sm">Manage</a>
                  <div class="dropdown">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">Actions</button>
                  </div>
                </td>
                <td>
                  <a class="icon" href="javascript:void(0)">
                    <i class="fe fe-edit"></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table><div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to 10 of 16 entries</div><div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate"><a class="paginate_button previous disabled" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0" id="DataTables_Table_0_previous">Previous</a><span><a class="paginate_button current" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0">1</a><a class="paginate_button " aria-controls="DataTables_Table_0" data-dt-idx="2" tabindex="0">2</a></span><a class="paginate_button next" aria-controls="DataTables_Table_0" data-dt-idx="3" tabindex="0" id="DataTables_Table_0_next">Next</a></div></div>
        </div>
      </div>
    </div>
	</div>
</template>

<script>
	import flatPickr from 'vue-flatpickr-component';
  import 'flatpickr/dist/flatpickr.css';
  import { VueEditor } from 'vue2-editor';
  import Validation from '../../../utils/Validation.js';
	export default {
		props: ['team', 'board', 'logged_user', 'meta'],
		components: {
	  	flatPickr,
	  	VueEditor,
	  },	  
		data() {
			return {
				storing: false,
				env: {
					createEntry: false
				},
				filter: this.emptyFilter(),
				entry: this.emptyEntryForm(),
				customToolbar: [
				  ["bold", "italic", "underline"],
				  [{ list: "ordered" }, { list: "bullet" }],
				  ["image", "code-block"]
				],
				validation: new Validation(),
				dateConfig: {dateFormat: "d-m-Y" },
			}
		},
		methods: {
			emptyEntryForm() {
				return {
					title: '',
					description: '',
					type: '',
					amt: 0,
					date: new Date()
				}
			},
			isEmpty(obj) {
		    for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
		    }
		    return true;
			},
			entryStore() {
				this.storing = true;
				let postData = this.entry;
				axios.post('/pms/accounts', postData).then(response => {
					console.log(response.data);
				}).catch(error => console.log(error.response.data));
			},
			runFilter() {

			},
			emptyFilter() {
				return {
					searchKey: ''
				}
			}
		}
	}
</script>