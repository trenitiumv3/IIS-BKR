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
                                    <th>Supplier</th>
                                    <th>Status</th>                                        
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

<div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title-add">Konfirmasi Hapus</h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="alert alert-danger hidden" id="err-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                </div>
                <form id="supplier-form-add" action="">
                    <input type="hidden" id="del-id"/>
                    <label for="supplier-name-add">Apakah Anda ingin menghapus data <span class="del-name"></span></label>                                                                
                </form>
            </div><!--modal body-->

            <div class="modal-footer">                
                <button type="button" class="btn btn-primary waves-effect" id="btn-delete">
                    <i class="material-icons">save</i>
                    <span>Ya</span>
                </button>
                <button type="button" aria-label="close" data-dismiss="alert" class="btn btn-danger waves-effect" id="btn-cancel">
                    <i class="material-icons">clear</i>
                    <span>Cancel</span>
                </button>
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
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
                { data: 4, "width": "10%"},
                { data: 5, "width": "10%"},
                { data: 6, "width": "10%"},
                { data: 7, "width": "10%"},              
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ -1 ], //last column
                    "orderable": false,//set not orderable
                    "className": "dt-center",
                    "createdCell": function (td, cellData, rowData, row, col) {                    
                        var $btn_edit = $("<button>", { class:"btn btn-primary btn-xs edit-btn","type": "button",
                            "data-value": rowData[9]});
                        $btn_edit.append("<span class='glyphicon glyphicon-pencil'></span>&nbsp Edit");                       

                        var $btn_del = $("<button>", { class:"btn btn-danger btn-xs del-btn","type": "button",
                            "data-value": rowData[9], "data-name": rowData[2]});
                        $btn_del.append("<span class='glyphicon glyphicon-remove'></span>&nbsp Hapus");
                       
                        $(td).html($btn_edit).append(" ").append(" ").append($btn_del);
                    }
                },
                {
                    "targets": [6],
                    "className": "dt-center",
                    "createdCell": function (td, cellData, rowData, row, col) {                    
                        if(rowData[6]=='3'){
                            $(td).html("<span class='label label-success'>active</span>");
                        }else{
                            $(td).html("<span class='label label-danger'>delete</span>");
                        }                        
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

        $("#datatables-list tbody").on( "click", "button.del-btn", function() {                      
            $("#confirm-delete-modal").modal("show");
            var delId=$(this).attr("data-value");
            var delName=$(this).attr("data-name");
            $("#del-id").val(delId);
            $(".del-name").text("\""+delName+"\"");
            
        });

        $("#btn-delete").click(function(){
            var id=$("#del-id").val();            
            var formData = new FormData();
            formData.append("item_id", id);

            $.ajax({
                url: "<?php echo site_url('item/deleteItem')?>",
                data: formData,
                type: "POST",
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#load_screen").show();
                },
                success: function(data) {
                    if (data.status != 'error') {
                        $("#load_screen").hide();
                        $(".modal").hide();
                        alertify.set('notifier', 'position', 'bottom-right');
                        alertify.success(data.msg);
                        window.setTimeout(function() {
                            location.href = "<?php echo site_url('item')?>";
                        }, 2000);
                    } else {
                        $("#load_screen").hide();
                        alertify.set('notifier', 'position', 'bottom-right');
                        alertify.error(data.msg);
                    }
                },
                error: function(xhr, status, error) {
                    //var err = eval("(" + xhr.responseText + ")");
                    //alertify.error(xhr.responseText);
                    $("#load_screen").hide();
                    alertify.set('notifier', 'position', 'bottom-right');
                    alertify.error('Cannot response server !');
                }
            });
        });

        $("#btn-cancel").click(function(){
            $("#confirm-delete-modal").modal('hide');
        });
    });
</script>