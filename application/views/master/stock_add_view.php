<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Tambah Stock Barang                        
                    </h2>                    
                </div>
                <div class="body">
                    <form>
                        <label for="email_address">Barcode <span class="col-pink">*</span></label>
                        <span class="cd-error-message font-bold col-pink" id="err-barcode"></span>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" id="barcode" data-label="#err-barcode" class="form-control" placeholder="Masukan data barcode" autofocus>
                            </div>
                        </div>
                        <label for="password">Nama Barang <span class="col-pink"></span></label>
                        <span class="cd-error-message font-bold col-pink" id="err-item-name"></span>
                        <div class="form-group">
                            <div class="form-line">
                                <input disabled type="text" id="item-name" data-label="#err-item-name" class="form-control" placeholder="Masukan nama barang">
                            </div>
                        </div>
                        <label for="item-desc">Deskripsi Barang</label>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea disabled rows="4" id="item-desc" class="form-control no-resize" placeholder="Please type what you want..."></textarea>
                            </div>
                        </div>    
                        <label for="supplier-name">Pilih Supplier</label>
                        <span class="cd-error-message font-bold col-pink" id="err-supplier-select"></span>
                        <div class="form-group row">
                            <div class="col-sm-6 no-padding">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="supplier-select" data-label="#err-supplier-select" class="form-control" value="" disabled placeholder="Supplier">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 no-padding">
                                <button id="lookup-supplier-btn" type="button" class="btn btn-default waves-effect" data-toggle="modal" data-target="#lookup-supplier-modal">
                                    <i class="material-icons">search</i>
                                </button>
                            </div>
                        </div>               
                        <label for="stock">Stok <span class="col-pink">*</span></label>
                        <span class="cd-error-message font-bold col-pink" id="err-stock"></span>
                        <div class="form-group">
                            <div class="form-line">
                            <input type="text" id="stock" data-label="#err-stock" class="numeric form-control" placeholder="Masukan stock">
                            </div>
                        </div>
                        <label for="item-supplier-price">Harga Satuan Supplier <span class="col-pink">*</span></label>
                        <span class="cd-error-message font-bold col-pink" id="err-item-supplier-price"></span>
                        <div class="form-group">
                            <div class="form-line">
                            <input type="text" id="item-supplier-price" data-label="#err-item-supplier-price" class="numeric form-control numeric" placeholder="Masukan harga barang">
                            </div>
                        </div>
                        <input type="hidden" id="item-id">
                        <button type="button" id="btn-save" class="btn btn-primary m-t-15 waves-effect">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    $this->load->view("lookup/lookup_supplier");
?>

<script>
    $(document).on("input", ".numeric", function() {
        this.value = this.value.replace(/\D/g,'');
    });
    
    function validate() {
        console.log("adad");
        var err = 0;

        if (!$('#barcode').validateRequired()) {
            err++;
        }        
        if (!$('#stock').validateRequired()) {
            err++;
        }
        if (!$('#item-supplier-price').validateRequired()) {
            err++;
        }
        if (!$('#supplier-select').validateRequired()) {
            err++;
        }                

        if (err != 0) {
            alertify.set('notifier', 'position', 'bottom-right');
            alertify.error("Data belum terisi dengan benar, Silahkan dicek kembali");
            return false;
        } else {
            return true;
        }
    }

    $("#add-discount-btn").click(function(){
        var $copyElement = $("#price-item-element").clone(true);
        $($copyElement).removeAttr("id");
        $($copyElement).find(".price-item-qty").val("");
        $($copyElement).find(".price-item-price").val("");
        $($copyElement).appendTo(".price-item-container");
    });

    function getAllData(){       
        var formData = new FormData();
        formData.append("item_id", $("#item-id").val());        
        formData.append("qty_stock", $("#stock").val());
        formData.append("supplier", $("#supplier-select").attr("data-value"));
        formData.append("price_supplier", $("#item-supplier-price").val());                
        
        return formData;
        console.log(formData);
    }

    $('#barcode').blur(function(){
        var data = $(this).val();
        console.log(data);
        if(data != ""){
            $.ajax({
                url: "<?php echo site_url('item/getItemDetailByBarcode')?>"+"/"+data,                
                type: "GET",
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,               
                success: function(data) {
                    if (data.status == 'success') {
                        $("#item-name").val(data.content.name);
                        $("#item-desc").val(data.content.description);
                        $("#item-id").val(data.content.id);
                    } else {    
                        $("#item-name").val("");
                        $("#item-desc").val("");
                        $("#item-id").val("");                    
                        alertify.set('notifier', 'position', 'bottom-right');
                        alertify.error(data.msg);
                    }
                },
                error: function(xhr, status, error) {
                    //var err = eval("(" + xhr.responseText + ")");
                    //alertify.error(xhr.responseText);
                    $("#item-name").val("");
                    $("#item-desc").val("");
                    $("#item-id").val("");
                    alertify.set('notifier', 'position', 'bottom-right');
                    alertify.error('Cannot response server !');
                }
            });
        }        

    });

    $("#btn-save").click(function(){        
        if(validate()){
           var formData = getAllData();
            $.ajax({
                url: "<?php echo site_url('item/insertStockItem')?>",
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

        }
    });

    $("#datatables-lookup-supplier tbody").on( "click", "button.add-supplier-btn", function() {            
        var id = $(this).attr("data-value");
        var label = $(this).attr("data-label");
        
        $("#supplier-select").val(label);
        $("#supplier-select").attr("data-value",id);

        $('#lookup-supplier-modal').modal('hide');

    });

</script>