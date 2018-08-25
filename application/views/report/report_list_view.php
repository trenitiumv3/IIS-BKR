<style>
    #report-table{
        width:100%!important;
    }
    .highlight{
        font-size:20px;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2>
            Report Penjualan Tanggal <?php echo date("d F Y");?>        
        </h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header"> 
                    <div class="row clearfix">                   
                        <div class="col-sm-1">
                            <div class="form-group">    
                                <a href="<?php echo site_url('report/downloadReportToday');?>">
                                    <button type="button" class="btn btn-primary waves-effect" id="btn-download">
                                        <i class="material-icons">file_download</i>
                                        <span>Download</span>
                                    </button>                                
                                </a>                            
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="body">
                    <!-- <div class="row clearfix">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="table-responsive">
                        <table id="report-table" class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>             
                                    <th>ID Transaksi</th>                               
                                    <th>Jenis Pembayaran</th>
                                    <th>Harga</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                    <th>Kasir</th> 
                                    <th>Action</th>                                            
                                </tr>
                            </thead>                                    
                            <tbody>
                                <?php 
                                    $totalPurchase=0;
                                    $totalCash=0;
                                    $totalDebit=0;
                                    $countDebit=0;
                                    $countCash=0;
                                    $modalAll=0;
                                    if(isset($data_income->total_modal)){
                                        $modalAll=$data_income->total_modal==""?0:$data_income->total_modal;
                                    }                                    

                                    foreach($data_purchase as $row){
                                        $discount= $row['extra_discount']==""?0:$row['extra_discount'];
                                        $finalPrice=($row['total_price']-($row['total_price']*$discount/100));
                                        $totalPurchase += $finalPrice;
                                        if($row['type_purchase']=="cash"){
                                            $totalCash+=$finalPrice;
                                            $countCash++;
                                        }else if($row['type_purchase']=="debit"){
                                            $totalDebit+=$finalPrice;
                                            $countDebit++;
                                        }
                                ?>
                                <tr>
                                    <td><?php echo $row['id'];?></td>
                                    <td><?php echo $row['type_purchase'];?></td>
                                    <td><?php echo $row['total_price'];?></td>
                                    <td><?php echo $discount;?>%</td>
                                    <td><?php echo $finalPrice;?></td>
                                    <td><?php echo $row['name'];?></td>                                    
                                    <td class="dt-center">
                                        <a href="<?php echo site_url().'/report/goToPurchaseDetail/'.$row['id'];?>">
                                            <button type="button" class="btn btn-primary btn-xs">
                                                Detail
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <table class="table">                            
                            <tr>
                                <td><b>Total Transaksi Cash</b></td>
                                <td class="highlight"><?php echo $countCash;?></td>
                            </tr>
                            <tr>
                                <td><b>Total Transaksi Debit</b></td>
                                <td class="highlight"><?php echo $countDebit;?></td>
                            </tr>
                            <tr>
                                <td><b>Total Transaksi</b></td>
                                <td class="highlight"><?php echo count($data_purchase);?></td>
                            </tr>                            
                            <tr>
                                <td><b>Total Penjualan Cash</b></td>
                                <td class="highlight"><?php echo $totalCash;?></td>
                            </tr>
                            <tr>
                                <td><b>Total Penjualan Debit</b></td>
                                <td class="highlight"><?php echo $totalDebit;?></td>
                            </tr>
                            <tr>
                                <td><b>Total Penjualan</b></td>
                                <td class="highlight"><?php echo intval($totalPurchase);?></td>
                            </tr>
                            <tr>
                                <td><b>Total Harga Supplier</b></td>
                                <td class="highlight"><?php echo intval($modalAll);?></td>
                            </tr>
                            <tr>
                                <td><b>Total Keuntungan</b></td>
                                <td class="highlight"><?php echo intval($totalPurchase-$modalAll);?></td>
                            </tr>
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
        //$('input').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
        var table = $('#report-table').DataTable({
            "lengthChange": true,          
            columns: [
                { data: 0,"width": "10%" },
                { data: 1, "width": "10%"},
                { data: 2, "width": "20%"},
                { data: 3, "width": "10%"},
                { data: 4, "width": "20%"},
                { data: 5, "width": "30%"},
                { data: 6, "width": "30%"}
            ]
        });
    });
</script>