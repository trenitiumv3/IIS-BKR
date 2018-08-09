<style>
    #report-table{
        width:100%!important;
    }
    .tbl-header-trans td{
        font-size:14px;
        padding:0px;
    }    
    .tbl-header-trans td:nth-child(1){
        width:30%;
    }
    .tbl-header-trans td:nth-child(2){
        width:70%;
    }
    .btn-find{
        width:200px;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2>
            DETAIL PENJUALAN        
        </h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">                    
                  
                </div>
                <div class="body">
                    <label for="email_address">Barcode <span class="col-pink">*</span></label>
                    <span class="cd-error-message font-bold col-pink" id="err-barcode"></span>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="barcode" data-label="#err-barcode" class="form-control" placeholder="Masukan data barcode" value="<?php echo $barcode;?>" autofocus>
                        </div>
                    </div>
                    <label for="password">Nama Barang <span class="col-pink"></span></label>
                    <span class="cd-error-message font-bold col-pink" id="err-item-name"></span>
                    <div class="form-group">
                        <div class="form-line">
                            <input disabled type="text" id="item-name" data-label="#err-item-name" class="form-control" placeholder="Masukan nama barang" value="<?php echo $itemName;?>">
                        </div>
                    </div>

                    <a id="btn-find" href="<?php echo site_url("report");?>">
                        <button type="button" class="btn btn-find btn-primary m-t-15 waves-effect">Cari</button>
                    </a>
                    <br/><br/>
                    <div class="table-responsive">
                        <table id="report-table" class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>               
                                    <th>Supplier</th>                               
                                    <th>Harga Supplier</th>                                    
                                    <th>Penambahan Stok</th>
                                    <th>Stok Aawal</th>                       
                                </tr>
                            </thead>                                    
                            <tbody>
                                <?php 
                                    $totalPurchase=0;                                    
                                    foreach($data_purchase_detail as $row){                                        
                                        //$discount = $row['extra_discount']? 
                                ?>
                                <tr>
                                    <td><?php echo $row['date_created'];?></td>
                                    <td><?php echo $row['supp_name'];?></td>
                                    <td><?php echo $row['price_total_supplier']/$row['qty_trans'];?></td>
                                    <td><?php echo $row['qty_trans'];?></td>
                                    <td><?php echo $row['last_qty_stock'];?></td>                                                                                                                                        
                                </tr>
                                <?php } ?>
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
        var table = $('#report-table').DataTable({
            "lengthChange": true,            
            columns: [
                { data: 0,"width": "10%" },
                { data: 1, "width": "10%"},
                { data: 2, "width": "20%"},
                { data: 3, "width": "10%"},  
                { data: 4, "width": "10%"}                             
            ]
        });

        $('#barcode').blur(function(){
            var data = $(this).val();
            var href = "<?php echo site_url("report/goToHistoryStock")?>/"+data;
            $("#btn-find").attr("href",href);
        });

        $('#btn-find').click(function(){
            var data = $("#barcode").val();
            var href = "<?php echo site_url("report/goToHistoryStock")?>/"+data;
            window.setTimeout(function() {
                location.href = href;
            }, 500);
        });
    });
</script>