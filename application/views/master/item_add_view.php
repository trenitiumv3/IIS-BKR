<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Tambah Barang Baru
                        <small>Add quick, dynamic tab functionality to transition through panes of local content</small>
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
                                    <label for="email_address">Barcode</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="email_address" class="form-control" placeholder="Masukan data barcode">
                                        </div>
                                    </div>
                                    <label for="password">Nama Barang</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="password" class="form-control" placeholder="Masukan nama barang">
                                        </div>
                                    </div>
                                    <label for="item-desc">Deskripsi Barang</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="4" id="item-desc" class="form-control no-resize" placeholder="Please type what you want..."></textarea>
                                        </div>
                                    </div>                                   
                                    <button type="button" class="btn btn-primary m-t-15 waves-effect">LOGIN</button>
                                </form>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="stock-info">
                            <div class="body">
                                <form>
                                    <label for="supplier-name">Nama Supplier</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="supplier-name" class="form-control" placeholder="Masukan data supplier">
                                        </div>
                                    </div>
                                    <label for="supplier-desc">Deskripsi Supplier</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="4" id="supplier-desc" class="form-control no-resize" placeholder="Tentang Supplier"></textarea>
                                        </div>
                                    </div>
                                    <label for="stock">Stok</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="stock" class="form-control" placeholder="Masukan stock awal">
                                        </div>
                                    </div>
                                    <label for="item-supplier-price">Harga Beli Supplier</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="item-supplier-price" class="form-control" placeholder="Masukan harga barang">
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-primary m-t-15 waves-effect">LOGIN</button>
                                </form>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="price-list">
                            <div class="body">      
                                <button type="button" class="btn btn-primary m-t-15 waves-effect">Tambah Discount</button>                      
                                <form>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <b>Qty</b>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control">
                                                </div>                                           
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <b>Harga Jual</b>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control">
                                                </div>                                           
                                            </div>
                                        </div>
                                    </div>                                    
                                    <button type="button" class="btn btn-primary m-t-15 waves-effect">LOGIN</button>
                                </form>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>