<div class="container-fluid">
    <div class="block-header">
        <h2>
            DATA ITEM            
        </h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>                        
                        <a href="<?php echo site_url('item/goToAddItem');?>">
                            <button type="button" class="btn btn-primary waves-effect" id="add-btn"
                                data-toggle="modal" data-target="#supplier-modal-add">
                                <i class="material-icons">add_circle_outline</i>
                                <span>Tambah Item</span>
                            </button>
                        </a>
                    </h2>                  
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table id="datatables-list" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>             
                                    <th>No</th> 
                                    <th>Barcode</th>                               
                                    <th>Nama Item</th>
                                    <th>Deskripsi</th>
                                    <th>Stock</th>                                            
                                    <th>Action</th>                                            
                                </tr>
                            </thead>                                    
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->           
</div>

<script>
    $(function() {
        var baseurl = "<?php echo site_url();?>/";
        var selected = [];
				
        var table = $('#datatables-list').DataTable({
            "lengthChange": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "autoWidth": false,
            deferRender: true,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "item/dataItemListAjax",
                "type": "POST"
            },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('id', aData[1]);
            },
            columns: [
                { data: 0,"width": "5%" },
                { data: 1, "width": "20%"},
                { data: 2, "width": "20%"},
                { data: 3, "width": "20%"},
                { data: 4, "width": "20%"},
                { data: 5, "width": "15%"} ,              
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ -1 ], //last column
                    "orderable": false,//set not orderable
                    "className": "dt-center",
                    "createdCell": function (td, cellData, rowData, row, col) {                    
                        var $btn_edit = $("<button>", { class:"btn btn-primary btn-xs edit-btn","type": "button",
                            "data-value": rowData[8]});
                        $btn_edit.append("<span class='glyphicon glyphicon-pencil'></span>&nbsp Edit");                       

                        var $btn_del = $("<button>", { class:"btn btn-danger btn-xs del-btn","type": "button",
                            "data-value": rowData[8]});
                        $btn_del.append("<span class='glyphicon glyphicon-remove'></span>&nbsp Hapus");
                       
                        $(td).html($btn_edit).append(" ").append(" ").append($btn_del);
                    }
                },
                {
                    "targets": [0], //last column
                    "orderable": false//set not orderable}
                }
            ]

        });

        $("#datatables-list tbody").on( "click", "button.edit-btn", function() { 
            var id=$(this).attr("data-value");           
            window.setTimeout(function() {
                location.href = "<?php echo site_url('item/goToEditItem')?>"+"/"+id;
            }, 200);
        });
    });
</script>