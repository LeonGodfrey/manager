
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 ==== -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="{{ asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Bootstrap Switch -->
<script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<!-- BS-Stepper -->
<script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- dropzonejs -->
<script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
{{-- <!-- <script src="{{ asset('dist/js/demo.js') }}"></script> --> --}}
<script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>



 <script>
   $(function () {
  bsCustomFileInput.init();
});
  
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask yyyy-mm-dd
    $('#datemask').inputmask('yyyy-mm-dd', {
      'placeholder': 'yyyy-mm-dd'
    })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', {
      'placeholder': 'mm/dd/yyyy'
    })
    //Money Euro
    $('[data-mask]').inputmask()
    
    //Date picker
    $('#reservationdate').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: moment(),
        maxDate: moment(),
        allowInputToggle: true,
        // daysOfWeekDisabled: [0]
    });

    //Date picker
    $('#reservationdate1').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: moment(),
        maxDate: moment(),
        allowInputToggle: true,
        // daysOfWeekDisabled: [0]
    });

    //Date picker
    $('#reservationdate2').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: moment(),
        maxDate: moment(),
        allowInputToggle: true,
        // daysOfWeekDisabled: [0]
    });

    //Date picker
    $('#reservationdate3').datetimepicker({
        format: 'YYYY-MM-DD',
       // defaultDate: moment(),
        maxDate: moment(),
        allowInputToggle: true,
        // daysOfWeekDisabled: [0]
    });

    $('.datepicker').each(function() {
    $(this).datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: moment(),
        maxDate: moment(),
        allowInputToggle: true,
    });
});


  })

// appraisal images
  $(document).ready(function() {
    $('.product-image-thumb').on('click', function () {
      var $image_element = $(this).find('img')
      $('.product-image').prop('src', $image_element.attr('src'))
      $('.product-image-thumb.active').removeClass('active')
      $(this).addClass('active')
    })
  })

  //data table script
  //data  table export without filtering
  $("#example1").DataTable({
    "responsive": false,
    "lengthChange": false,
    "ordering": false,
    "searching": false,
    "paging": false,
    "autoWidth": false,
    "buttons": ["excel", "print"]
  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  //data table export with filtering
  $("#example2").DataTable({
    "responsive": false,
    "lengthChange": false,
    "ordering": false,
    "searching": true,    
    "info": false,
    "paging": false,
    "autoWidth": false,
    "buttons": ["excel", "print"]
  }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');


  $('#example3').DataTable({
    "paging": false,
    "lengthChange": false,
    "searching": true,
    "ordering": false,
    "info": true,
    "autoWidth": false,
    "responsive": true,
  });

  
        // Count Up Animation
        document.addEventListener('DOMContentLoaded', function() {
            const countUpElements = document.querySelectorAll('.count-up');

            countUpElements.forEach((element) => {
                const targetValue = parseFloat(element.getAttribute('data-value'));
                const duration = 600; // Animation duration in milliseconds

                const startTimestamp = performance.now();
                const update = (currentTimestamp) => {
                    const elapsed = currentTimestamp - startTimestamp;
                    const progress = elapsed / duration;

                    if (progress < 1) {
                        const animatedValue = progress * targetValue;
                        element.textContent = animatedValue.toLocaleString(undefined, {
                            minimumFractionDigits: 1
                        });
                        requestAnimationFrame(update);
                    } else {
                        element.textContent = targetValue.toLocaleString(undefined, {
                            minimumFractionDigits: 0
                        });
                    }
                };

                requestAnimationFrame(update);
            });
        });
    

</script>

</body>
</html>