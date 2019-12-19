
function court(court_type,court_code){
	if(court_type != 0){
		$.ajax({ 
			type:"GET",
			url:"/court_category/"+court_type,
			success:function(res){   	
				if(res.length !=0){
					$("#court_code").empty();

					$("#court_code").append('<option value="0">Select Court Type</option>');
					$.each(res,function(key,value){
						$("#court_code").append('<option value="'+value.court_code+'" ' + (value.court_code == court_code ? 'selected="selected"' : '' )+ '>'+value.court_name+'</option>');
					});
				}
				else{
					$("#court_code").empty();		              
				}
			}
		});
	}
	else{
		$("#court_code").empty();
	}
}

function case_subcategory(catg_code,subcatg_code){

	if(catg_code != 0){
		$.ajax({
		   type:"GET",
		   url:"/case_subcategory?catg_code="+catg_code,
		   success:function(res){               
		    if(res){
		        $("#case_subcategory").empty();
		      
		        $.each(res,function(key,value){
		            $("#case_subcategory").append('<option value="'+value.subcatg_code+'" ' + (value.subcatg_code == subcatg_code ? 'selected="selected"' : '' )+ ' >'+value.subcatg_desc+'</option>');
		        });
		   
		    }else{
		       $("#case_subcategory").empty();
		    }
		   }
		});
	}else{
		$("#case_subcategory").empty();
	}
}

function case_court(court_type,court_code){
	if(court_type != 0){
		$.ajax({ 
			type:"GET",
			url:"/court_category/"+court_type,
			success:function(res){   	
				
				if(res.length !=0){
					if(court_type =='2'){
						$('#court_code_label').empty().html('High Court <span class="text-danger">*</span>');
					}
					else{
						$('#court_code_label').empty().html('Court Name <span class="text-danger">*</span>')
					}
					$("#court_code").empty();

					//$("#court_code").append('<option value="0">Select Court Type</option>');
					$.each(res,function(key,value){
						$("#court_code").append('<option value="'+value.court_code+'" ' + (value.court_code == court_code ? 'selected="selected"' : '' )+ '>'+value.court_name+'</option>');
					});
				}
				else{
					$("#court_code").empty();		              
				}
			}
		});
	}
	else{
		$("#court_code").empty();
	}
}
function state(state_code,city_code){
	 if(state_code !='0'){
        $.ajax({
           type:"GET",
           url:"/city_fetch?state_code="+state_code,
           success:function(res){                     
            if(res){
                $("#city").empty();
                $.each(res,function(key,value){
                    $("#city").append('<option value="'+value.city_code+'" '+(value.city_code == city_code ? 'selected="selected"' : '' )+'>'+value.city_name+'</option>');
                });
           
            }else{
               $("#city").empty();
            }
           }
        });
    }else{
        $("#city").empty();
    }
}



function state_fetch(country_code,state_id,state_code){

	if(country_code !='0'){
		$.ajax({
			type:'GET',
			url:'/state?country_id='+country_code,
			success:function(data){
				if(data){
					$(state_id).empty();
					$(state_id).append('<option value="">Select State</option>');	
					$.each(data,function(i,v){
						$(state_id).append('<option value="'+v.state_code+'" '+(v.state_code == state_code ? 'selected' : '')+'>'+v.state_name+'</option>');
					});
				}
				else{
					$(state_id).empty();
				}
			}
		})
	}
}
function city_fetch(state_code,city_code,city_id){

	 if(state_code !='0' || state_code !='' ){
        $.ajax({
           type:"GET",
           url:"/city_fetch?state_code="+state_code,
           success:function(res){                     
            if(res){
                $(city_id).empty();
                $.each(res,function(key,value){
                    $(city_id).append('<option value="'+value.city_code+'" '+(value.city_code == city_code ? 'selected="selected"' : '' )+'>'+value.city_name+'</option>');
                });
           
            }else{
               $(city_id).empty();
            }
           }
        });
    }else{
        $(city_id).empty();
    }
}




function case_court_select(court_code, court_type, no_catg, cnr){
	if(court_type =='1'){
		$('#no_catg_div').show();
		$('#state_city_div').hide();
		$('#no_div').hide();
		$('#case_type_div').hide();
		$('#court_code_div').hide();
		$('#cnr_div').hide();
		$('#cnr_number_div').hide();

		var sno_catg = $('#no_catg').val();
		var no_catg =( no_catg !='0' ? no_catg : sno_catg ) ;
		
		if(no_catg == 'd_no'){
			$('#no_label').empty().html('Diary Number <span class="text-danger">*</span>');
			$('#no_div').show();

			$('#case_type_div').hide();

		}	
		else if(no_catg == 'c_no'){
			$('#no_label').empty().html('Case Number <span class="text-danger">*</span>');
			$('#no_div').show();
			$('#case_type_div').show();
		}
		else{
			$('#no_div').hide();
		}
		$('#no_catg').on('change',function(e){
			e.preventDefault();
			var no_catg = $(this).val();
			// alert(no_catg);
			if(no_catg == 'd_no'){
				$('#no_label').empty().html('Diary Number <span class="text-danger">*</span>');
				$('input[name="c_d_number"]').val('');
				$('#err_c_d_number').html('');
				$('#no_div').show();
				$('#case_type_div').hide();

			}	
			else if(no_catg == 'c_no'){
				$('#no_label').empty().html('Case Number <span class="text-danger">*</span>');
				$('input[name="c_d_number"]').val('');
				$('#err_c_d_number').html('');
				$('#no_div').show();
				$('#case_type_div').show();
			}
			else{
				$('#no_div').hide();
			}
		});
	}else if(court_type =='0'){
		$('#court_code_div').hide();
		$('#no_catg_div').hide();
		$('#no_div').hide();
		$('#cnr_div').hide();
		$('#case_type_div').hide();
		$('#cnr_number_div').hide();
		$('#state_city_div').hide();
	}
	else if(court_type =='2' || court_type == '3'){	
		case_court(court_type,court_code);
		$('#no_catg_div').hide();	
		$('#no_div').hide();
		// $('#court_code_div').show();
		$('#cnr_div').show();
		var scnr = $('input[name="cnr"]:checked').val();

		var cnr = (cnr != '' ? cnr : scnr );

			
		if(cnr == '0'){
			if(court_type == '3'){
				$('#state_city_div').show();
				$('#court_code_div').hide();
			}
			else if(court_type == '2'){
				$('#court_code_div').show();
				$('#state_city_div').hide();
			}
				// $('#bench_code_div').show();
				$('#no_label').empty().html('Case Number <span class="text-danger">*</span>');
				$('#no_div').show();
				$('#cnr_number_div').hide();
				$('#case_type_div').show();
			}
			else{
				
				$('#state_city_div').hide();					
				$('#court_code_div').hide();
				
				$('#no_div').hide();
				$('#cnr_number_div').show();
				$('#court_code_div').hide();
				$('#case_type_div').hide();
				// $('#bench_code_div').hide();
			}
		$('input[name="cnr"]').on('change', function(e){
			e.preventDefault();
			var cnr = $(this).val();
				if(cnr == '0'){
					if(court_type == '3'){
						$('#state_city_div').show();
						$('#court_code_div').hide();
					}
					else if(court_type == '2'){
						$('#court_code_div').show();
						$('#state_city_div').hide();

					}
				
					// $('#bench_code_div').show();
					$('#no_label').empty().html('Case Number <span class="text-danger">*</span>');
					$('#no_div').show();
					$('#cnr_number_div').hide();
					$('#case_type_div').show();
			}
			else{
				
				$('#cnr_number_div').show();
				$('#state_city_div').hide();
				$('#no_div').hide();
				$('#court_code_div').hide();				
				$('#case_type_div').hide();
				// $('#bench_code_div').hide();
			}
		})
	}
	else{
		$('#cnr_number_div').show();	
		$('#cnr_div').hide();
		$('#court_code_div').hide();
		$('#no_div').hide();
		$('#case_type_div').hide();
		$('#state_city_div').hide();
	}
	
}

function case_table(case_status,case_status_text,cust_id){
	$.ajax({
		type:"GET",
		url: '/cases_table?case_status='+case_status+"&cust_id="+cust_id,
		success:function(res){
			var case_text = case_status_text + ' Cases';
 			$('#case_status_label').empty().html(case_text);
			$('#table_div').empty().html(res);
		}
	})
}
function hearing_members(case_id,auth_id){
	$.ajax({
		type:'GET',
		url : "/case_member?case_id="+case_id,
		success:function(res){
			if(res){
				$('.hearing_members').empty();
				$.each(res,function(key,value){
					$('.hearing_members').append('<option value="'+value.user_id1+'" '+(auth_id == value.user_id1 ? 'selected' : '') +' '+(auth_id == value.user_id1 ? 'locked="locked"' : '') +'>'+value.member.name+'</option>');
				});
			}else{
				$('.hearing_members').empty();
			}
		}
	});
}
function team_users(team_id,auth_id,auth_name){
	$.ajax({
		type:'GET',
		url: '/team_users?team_id='+team_id,
		success:function(res){
			if(team_id ==0){
				if(res){
					$('.team_users').empty();
					$('.team_users').append('<option value="'+auth_id+'" selected="selected" locked="locked">'+auth_name+'</option>');
					$.each(res,function(key,value){
						$('.team_users').append('<option value="'+value.id+'">'+value.name+'</option>');
					});
				}else{
					$('.team_users').empty();
				}
			}else{				
				if(res){
					$('.team_users').empty();				
					$.each(res,function(key,value){
						$('.team_users').append('<option value="'+value.user_id+'" '+(auth_id == value.user_id ? 'selected' : '') +'  '+(auth_id == value.user_id ? 'locked="locked"' : '') +'>'+value.users.name+'</option>');
					});
				}else{
					$('.team_users').empty();
				}
			}
		}
	});
}


function case_members(case_id1,auth_id,auth_name,assignee_user_id){
	case_id =case_id1;
	$.ajax({
		type:'GET',
		url : "/case_member?case_id="+case_id,
		success:function(res){
			// console.log(res);
			if(case_id ==0){
				if(res){
					$('.members_todo').empty();
					$('.members_todo').append('<option value="'+auth_id+'" '+(assignee_user_id == auth_id ? 'selected' : '')+' locked="locked">'+auth_name+'</option>')
					$.each(res,function(key,value){
						$('.members_todo').append('<option value="'+value.id+'"  '+(assignee_user_id == value.id ? 'selected' : '')+'>'+value.name+'</option>');
					});
				}else{
					$('.members_todo').empty();
				}
			}else{				
				if(res){

					$('.members_todo').empty();
				
					$.each(res,function(key,value){
						$('.members_todo').append('<option value="'+value.user_id1+'" '+(assignee_user_id != '' ? (assignee_user_id ==value.user_id1 ? 'selected' : '')  : (auth_id == value.user_id1 ? 'selected' : ''))+' '+(auth_id == value.user_id1 ? 'locked="locked"' : '') +'>'+value.member.name+'</option>');
					});
				}else{
					$('.members_todo').empty();
				}
			}
		}
	})
}
function qual_course(qual_catg_code,qual_code){
	// console.log(qual_catg_code);
	if(qual_catg_code!=''){
		$.ajax({
			type:'GET',
			url:'/qual_category?qual_catg_code='+qual_catg_code,
			success:function(res){
				// console.log(res);
				if(res){
					  $("#qual_course").empty();
					$('#qual_course').append('<option value="">Select Course</option>');
					$.each(res, function(key,value){
						$("#qual_course").append('<option value="'+value.qual_code+'" '+(value.qual_code == qual_code ? 'selected' : '')+'>'+value.qual_desc+'</option>');
					});
				}
				else{
					$('#qual_course').empty();
				}
			}
		});
	}
	else{
		$('#qual_course').empty();
	}
}

function qual_docs(qual_catg_code){
	if(qual_catg_code!=''){
		$.ajax({
			type:'GET',
			url:'/qual_docs?qual_catg_code='+qual_catg_code,
			success:function(data){
				if(data){
					$('#docs_type').empty();
					$.each(data,function(i,v){
						$('#docs_type').append('<tr><td><input type="text" value="'+v.qual_doc_type.name+'" readonly class="form-control" name="doc_name[]"></td><td class="error-di"><input type="file" name="doc_url[]" class="form-control" accept="application/pdf, image/*"><input type="hidden" name="doc_check[]" value="" class="doc_url"></td><td><input type="hidden" name="s_doc_url[]" value=""><input type="hidden" name="qual_doc_type_id[]" value="'+v.qual_doc_type.id+'"></td></tr>');
					});
				}else{
					$('#docs_type').empty();
				}
			}	
		});
	}
}


function user_filestackCreate(filestack_id){
	if(filestack_id == ''){
	var type  = 1;
	var title = '';
	var userTitle ='dashboard';
	$.ajax({
	  type:'POST',
	  url:"/filestacks",
	  data:{type:type,title:title,userTitle:userTitle},
	  success:function(res){
	      //console.log(res);
	  }
	});
	// console.log(userTitle);
	}
}