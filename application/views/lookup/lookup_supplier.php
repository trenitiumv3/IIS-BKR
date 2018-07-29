<!--Modal ADD-->
<div class="modal fade" id="lookup-supplier-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Master Supplier</b></h4>
            </div>
            <!--modal header-->

            <div class="modal-body">
                <table id="datatables-lookup-supplier" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Supplier</th>
                            <th>Deskripsi</th>
                            <th>Pilih</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <!--modal body-->

            <div class="modal-footer">
            </div>
            <!--modal footer-->

        </div>
        <!--modal content-->
    </div>
    <!--modal dialog-->
</div>

<script>
    $(document).ready(function() {
        var baseurl = "<?php echo site_url();?>/";
        var selected = [];
        var table = "";

        $("#lookup-supplier-btn").click(function() {
            table = $('#datatables-lookup-supplier').DataTable({
                "lengthChange": false,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                "autoWidth": false,
                deferRender: true,
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": baseurl + "supplier/dataSupplierListAjax",
                    "type": "POST"
                },
                "fnCreatedRow": function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-id', aData[1]);
                },
                columns: [{
                    data: 0,
                    "width": "10%"
                }, {
                    data: 2,
                    "width": "40%"
                }, 
                {
                    data: 3,
                    "width": "40%"
                },
                {
                    data: 1,
                    "width": "10%"
                }],
                "fnHeaderCallback": function(nHead, aData, iStart, iEnd, aiDisplay) {
                    
                },
                //Set column definition initialisation properties.
                "columnDefs": [{
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "className": "dt-center",
                    "createdCell": function(td, cellData, rowData, row, col) {
                        var $btn_add = $("<button>", {
                            class: "btn btn-primary btn-xs add-supplier-btn",
                            "type": "button",
                            "data-value": cellData,
                            "data-label": rowData[2]
                        }).css("width", "100%");
                        $btn_add.append("<span class='glyphicon glyphicon-plus'></span>&nbsp Add");

                        $(td).html($btn_add);
                    }
                }, {
                    "targets": [0], //last column
                    "orderable": false //set not orderable}
                }]

            });

        });

        $('#lookup-supplier-modal').on('hidden.bs.modal', function() {
            //$('#dataTables-poli').html("");
            $('#datatables-lookup-supplier').dataTable().fnDestroy();
        })
    });
</script>