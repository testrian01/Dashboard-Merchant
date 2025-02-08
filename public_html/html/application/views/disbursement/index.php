<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->

    <div class="card shadow border-left-info">
        <div class="card-header">
           <h3>Settlement</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo base_url('admin/createCashOutChanel'); ?>">
                
                    <div class="col-md-12">
                        <!-- <div class="form-group">
                            <label for="transaction_method">Metode Transaction</label>
                            <select id="transaction_method" name="transaction_method">
                                <option value="cash">Transfer</option>
                                <option value="credit_card">Credit Card</option>
                                
                            </select>
                        </div> -->
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="reekening">Rekening Tujuan</label>
                            <select id="reekening" class="form-control" name="transaction_method" style="width: 500px;">
                                <option value="" selected disabled>Select Bank</option>
                                <!-- Bank Umum -->
                                <optgroup label="Bank Umum">
                                    <option value="002">Bank BRI (002)</option>
                                    <option value="008">Bank Mandiri (008)</option>
                                    <option value="009">Bank BNI (009)</option>
                                    <option value="014">Bank BCA (014)</option>
                                    <option value="022">Bank CIMB Niaga (022)</option>
                                    <option value="013">Bank Permata (013)</option>
                                    <option value="011">Bank Danamon (011)</option>
                                    <option value="016">Bank Maybank Indonesia (016)</option>
                                    <option value="019">Bank Panin (019)</option>
                                    <option value="426">Bank Mega (426)</option>
                                    <option value="441">Bank Bukopin (441)</option>
                                    <option value="153">Bank Sinarmas (153)</option>
                                    <option value="213">Bank BTPN (213)</option>
                                    <option value="028">Bank OCBC NISP (028)</option>
                                    <option value="037">Bank Artha Graha (037)</option>
                                    <option value="023">Bank UOB Indonesia (023)</option>
                                    <option value="041">Bank HSBC (041)</option>
                                    <option value="061">Bank ANZ Indonesia (061)</option>
                                    <option value="050">Bank Standard Chartered (050)</option>
                                    <option value="046">Bank DBS Indonesia (046)</option>
                                    <option value="054">Bank Capital Indonesia (054)</option>
                                    <option value="485">Bank MNC (485)</option>
                                    <option value="042">Bank of Tokyo Mitsubishi UFJ (042)</option>
                                    <option value="484">Bank KEB Hana (484)</option>
                                    <option value="097">Bank Mayapada (097)</option>
                                    <option value="490">Bank J Trust Indonesia (490)</option>
                                    <option value="213">Bank Tabungan Pensiunan Nasional (BTPN) (213)</option>
                                </optgroup>

                                <!-- Bank Daerah -->
                                <optgroup label="Bank Daerah">
                                    <option value="110">Bank Jabar Banten (BJB) (110)</option>
                                    <option value="111">Bank DKI (111)</option>
                                    <option value="112">Bank BPD DIY (112)</option>
                                    <option value="113">Bank Jateng (113)</option>
                                    <option value="114">Bank Jatim (114)</option>
                                    <option value="115">Bank BPD Jambi (115)</option>
                                    <option value="116">Bank BPD Aceh (116)</option>
                                    <option value="117">Bank Sumut (117)</option>
                                    <option value="118">Bank Nagari (Sumbar) (118)</option>
                                    <option value="119">Bank Riau Kepri (119)</option>
                                    <option value="120">Bank Sumsel Babel (120)</option>
                                    <option value="121">Bank Lampung (121)</option>
                                    <option value="122">Bank Kalsel (122)</option>
                                    <option value="123">Bank Kalbar (123)</option>
                                    <option value="124">Bank Kaltimtara (124)</option>
                                    <option value="125">Bank Kalteng (125)</option>
                                    <option value="126">Bank Sulselbar (126)</option>
                                    <option value="127">Bank SulutGo (127)</option>
                                    <option value="128">Bank NTB Syariah (128)</option>
                                    <option value="129">Bank BPD Bali (129)</option>
                                    <option value="130">Bank NTT (130)</option>
                                    <option value="131">Bank Maluku Malut (131)</option>
                                    <option value="132">Bank Papua (132)</option>
                                    <option value="133">Bank Bengkulu (133)</option>
                                    <option value="134">Bank Sulteng (134)</option>
                                    <option value="135">Bank Sultra (135)</option>
                                </optgroup>

                                <!-- Bank Syariah -->
                                <optgroup label="Bank Syariah">
                                    <option value="451">Bank Syariah Indonesia (BSI) (451)</option>
                                    <option value="147">Bank Muamalat (147)</option>
                                    <option value="153">Bank Sinarmas Syariah (153)</option>
                                    <option value="451">BRI Syariah (451)</option>
                                    <option value="405">Bank Victoria Syariah (405)</option>
                                    <option value="415">Bank Syariah Bukopin (415)</option>
                                    <option value="422">Bank BCA Syariah (422)</option>
                                </optgroup>

                                <!-- Bank Asing -->
                                <optgroup label="Bank Asing">
                                    <option value="031">Citibank (031)</option>
                                    <option value="032">JP Morgan Chase Bank (032)</option>
                                    <option value="033">Bank of America (033)</option>
                                    <option value="067">Deutsche Bank (067)</option>
                                    <option value="069">Bank of China (069)</option>
                                    <option value="076">HSBC (076)</option>
                                    <option value="503">Korea Exchange Bank (503)</option>
                                    <option value="045">BNP Paribas (045)</option>
                                    <option value="070">Mizuho Bank (070)</option>
                                    <option value="542">Bank Jago (542)</option>
                                    <option value="950">Bank Commonwealth (950)</option>
                                </optgroup>

                                <!-- Lainnya -->
                                <optgroup label="Lainnya">
                                    <option value="213">Bank BTPN Wow! (213)</option>
                                    <option value="542">Bank Jago (542)</option>
                                    <option value="501">Bank Amar Indonesia (501)</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="no_rekening">No Rekening</label>
                            <input type="text" class="form-control" id="no_rekening" name="no_rekening" style="width: 500px;">
                        </div>
                        <div class="form-group">
                            <label for="berita">Berita Transfer</label>
                            <input type="text" class="form-control" id="berita" name="berita" style="width: 500px;">
                        </div>
                        <div class="form-group">
                            <label for="nominal">Nominal Transfer</label>
                            <input type="text" class="form-control" required id="nominal" name="nominal" style="width: 500px;">
                        </div>
                    </div>
          
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                </div>

            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->