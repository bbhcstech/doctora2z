


 <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<!-- Load jQuery first -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>-->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{ asset('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>

<!-- Vendor Scripts -->
<script src="{{ asset('admin/assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/quill/quill.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('admin/assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<!--DataTable-->


<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#example2').DataTable({
        deferRender: true, // Render only the visible rows
        responsive: true,  // Make the table responsive
        scroller: true,    // Enable Scroller for large datasets
        pageLength: 25,    // Set the default number of rows per page
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        order: [[0, 'desc']], // Default sorting on the first column in descending order
        dom: 'Bfrtip',       // Include buttons and other elements
        buttons: [
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
});
$(document).ready(function() {
    var table = $('#example1').DataTable({
        pageLength: 25,
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,
        order: [[0, 'asc']],  // Sort by the first column in descending order (adjust if needed)
        dom: 'Bfrtip',
        buttons: [
            // 'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
});
$(document).ready(function() {
    var table = $('#example3').DataTable( {
        pageLength: 25,
        rowReorder: {
            selector: 'td:nth-child(5)'
        },
        responsive: true,
         order: [[4, 'desc']],
         dom: 'Bfrtip',
        buttons: [
            // 'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    } );
   
} );

$(document).ready(function() {
    var table = $('#example4').DataTable( {
        pageLength: 25,
        rowReorder: {
            selector: 'td:nth-child(0)'
        },
        responsive: true,
         order: [[0, 'desc']],
         dom: 'Bfrtip',
        buttons: [
            // 'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    } );
   
} );

$(document).ready(function() {
    var table = $('#example5').DataTable( {
        pageLength: 25,
        rowReorder: {
            selector: 'td:nth-child(0)'
        },
        responsive: true,
         order: [[1, 'desc']],
         dom: 'Bfrtip',
        buttons: [
            // 'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    } );
   
} );
</script>
<!--DataTable-->




<!-- Custom Main Script -->
<script src="{{ asset('admin/assets/js/main.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: 'Type here...',
            height: 300, // Set editor height
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
    
    
    document.addEventListener('keydown', function (e) {
    if (e.key === 'Backspace' && window.location.pathname === '/dashboard') {
        e.preventDefault();
        alert('Please complete OTP verification first.');
    }
});
</script>
</body>

</html>