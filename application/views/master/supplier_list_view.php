<div class="container-fluid">
    <div class="block-header">
        <h2>
            JQUERY DATATABLES
            <small>Taken from <a href="https://datatables.net/" target="_blank">datatables.net</a></small>
        </h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        DATA SUPPLIER&nbsp&nbsp
                        <button type="button" class="btn btn-primary waves-effect" id="add-btn"
                            data-toggle="modal" data-target="#supplier-modal-add">
                            <i class="material-icons">add_circle_outline</i>
                            <span>Tambah Supplier</span>
                        </button>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table id="datatables-list" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>             
                                    <th>ID</th>                               
                                    <th>Nama Supplier</th>
                                    <th>Deskripsi</th>
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

<div class="modal fade" id="supplier-modal-add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title-add">Tambah Supplier Baru</h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="alert alert-danger hidden" id="err-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                </div>
                <form id="supplier-form-add" action="">
                    <label for="supplier-name-add">Nama Supplier</label>
                    <span class="cd-error-message font-bold col-pink" id="err-supplier-name-add"></span>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="supplier-name-add" data-label="#err-supplier-name-add" class="form-control" placeholder="Masukan nama supplier">
                        </div>
                    </div>                    
                    <label for="supplier-desc-add">Deskripsi Supplier</label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea rows="4" id="supplier-desc-add" class="form-control no-resize" placeholder="Please type what you want..."></textarea>
                        </div>
                    </div>                                            
                </form>
            </div><!--modal body-->

            <div class="modal-footer">                
                <button type="button" class="btn btn-primary waves-effect" id="btn-save">
                    <i class="material-icons">save</i>
                    <span>Save</span>
                </button>
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
</div>

<!--Modal EDIT-->
<div class="modal fade" id="supplier-modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title-edit">Update Supplier</h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="alert alert-danger hidden" id="err-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                </div>
                <form id="supplier-form-edit" action="">
                    <label for="supplier-name-edit">Nama Supplier</label>
                    <span class="cd-error-message font-bold col-pink" id="err-supplier-name-edit"></span>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="supplier-name-edit" data-label="#err-supplier-name-edit" class="form-control" placeholder="Masukan nama supplier">                            
                        </div>
                    </div>                    
                    <label for="supplier-desc-edit">Deskripsi Supplier</label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea rows="4" id="supplier-desc-edit" class="form-control no-resize" placeholder="Please type what you want..."></textarea>
                        </div>
                    </div>  
                    <input type="hidden" id="supplier-id-edit">
                </form>
            </div><!--modal body-->

            <div class="modal-footer">
                <p id="created"></p>
                <p id="last_modified"></p>
                <button type="button" class="btn btn-primary waves-effect" id="btn-update">
                    <i class="material-icons">save</i>
                    <span>Update</span>
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
                "url": "supplier/dataSupplierListAjax",
                "type": "POST"
            },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('id', aData[1]);
            },
            columns: [
                { data: 0,"width": "10%" },
                { data: 2, "width": "35%"},
                { data: 3, "width": "35%"},
                { data: 4, "width": "20%"}               
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ -1 ], //last column
                    "orderable": false,//set not orderable
                    "className": "dt-center",
                    "createdCell": function (td, cellData, rowData, row, col) {
                        var $btn_edit = $("<button>", { class:"btn btn-primary btn-xs edit-btn","type": "button",
                            "data-toggle":"modal","data-target":"#supplier-modal-edit","data-value": rowData[1]});
                        $btn_edit.append("<span class='glyphicon glyphicon-pencil'></span>&nbsp Edit");                       

                        var $btn_del = $("<button>", { class:"btn btn-danger btn-xs del-btn","type": "button",
                            "data-value": rowData[1]});
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

        function validate() {
            var err = 0;

            if (!$('#supplier-name-add').validateRequired()) {
                err++;
            }

            if (err != 0) {
                return false;
            } else {
                return true;
            }
        }
        function validateEdit() {
            var err = 0;

            if (!$('#supplier-name-edit').validateRequired()) {
                err++;
            }

            if (err != 0) {
                return false;
            } else {
                return true;
            }
        }        

        $("#datatables-list tbody").on( "click", "button.edit-btn", function() {
            console.log("asdasd");
            $('#supplier-form-edit')[0].reset();
            $('#err-master-name-edit').text("");
            
            var id_item =  $(this).attr("data-value");
            var $tr =  $(this).closest("tr");
            var $td =  $(this).closest("td");
            var name = $tr.find('td').eq(1).text();
            var text = $tr.find('td').eq(2).text();
            var created = $td.find('div.item-info').attr("data-created");
            var last_modified = $td.find('div.item-info').attr("data-last-modifed");
            
            $('#supplier-name-edit').val(name);
            $('#supplier-desc-edit').val(text);
            $('#supplier-id-edit').val(id_item);

            $('#created').empty();
            $('#created').append("Created : "+"<b>"+created+"</b>");
            $('#last_modified').empty();
            $('#last_modified').append("Last Modified : "+"<b>"+last_modified+"</b>");

        });

        var saveDataEvent = function(e) {
            if (validate()) {
                var formData = new FormData();
                formData.append("name", $("#supplier-name-add").val());
                formData.append("desc", $("#supplier-desc-add").val());

                $(this).saveData({
                    url: "<?php echo site_url('supplier/createSupplier')?>",
                    data: formData,
                    locationHref: "<?php echo site_url('supplier')?>",
                    hrefDuration : 1000
                });
            }
            e.preventDefault();
        };

        var updateDataEvent = function(e){
            if (validateEdit()) {
                var formData = new FormData();
                formData.append("id", $("#supplier-id-edit").val());
                formData.append("name", $("#supplier-name-edit").val());
                formData.append("desc", $("#supplier-desc-edit").val());

                $(this).saveData({
                    url: "<?php echo site_url('supplier/editSupplier')?>",
                    data: formData,
                    locationHref: "<?php echo site_url('supplier')?>",
                    hrefDuration : 1000
                });
            }
            e.preventDefault();
        };

        // SAVE DATA TO DB
        $('#btn-save').click(saveDataEvent);
        $("#supplier-form-add").on("submit", saveDataEvent);

        // UPDATE DATA TO DB
        $('#btn-update').click(updateDataEvent);
        $("#supplier-form-edit").on("submit", updateDataEvent);
    });
</script>