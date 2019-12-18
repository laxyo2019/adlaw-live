 </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
   
    <strong>Copyright &copy; 2018 <a href="">ADLAW</a>.</strong> All rights
    reserved.
  </footer>

 
  <aside class="control-sidebar control-sidebar-dark">

    <div class="tab-content">
    
      <div class="tab-pane" id="control-sidebar-home-tab">
        
    </div>
  </aside>

  <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->


    <script src="{{asset('adlaw_files/js/bootstrap.min.js')}}"></script>

    <script src="{{asset('adlaw_files/js/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('adlaw_files/js/adlaw.min.js')}}"></script>
    <!-- Sparkline -->
    <script src="{{asset('adlaw_files/js/jquery.sparkline.min.js')}}"></script>
    <script src="{{asset('adlaw_files/js/jquery-jvectormap-1.2.2.min.js')}}"></script>
    <script src="{{asset('adlaw_files/js/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{asset('adlaw_files/js/jquery.slimscroll.min.js')}}"></script>
    {{-- <script src="{{asset('js/adlaw/js/jquery.sparkline.min.js')}}"></script> --}}
    <!-- jvectormap  -->

    <!-- SlimScroll -->
    {{-- <script src="bower_components/jquery-slimscroll/s"></script> --}}
    <!-- ChartJS -->
    {{-- <script src="bower_components/chart.js/Chart.js"></script> --}}
    {{-- AdminLTE dashboard demo (This is only for demo purposes) --}}
    {{-- <script src="{{asset('adlaw/js/dashboard2.js')}}"></script> --}}
    <!-- AdminLTE for demo purposes -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.0/moment.min.js"></script>
    <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>

    <script src="{{asset('js/parts-selector.js')}}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.4/croppie.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="{{asset('adlaw_files/js/demo.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>

    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>



    <script src="{{asset('js/all_category.js')}}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        tinymce.init({

            selector: "textarea.tinymce",
            plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",

            "   directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent |  forecolor backcolor ",

            height: 300,
        });
    </script>
</body>
</html>
