<style>
    .delete-border{
        border:2px solid #F44336;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Update Barang                        
                    </h2>                    
                </div>
                <div class="body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation" class="active"><a href="#item-info" data-toggle="tab">INFORMASI BARANG</a></li>                        
                        <li role="presentation"><a href="#price-list" data-toggle="tab">HARGA BARANG</a></li>                        
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="item-info">
                            <div class="body">
                                <form>
                                    <input type="hidden" id="item-edit-id" value="<?php echo $item['id'];?>"/>
                                    <label for="email_address">Barcode <span class="col-pink">*</span></label>
                                    <span class="cd-error-message font-bold col-pink" id="err-barcode"></span>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="barcode" data-label="#err-barcode" class="form-control" placeholder="Masukan data barcode" value="<?php echo $item['barcode'];?>">
                                        </div>
                                    </div>
                                    <label for="password">Nama Barang <span class="col-pink">*</span></label>
                                    <span class="cd-error-message font-bold col-pink" id="err-item-name"></span>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="item-name" data-label="#err-item-name" class="form-control" placeholder="Masukan nama barang" value="<?php echo $item['name'];?>">
                                        </div>
                                    </div>
                                    <label for="item-desc">Deskripsi Barang</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="4" id="item-desc" class="form-control no-resize" placeholder="Please type what you want..."><?php echo $item['description'];?></textarea>
                                        </div>
                                    </div>

                                    <label for="stock">Stok saat ini<span class="col-pink">*</span></label>
                                    <span class="cd-error-message font-bold col-pink" id="err-stock"></span>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="stock" data-label="#err-stock" class="numeric form-control" placeholder="Masukan stock" value="<?php echo $item['qty_stock'];?>" >
                                        </div>
                                    </div> 
									<label for="item-supplier-price">Harga Satuan Barang Supplier <span class="col-pink">*</span></label>
                                    <span class="cd-error-message font-bold col-pink" id="err-item-supplier-price"></span>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="item-supplier-price" data-label="#err-item-supplier-price" class="numeric form-control numeric" placeholder="Masukan harga supplier" value="<?php echo $item['price_supplier'];?>">
                                        </div>
                                    </div>
                                    <label for="item-customer-price">Harga Jual Customer<span class="col-pink">*</span></label>
                                    <span class="cd-error-message font-bold col-pink" id="err-item-customer-price"></span>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="item-customer-price" data-label="#err-item-customer-price" class="numeric form-control numeric" value="<?php echo $item['price_customer'];?>" placeholder="Masukan harga jual">
                                        </div>
                                    </div>                               
                                    <button type="button" class="btn btn-primary m-t-15 waves-effect btn-next-tab" data-next="price-list">LANJUT</button>
                                </form>
                            </div>
                        </div>                        
                        <div role="tabpanel" class="tab-pane fade" id="price-list">
                            <div class="body">      
                                <button type="button" id="add-discount-btn" class="btn btn-primary waves-effect">Tambah Discount</button>
                                <br/><br/>
                                <span class="font-bold col-pink" id="err-price-item-list"></span>                      
                                <form class="price-item-container">
                                    <?php foreach($discount as $row){?>
                                        <div class="row price-item-list" data-old="true" data-id="<?php echo $row['id'];?>" data-del="false">
                                            <div class="col-sm-2">
                                                <b>Qty</b>
                                                <div class="input-group">
                                                    <div class="form-line">
                                                        <input type="text" value="<?php echo $row['qty_for_discount'];?>" class="numeric form-control price-item-qty">
                                                    </div>                                           
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <b>Harga Jual</b>
                                                <div class="input-group">
                                                    <div class="form-line">
                                                        <input type="text" value="<?php echo $row['discount'];?>" class="numeric form-control price-item-price">
                                                    </div>                                           
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove-discount" data-old="true" data-del="false">
                                                    <i class="material-icons">clear</i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php } ?>                                                                        
                                </form>
                                <div class="row hidden" id="price-item-element" data-old="false">
                                    <div class="col-sm-2">
                                        <b>Qty</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" class="numeric form-control price-item-qty">
                                            </div>                                           
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <b>Harga Jual</b>
                                        <div class="input-group">
                                            <div class="form-line">
                                                <input type="text" class="numeric form-control price-item-price">
                                            </div>                                           
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float remove-discount" data-old="false">
                                            <i class="material-icons">clear</i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary m-t-15 waves-effect" id="btn-save">SIMPAN</button>
                            </div>
                        </div><!--Price Tabs-->                        
                    </div>
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
    
    $(".btn-next-tab").click(function(){
        var tabs = $(this).attr("data-next");
        var selector = '.nav-tabs a[href="#'+tabs+'"]'; 
        $(selector).tab('show');
    });

    function validate() {
        console.log("adad");
        var err = 0;

        if (!$('#barcode').validateRequired()) {
            err++;
        }
        if (!$('#item-name').validateRequired()) {
            err++;
        }
        if (!$('#stock').validateRequired()) {
            err++;
        }       
        if (!$('#item-customer-price').validateRequired()) {
            err++;
        }        
        
        $("#err-price-item-list").text("");
        var countDiscount=0;
        var discountQtyArr = [];
        $(".price-item-list").each(function(){
            var qty = $(this).find(".price-item-qty").val();
            var price = $(this).find(".price-item-price").val();
            
            if(countDiscount==0){
                if(qty=="" && price==""){
                    
                }else{
                    if(qty<=1){
                        $("#err-price-item-list").text("Qty harus lebih dari 1...");
                        err++;
                        return false;
                    }else if(price==""){
                        $("#err-price-item-list").text("Masih terdapat data harga yang kosong..");
                        err++;
                        return false;
                    } else{
                        discountQtyArr.push(qty);
                    }
                }
                countDiscount++;
            }else{
                if(qty=="" || price==""){
                    $("#err-price-item-list").text("Masih terdapat data harga yang kosong..");
                    err++;
                    return false;
                }else{
                    var found = $.inArray(qty, discountQtyArr);
                    if(found== -1){                        
                        if(qty<=1){
                            $("#err-price-item-list").text("Qty harus lebih dari 1...");
                            err++;
                            return false;
                        }else{
                            discountQtyArr.push(qty);
                        }                        
                    }else{
                        $("#err-price-item-list").text("Qty ke "+qty+" sudah terdapat dalam discount..");
                        err++;
                        return false;                        
                    }
                }
                countDiscount++;
            }            
        });

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
        $($copyElement).addClass("price-item-list");
        $($copyElement).removeClass("hidden");
        $($copyElement).removeAttr("id");
        $($copyElement).find(".price-item-qty").val("");
        $($copyElement).find(".price-item-price").val("");
        $($copyElement).appendTo(".price-item-container");
    });

    $(".remove-discount").click(function(){
        var oldData = $(this).attr("data-old");
        if(oldData=="true"){
            var dataDel = $(this).closest(".price-item-list").attr("data-del");
            if(dataDel=="true"){
                $(this).closest(".price-item-list").removeClass("delete-border");
                $(this).closest(".price-item-list").attr("data-del","false");
            }else{
                $(this).closest(".price-item-list").addClass("delete-border");
                $(this).closest(".price-item-list").attr("data-del","true");
            }            
        }else{
            $(this).closest(".price-item-list").remove();
        }
        
    });

    function getAllData(){
        var priceList=[];
        $(".price-item-list").each(function(){
            var qty = $(this).find(".price-item-qty").val();
            var price = $(this).find(".price-item-price").val();
            var flag = $(this).attr("data-old");                
            
            if(qty!="" && price!=""){
                if(flag=="true"){
                    var del = $(this).attr("data-del");            
                    var id = $(this).attr("data-id");
                    priceList.push({
                        "qty":qty,
                        "price":price,
                        "oldData":true,
                        "del":del,
                        "id":id
                    });
                }else{
                    priceList.push({
                        "qty":qty,
                        "price":price,
                        "oldData":false                    
                    });
                }
            }                        
        });

        var formData = new FormData();
        formData.append("id", $("#item-edit-id").val());
        formData.append("barcode", $("#barcode").val());
        formData.append("name", $("#item-name").val());
        formData.append("qty_stock", $("#stock").val());
        formData.append("description", $("#item-desc").val());
        formData.append("price_customer", $("#item-customer-price").val());
		formData.append("price_supplier", $("#item-supplier-price").val());
        formData.append("item_price_list", JSON.stringify(priceList));
        
        return formData;
        console.log(formData);
    }

    $("#btn-save").click(function(){        
        if(validate()){
           var formData = getAllData();
            $.ajax({
                url: "<?php echo site_url('item/editItem')?>",
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