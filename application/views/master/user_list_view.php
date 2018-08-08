<div class="container-fluid">
    <div class="block-header">
        <h2>
            DATA USER            
        </h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>                        
                        <button type="button" class="btn btn-primary waves-effect" id="add-btn"
                            data-toggle="modal" data-target="#user-modal-add">
                            <i class="material-icons">add_circle_outline</i>
                            <span>Tambah User</span>
                        </button>
                    </h2>                    
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table id="datatables-list" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>             
                                    <th>ID</th>                               
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Role</th> 
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
                <h4 class="modal-title" id="modal-title-del">Konfirmasi Hapus</h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="alert alert-danger hidden" id="err-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                </div>
                <form id="user-form-del" action="">
                    <input type="hidden" id="del-id"/>
                    <label for="username-del">Apakah Anda ingin menghapus data <span class="del-name"></span></label>                                                                
                </form>
            </div><!--modal body-->

            <div class="modal-footer">                
                <button type="button" class="btn btn-primary waves-effect" id="btn-delete">
                    <i class="material-icons">save</i>
                    <span>Save</span>
                </button>
                <button type="button" class="btn btn-danger waves-effect" id="btn-cancel">
                    <i class="material-icons">clear</i>
                    <span>Cancel</span>
                </button>
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
</div>

<div class="modal fade" id="user-modal-add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title-add">Tambah User Baru</h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="alert alert-danger hidden" id="err-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                </div>
                <form id="user-form-add" action="">
                    <label for="name-add">Nama</label>
                    <span class="cd-error-message font-bold col-pink" id="err-name-add"></span>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="name-add" data-label="#err-name-add" class="form-control" placeholder="Masukan nama user">
                        </div>
                    </div>                    
                    <label for="username-add">Username</label>
                    <span class="cd-error-message font-bold col-pink" id="err-username-add"></span>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" autocomplete="off" id="username-add" data-label="#err-username-add" class="form-control" placeholder="Masukan username">
                        </div>
                    </div> 

                    <label for="role-add">Pilih Role</label>
                    <span class="cd-error-message font-bold col-pink" id="err-role-add"></span>
                    <div class="form-group">
                        <select class="form-control" id="role-add">                                
                            <option value="cashier">Kasir</option>
                            <option value="super admin">Super Admin</option>
                        </select>  
                    </div> 

                    <label for="password-add">Password</label>
                    <span class="cd-error-message font-bold col-pink" id="err-password-add"></span>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="password" id="password-add" data-label="#err-password-add" class="form-control" placeholder="Masukan password">
                        </div>
                    </div>    
                    <label for="confirm-password-add">Konfirmasi Password</label>
                    <span class="cd-error-message font-bold col-pink" id="err-confirm-password-add"></span>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="password" id="confirm-password-add" data-label="#err-confirm-password-add" class="form-control" placeholder="Masukan konfirmasi password">
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
<div class="modal fade" id="user-modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title-edit">Update User</h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="alert alert-danger hidden" id="err-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                </div>
                <form id="user-form-edit" action="">
                    <label for="name-edit">Nama</label>
                    <span class="cd-error-message font-bold col-pink" id="err-name-edit"></span>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="name-edit" data-label="#err-name-edit" class="form-control" placeholder="Masukan nama user">
                        </div>
                    </div>                    
                    <label for="username-edit">Username</label>
                    <span class="cd-error-message font-bold col-pink" id="err-username-edit"></span>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" autocomplete="off" id="username-edit" data-label="#err-username-edit" class="form-control" placeholder="Masukan username">
                        </div>
                    </div> 

                    <label for="role-edit">Pilih Role</label>
                    <span class="cd-error-message font-bold col-pink" id="err-role-edit"></span>
                    <div class="form-group">
                        <select class="form-control" id="role-edit">                                
                            <option value="cashier">Kasir</option>
                            <option value="super admin">Super Admin</option>
                        </select>  
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-default waves-effect" data-value=false id="btn-ch-pass">
                            <i class="material-icons">refresh</i>
                            <span>Ubah Password</span>
                        </button>
                    </div>

                    <div id="modul-ch-pass" class="hidden">
                        <label for="password-edit">Password</label>
                        <span class="cd-error-message font-bold col-pink" id="err-password-edit"></span>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="password" id="password-edit" data-label="#err-password-edit" class="form-control" placeholder="Masukan password">
                            </div>
                        </div>    
                        <label for="confirm-password-edit">Konfirmasi Password</label>
                        <span class="cd-error-message font-bold col-pink" id="err-confirm-password-edit"></span>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="password" id="confirm-password-edit" data-label="#err-confirm-password-edit" class="form-control" placeholder="Masukan konfirmasi password">
                            </div>
                        </div>
                    </div> 
                    <input type="hidden" id="user-id-edit">
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
                "url": "user/dataUserListAjax",
                "type": "POST"
            },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('id', aData[1]);
            },
            columns: [
                { data: 0,"width": "10%" },
                { data: 2, "width": "35%"},
                { data: 3, "width": "35%"},
                { data: 4, "width": "20%"},
                { data: 5, "width": "20%"}               
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ -1 ], //last column
                    "orderable": false,//set not orderable
                    "className": "dt-center",
                    "createdCell": function (td, cellData, rowData, row, col) {
                        var $btn_edit = $("<button>", { class:"btn btn-primary btn-xs edit-btn","type": "button",
                            "data-toggle":"modal","data-target":"#user-modal-edit","data-value": rowData[1],
                            "data-name": rowData[2],"data-username": rowData[3],"data-role": rowData[4]});
                        $btn_edit.append("<span class='glyphicon glyphicon-pencil'></span>&nbsp Edit");                       

                        var $btn_del = $("<button>", { class:"btn btn-danger btn-xs del-btn","type": "button",
                            "data-value": rowData[1],"data-name": rowData[2]});
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

            if(!$('#username-add').validateRequired()) {
                err++;
            }
            if(!$('#name-add').validateRequired()) {
                err++;
            }
            if(!$('#role-add').validateRequired()) {
                err++;
            }
            
            var errPass = 0;
            if(!$('#password-add').validateRequired()){
                err++;
                errPass++;
            }
            if(!$('#confirm-password-add').validateRequired()){
                err++;
                errPass++;
            }
            if(errPass==0){
                var pass = $('#password-add').val();
                var confirm_pass = $('#confirm-password-add').val();
                if(pass!=confirm_pass){
                    alertify.set('notifier', 'position', 'bottom-right');
                    alertify.error('Password dan Konfirmasi Password tidak cocok !');
                    err++;
                }
            }

            if (err != 0) {
                return false;
            } else {
                return true;
            }
        }
        function validateEdit() {
            var err = 0;
            var chPass = $("#btn-ch-pass").attr("data-value");

            if(!$('#username-edit').validateRequired()) {
                err++;
            }
            if(!$('#name-edit').validateRequired()) {
                err++;
            }
            if(!$('#role-edit').validateRequired()) {
                err++;
            }
            
            if(chPass=="true"){
                var errPass = 0;
                if(!$('#password-edit').validateRequired()){
                    err++;
                    errPass++;
                }
                if(!$('#confirm-password-edit').validateRequired()){
                    err++;
                    errPass++;
                }
                if(errPass==0){
                    var pass = $('#password-edit').val();
                    var confirm_pass = $('#confirm-password-edit').val();
                    if(pass!=confirm_pass){
                        alertify.set('notifier', 'position', 'bottom-right');
                        alertify.error('Password dan Konfirmasi Password tidak cocok !');
                        err++;
                    }
                }
            }
            
            if (err != 0) {
                return false;
            } else {
                return true;
            }
        }        
        
        $("#datatables-list tbody").on( "click", "button.del-btn", function() {
            $("#confirm-delete-modal").modal("show");
            var delId=$(this).attr("data-value");
            var delName=$(this).attr("data-name");
            $("#del-id").val(delId);
            $(".del-name").text(delName);

        });
        $("#btn-cancel").click(function(){
            $("#confirm-delete-modal").modal("hide");
        })

        $("#datatables-list tbody").on( "click", "button.edit-btn", function() {
            console.log("asdasd");
            $('#user-form-edit')[0].reset();
            $('#err-master-name-edit').text("");
            
            var id_item =  $(this).attr("data-value");
            var $tr =  $(this).closest("tr");
            var $td =  $(this).closest("td");
            var name = $(this).attr("data-name");
            var username = $(this).attr("data-username");
            var role = $(this).attr("data-role");
            var created = $td.find('div.item-info').attr("data-created");
            var last_modified = $td.find('div.item-info').attr("data-last-modifed");
            
            $('#username-edit').val(username);
            $('#name-edit').val(name);
            //$('#role-edit option[value="'+role+'"]').attr('selected','selected');
            $("#role-edit").val(role).change();            
            $('#user-id-edit').val(id_item);

            $('#created').empty();
            $('#created').append("Created : "+"<b>"+created+"</b>");
            $('#last_modified').empty();
            $('#last_modified').append("Last Modified : "+"<b>"+last_modified+"</b>");

        });

        var saveDataEvent = function(e) {
            if (validate()) {
                var formData = new FormData();
                formData.append("username", $("#username-add").val());
                formData.append("name", $("#name-add").val());
                formData.append("role", $("#role-add").val());
                formData.append("password", $("#password-add").val());    

                $(this).saveData({
                    url: "<?php echo site_url('user/createUser')?>",
                    data: formData,
                    locationHref: "<?php echo site_url('user')?>",
                    hrefDuration : 1000
                });
            }
            e.preventDefault();
        };

        var updateDataEvent = function(e){
            if (validateEdit()) {
                var formData = new FormData();
                formData.append("id", $("#user-id-edit").val());
                formData.append("name", $("#name-edit").val());  
                formData.append("username", $("#username-edit").val());              
                formData.append("role", $("#role-edit").val());
                
                var chPass = $("#btn-ch-pass").attr("data-value");
                if(chPass=="true"){
                    formData.append("chpass", true);
                    formData.append("password", $("#password-edit").val());                    
                }else{
                    formData.append("chpass", false);
                    formData.append("password", "");
                }

                $(this).saveData({
                    url: "<?php echo site_url('user/editUser')?>",
                    data: formData,
                    locationHref: "<?php echo site_url('user')?>",
                    hrefDuration : 1000
                });
            }
            e.preventDefault();
        };

        $("#btn-ch-pass").click(function(){            
            var value = $(this).attr("data-value");
            $("#password-edit").val("");
            $("#confirm-password-edit").val("");
            if(value=="false"){
                $(this).attr("data-value","true");
                $(this).addClass("btn-warning");
                $(this).removeClass("btn-default");
                $("#modul-ch-pass").removeClass("hidden");
            }else{
                $(this).attr("data-value","false");
                $(this).addClass("btn-default");
                $(this).removeClass("btn-warning");
                $("#modul-ch-pass").addClass("hidden");
            }
        });

        // SAVE DATA TO DB
        $('#btn-save').click(saveDataEvent);
        $("#user-form-add").on("submit", saveDataEvent);

        // UPDATE DATA TO DB
        $('#btn-update').click(updateDataEvent);
        $("#user-form-edit").on("submit", updateDataEvent);
    });
</script>