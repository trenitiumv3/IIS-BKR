<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Tambah Barang Baru                        
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
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation" class="active"><a href="#item-info" data-toggle="tab">INFORMASI BARANG</a></li>
                        <li role="presentation"><a href="#stock-info" data-toggle="tab">STOK BARANG</a></li>    
                        <li role="presentation"><a href="#price-list" data-toggle="tab">HARGA BARANG</a></li>                        
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="item-info">
                            <div class="body">
                                <form>
                                    <label for="email_address">Barcode <span class="col-pink">*</span></label>
                                    <span class="cd-error-message font-bold col-pink" id="err-barcode"></span>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="barcode" data-label="#err-barcode" class="form-control" placeholder="Masukan data barcode" autofocus>
                                        </div>
                                    </div>
                                    <label for="password">Nama Barang <span class="col-pink">*</span></label>
                                    <span class="cd-error-message font-bold col-pink" id="err-item-name"></span>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="item-name" data-label="#err-item-name" class="form-control" placeholder="Masukan nama barang">
                                        </div>
                                    </div>
                                    <label for="item-desc">Deskripsi Barang</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="4" id="item-desc" class="form-control no-resize" placeholder="Please type what you want..."></textarea>
                                        </div>
                                    </div>                                   
                                    <button type="button" class="btn btn-primary m-t-15 waves-effect btn-next-tab" data-next="">LANJUT</button>
                                </form>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="stock-info">
                            <div class="body">
                                <form>
                                    <label for="supplier-name">Pilih Supplier</label>
                                    <span class="cd-error-message font-bold col-pink" id="err-supplier-select"></span>
                                    <div class="form-group row">
                                        <div class="col-sm-6 no-padding">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="supplier-select" data-label="#err-supplier-select" class="form-control" value="Focused" disabled placeholder="Supplier">
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
                                            <input type="text" id="stock" data-label="#err-stock" class="numeric form-control" placeholder="Masukan stock awal">
                                        </div>
                                    </div>
                                    <label for="item-supplier-price">Harga Beli Supplier <span class="col-pink">*</span></label>
                                    <span class="cd-error-message font-bold col-pink" id="err-item-supplier-price"></span>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="item-supplier-price" data-label="#err-item-supplier-price" class="numeric form-control numeric" placeholder="Masukan harga barang">
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-primary m-t-15 waves-effect">LANJUT</button>
                                </form>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="price-list">
                            <div class="body">      
                                <button type="button" id="add-discount-btn" class="btn btn-primary waves-effect">Tambah Discount</button>
                                <br/><br/>
                                <span class="font-bold col-pink" id="err-price-item-list"></span>                      
                                <form class="price-item-container">
                                    <div class="row price-item-list" id="price-item-element">
                                        <div class="col-md-2">
                                            <b>Qty</b>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="text" class="numeric form-control price-item-qty">
                                                </div>                                           
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <b>Harga Jual</b>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="text" class="numeric form-control price-item-price">
                                                </div>                                           
                                            </div>
                                        </div>
                                    </div>                                                                        
                                </form>
                                <button type="button" class="btn btn-primary m-t-15 waves-effect" id="btn-save">SIMPAN</button>
                            </div>
                        </div>                        
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
        if (!$('#item-supplier-price').validateRequired()) {
            err++;
        }
        if (!$('#supplier-select').validateRequired()) {
            err++;
        }
        
        $("#err-price-item-list").text("");
        $(".price-item-list").each(function(){
            var qty = $(this).find(".price-item-qty").val();
            var price = $(this).find(".price-item-price").val();

            if(qty=="" || price==""){
                $("#err-price-item-list").text("Masih terdapat data harga yang kosong..");
                err++;
                return false;
            }
        });

        if (err != 0) {
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
        var priceList=[];
        $(".price-item-list").each(function(){
            var qty = $(this).find(".price-item-qty").val();
            var price = $(this).find(".price-item-price").val();

            priceList.push({
                "qty":qty,
                "price":price
            });
        });

        var formData = new FormData();
        formData.append("barcode", $("#barcode").val());
        formData.append("name", $("#item-name").val());
        formData.append("qty_stock", $("#stock").val());
        formData.append("supplier", $("#supplier-select").val());
        formData.append("price_supplier", $("#item-supplier-price").val());
        formData.append("description", $("#item-desc").val());
        formData.append("item_price_list", JSON.stringify(priceList));
        
        return formData;
        console.log(formData);
    }

    $("#btn-save").click(function(){        
        if(validate()){
           var formData = getAllData();
            $.ajax({
                url: "<?php echo site_url('item/insertItem')?>",
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
                        if (settings.redirect) {
                            window.setTimeout(function() {
                                location.href = settings.locationHref;
                            }, settings.hrefDuration);
                        }
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