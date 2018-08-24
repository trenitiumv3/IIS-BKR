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
                    <?php if($isPeriod !=""){ ?>
                        <a href="<?php echo site_url('report/goToReportRange').'/'.$startDate.'/'.$endDate?>">              
                            <button type="button" class="btn btn-primary waves-effect" id="btn-search">
                                <i class="material-icons">keyboard_backspace</i>
                                <span>KEMBALI KE LIST</span>
                            </button>
                        </a>      
                    <?php }else{ ?>
                        <a href="<?php echo site_url('report');?>">              
                            <button type="button" class="btn btn-primary waves-effect" id="btn-search">
                                <i class="material-icons">keyboard_backspace</i>
                                <span>KEMBALI KE LIST</span>
                            </button>
                        </a>      
                    <?php } ?>
                </div>
                <div class="body">
                    <table class="table tbl-header-trans">
                        <?php 
                            $discount= $data_purchase->extra_discount==""?0:$data_purchase->extra_discount;
                            $finalPrice=($data_purchase->total_price-($data_purchase->total_price*$discount/100));
                            $modalAll=$data_income->total_modal==""?0:$data_income->total_modal;                            
                        ?>
                        <tr>
                            <td><b>ID Transaksi</b></td>
                            <td class="highlight"><?php echo $data_purchase->id;?></td>
                        </tr>
                        <tr>
                            <td><b>Kasir</b></td>
                            <td class="highlight"><?php echo $data_purchase->name;?></td>
                        </tr>
                        <tr>
                            <td><b>Tipe Transaksi</b></td>
                            <td class="highlight"><?php echo strtoupper($data_purchase->type_purchase)." - ".$discount;?>%</td>
                        </tr>                        
                        <tr>
                            <td><b>Total</b></td>
                            <td class="highlight"><?php echo $finalPrice;?></td>
                        </tr> 
                        <tr>
                            <td><b>Keuntungan</b></td>
                            <td class="highlight"><?php echo intval($finalPrice-$modalAll);?></td>
                        </tr>
                                                 
                    </table>
                    <div class="table-responsive">
                        <table id="report-table" class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>             
                                    <th>Barcode</th>                               
                                    <th>Nama Barang</th>
                                    <th>Harga Jual</th>
                                    <th>Harga Supplier</th>
                                    <th>Qty</th>                                                                         
                                </tr>
                            </thead>                                    
                            <tbody>
                                <?php 
                                    $totalPurchase=0;                                    
                                    foreach($data_purchase_detail as $row){                                        
                                        //$discount = $row['extra_discount']? 
                                ?>
                                <tr>
                                    <td><?php echo $row['barcode'];?></td>
                                    <td><?php echo $row['name_item'];?></td>
                                    <td><?php echo $row['price_customer'];?></td>
                                    <td><?php echo $row['price_supplier'];?></td>
                                    <td><?php echo $row['stock'];?></td>                                                                                                            
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
        $('input').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
        var table = $('#report-table').DataTable({
            "lengthChange": true,  
            "paging": false,
            columns: [
                { data: 0,"width": "10%" },
                { data: 1, "width": "10%"},
                { data: 2, "width": "20%"},
                { data: 3, "width": "10%"},
                { data: 4, "width": "20%"}                
            ]
        });

        $("#btn-back").click(function(){
            location.href = "<?php echo site_url('report/goToReportRange')?>/"+startDateVal+"/"+endDateVal;
        });
    });
</script>