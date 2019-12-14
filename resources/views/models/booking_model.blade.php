<div class="modal modal-fade " id="BtnViewModal">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Book an Appointment</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
      <div class="modal-body">
        <form action="{{route('book_an_appointment')}}" method="post">
          @csrf
        <div class="row form-group">
          <div class="col-md-12">
            <label>Booking Date</label>
            <input type="text"  value="" name="b_date" class="form-control" id="bookingDate" data-date-format="yyyy-mm-dd" placeholder="{{date('Y-m-d')}}" required="">
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-12">
            <label>Booking Time</label>
            <select class="form-control" name="plan_id">
            
              @foreach($slots as $slot)
                <option value="{{$slot->id}}">{{ date('h:i A', strtotime($slot->slot)) }}</option>
              @endforeach
            </select>
        

          </div>
        </div>
    
        <div class="row form-group">
          <div class="col-md-12">
          
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="client_id" value="{{(Auth::user()) ? Auth::user()->id : null }}">
             @if(Auth::user())                 
                <button type="submit" class="btn btn-info">Submit</button>
             @else
                <button type="button" class="btn btn-info" data-dismiss="modal" id="submitBtn" >Submit</button>
             @endif
          </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">        
        
      </div>
    </div>
  </div>              
</div>