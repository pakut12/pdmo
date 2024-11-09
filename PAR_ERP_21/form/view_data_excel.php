<!doctype html>
<html lang="en">

<head>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
  <!-- Bootstrap CSS v5.2.1 -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

  <style>

  </style>
</head>

<body>
  <header>

  </header>
  <main>
    <div class="form-group row">
      <?php
      $W = $_GET['W'];
      $WFR_ID = $_GET['WFR'];

      /********** ดึงข้อมูลในตาราง  FRM_SLIP_EMPLOYEE************/
      $query = db::query("select * from FRM_SLIP_EMPLOYEE WHERE WF_MAIN_ID = '" . $W . "' AND WFR_ID = '" . $WFR_ID . "'");
      $row = db::num_rows($query);

      $WF = [];
      $n = 0;
      while ($n < $row) {
        array_push($WF, db::fetch_array($query));
        $n++;
      }
      /********** จบการดึงข้อมูลในตาราง  FRM_SLIP_EMPLOYEE************/

      /********** ทำการจัดกลุ่ม สังกัด เพื่อหาผลรวมเเต่ล่ะสังกัด ************/
      $deparr = []; // สังกัด
      foreach ($WF as $k => $v) {
        if (!in_array($v['F_AFFILIATION'], $deparr)) {
          array_push($deparr, $v['F_AFFILIATION']);
        }
      }
      /********** จบทำการจัดกลุ่ม สังกัด เพื่อหาผลรวมเเต่ล่ะสังกัด ************/
      ?>
    </div>

    <!-- ส่วนหัวเรื่อง  เดือน ปีงบประมาณ สำนัก เงินฝาก-->
    <div class="row h5 ">
      <div class="col-md-6 text-start ">
        <?= $WF[0]["MONTH_ID"] ?><br>
        <div class="mt-1"> <?= $WF[0]["DEP_ID"] ?></div>
      </div>
      <div class="col-md-6 text-end ">
        <?= $WF[0]["F_YEAR"] ?><br>
        <div class="mt-1"><?= $WF[0]["F_TYPE"] ?></div>
      </div>
      <input type="hidden" name="MONTH_ID[]" id="" value=" <?= $WF[0]["MONTH_ID"] ?>">
      <input type="hidden" name="DEP_ID[]" id="" value="<?= $WF[0]["DEP_ID"] ?>">
      <input type="hidden" name="F_YEAR[]" id="" value="<?= $v['F_YEAR']; ?>">
      <input type="hidden" name="F_TYPE[]" id="" value="<?= $v['F_TYPE']; ?>">
    </div>
    <!-- จบส่วนหัวเรื่อง  เดือน ปีงบประมาณ สำนัก เงินฝาก-->
    <br>

    <div class="table-responsive ">
      <table class="table  table-sm  text-nowrap " id="table_view">
        <thead>
          <tr class="bg-dark-subtle text-center">
            <th>ลำดับที่</th>
            <th>เลขบัตรประชาชน</th>
            <th>เลขที่บัญชีธนาคาร</th>
            <th width='200px'>รายชื่อผู้รับเงิน</th>
            <th>ตำแหน่ง</th>
            <th>สังกัด</th>
            <th>อัตราค่าจ้าง <br>เดือนละ </th>
            <th>รวมยอดจ่ายจริง</th>
            <th>ประกันสังคม</th>
            <th>สหกรณ์</th>
            <th>หนี้ธนาคาร<br>กรุงไทย-ออมสิน</th>
            <th>กยศ.</th>
            <th>หนี้ สบน.</th>
            <th>รับจริง</th>
            <th>หมายเหตุ</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sum_FULL_F_WAGE = 0; //ผลรวมทั้งหมดของ อัตราค่าจ้างเดือนละ
          $sum_FULL_F_SUM = 0; //ผลรวมทั้งหมดของ รวมยอดจ่ายจริง
          $sum_FULL_F_SSO = 0; //ผลรวมทั้งหมดของ ประกันสังคม
          $sum_FULL_F_COOPERATIVE = 0; //ผลรวมทั้งหมดของ สหกรณ์
          $sum_FULL_F_BANK_DEBT = 0; //ผลรวมทั้งหมดของ หนี้ธนาคาร กรุงไทย-ออมสิน
          $sum_FULL_F_SLF = 0; //ผลรวมทั้งหมดของ กยศ.
          $sum_FULL_F_PDMO = 0; //ผลรวมทั้งหมดของ หนี้ สบน.
          $sum_FULL_F_ACCEPT = 0; //ผลรวมทั้งหมดของ รับจริง
          $n = 1;
          foreach ($deparr as $key => $de) {
            $sumF_WAGE = 0; //ผลรวมของ อัตราค่าจ้างเดือนละ
            $sumF_SUM = 0; //ผลรวมของ รวมยอดจ่ายจริง
            $sumF_SSO = 0; //ผลรวมของ ประกันสังคม
            $sumF_COOPERATIVE = 0; //ผลรวมของ สหกรณ์
            $sumF_BANK_DEBT = 0; //ผลรวมของ หนี้ธนาคาร กรุงไทย-ออมสิน
            $sumF_SLF = 0; //ผลรวมของ กยศ.
            $sumF_PDMO = 0; //ผลรวมของ หนี้ สบน.
            $sumF_ACCEPT = 0; //ผลรวมของ รับจริง


            foreach ($WF as $k => $v) {
              if ($de == $v['F_AFFILIATION']) {
                /*************** เพิ่มจำนวนผลรวมเเต่ล่ะช่อง ***************/
                $sumF_WAGE += $v['F_WAGE'];
                $sumF_SUM += $v['F_SUM'];
                $sumF_SSO += $v['F_SSO'];
                $sumF_COOPERATIVE += $v['F_COOPERATIVE'];
                $sumF_BANK_DEBT += $v['F_BANK_DEBT'];
                $sumF_SLF += $v['F_SLF'];
                $sumF_PDMO += $v['F_PDMO'];
                $sumF_ACCEPT += $v['F_ACCEPT'];
                /*************** จบเพิ่มจำนวนผลรวมเเต่ล่ะช่อง ***************/

                /*************** เพิ่มจำนวนผลรวมทั้งหมดเเต่ล่ะช่อง ***************/
                $sum_FULL_F_WAGE += $v['F_WAGE'];
                $sum_FULL_F_SUM += $v['F_SUM'];
                $sum_FULL_F_SSO += $v['F_SSO'];
                $sum_FULL_F_COOPERATIVE += $v['F_COOPERATIVE'];
                $sum_FULL_F_BANK_DEBT += $v['F_BANK_DEBT'];
                $sum_FULL_F_SLF += $v['F_SLF'];
                $sum_FULL_F_PDMO += $v['F_PDMO'];
                $sum_FULL_F_ACCEPT += $v['F_ACCEPT'];
                /*************** จบเพิ่มจำนวนผลรวมทั้งหมดเเต่ล่ะช่อง ***************/
          ?>
                <tr>

                  <input type="hidden" name="F_ID[]" id="" value="<?= $v['F_ID']; ?>">
                  <input type="hidden" name="F_CARD[]" id="" value="<?= $v['F_CARD']; ?>">
                  <input type="hidden" name="F_BANK[]" id="" value="<?= ($v['F_BANK']); ?>">
                  <input type="hidden" name="F_USER[]" id="" value="<?= ($v['F_USER']); ?>">
                  <input type="hidden" name="F_POSITION[]" id="" value="<?= ($v['F_POSITION']); ?>">
                  <input type="hidden" name="F_AFFILIATION[]" id="" value="<?= ($v['F_AFFILIATION']); ?>">
                  <input type="hidden" name="F_WAGE[]" id="" value="<?= number_format($v['F_WAGE'], 2); ?>">
                  <input type="hidden" name="F_SUM[]" id="" value="<?= number_format($v['F_SUM'], 2); ?>">
                  <input type="hidden" name="F_SSO[]" id="" value="<?= number_format($v['F_SSO'], 2); ?>">
                  <input type="hidden" name="F__NOTE[]" id="" value="<?= ($v['F__NOTE']); ?>">


                  <td><?= $v['F_ID']; ?></td>
                  <td><?= $v['F_CARD']; ?></td>
                  <td><?= $v['F_BANK']; ?></td>
                  <td><?= $v['F_USER']; ?></td>
                  <td><?= $v['F_POSITION']; ?></td>
                  <td><?= $v['F_AFFILIATION']; ?></td>
                  <td id="T_F_WAGE#<?= $v['F_ID']; ?>"><?= number_format($v['F_WAGE'], 2); ?></td>
                  <td id="T_F_SUM#<?= $v['F_ID']; ?>"><?= number_format($v['F_SUM'], 2); ?></td>
                  <td id="T_F_SSO#<?= $v['F_ID']; ?>"><?= number_format($v['F_SSO'], 2); ?></td>
                  <td><input type="text" name="F_COOPERATIVE[]" id="F_COOPERATIVE#<?= $v['F_ID']; ?>" class="form-control form-control-sm text-center" value="<?php echo isset($v['F_COOPERATIVE']) ? number_format($v['F_COOPERATIVE'], 2) : "0.00" ?>"></td>
                  <td><input type="text" name="F_BANK_DEBT[]" id="F_BANK_DEBT#<?= $v['F_ID']; ?>" class="form-control form-control-sm text-center" value="<?php echo isset($v['F_BANK_DEBT']) ? number_format($v['F_BANK_DEBT'], 2) :  "0.00"  ?>"></td>
                  <td><input type="text" name="F_SLF[]" id="F_SLF#<?= $v['F_ID']; ?>" class="form-control form-control-sm text-center" value="<?php echo isset($v['F_SLF']) ? number_format($v['F_SLF'], 2) :  "0.00"  ?>"></td>
                  <td><input type="text" name="F_PDMO[]" id="F_PDMO#<?= $v['F_ID']; ?>" class="form-control form-control-sm text-center" value="<?php echo isset($v['F_PDMO']) ? number_format($v['F_PDMO'], 2) :  "0.00"  ?>"></td>
                  <td><input type="text" name="F_ACCEPT[]" id="F_ACCEPT#<?= $v['F_ID']; ?>" class="form-control form-control-sm text-center" value="<?= number_format($v['F_ACCEPT'], 2) ?>" readonly></td>
                  <td><?= ($v['F__NOTE']); ?></td>
                </tr>
            <?php
              }
            }
            ?>
            <tr class="bg-dark-subtle">
              <td colspan="6" class="fw-bold text-end "> รวม </td>
              <td id="S_F_WAGE#<?= $key; ?>"><?= number_format($sumF_WAGE, 2) ?></td>
              <td id="S_F_SUM#<?= $key; ?>"> <?= number_format($sumF_SUM, 2) ?></td>
              <td id="S_F_SSO#<?= $key; ?>"> <?= number_format($sumF_SSO, 2) ?></td>
              <td id="S_F_COOPERATIVE#<?= $key; ?>"> <?= number_format($sumF_COOPERATIVE, 2) ?></td>
              <td id="S_F_BANK_DEBT#<?= $key; ?>"> <?= number_format($sumF_BANK_DEBT, 2) ?></td>
              <td id="S_F_SLF#<?= $key; ?>"> <?= number_format($sumF_SLF, 2) ?></td>
              <td id="S_F_PDMO#<?= $key; ?>"><?= number_format($sumF_PDMO, 2) ?> </td>
              <td id="S_F_ACCEP#<?= $key; ?>"> <?= number_format($sumF_ACCEPT, 2) ?></td>
              <td> </td>
            </tr>
          <?php
          }
          ?>

        </tbody>
        <tr class="bg-dark-subtle">
          <td colspan="6" class="fw-bold text-end"> รวมทั้งสิ้น </td>
          <td id="ALL_S_WAGE"><?= number_format($sum_FULL_F_WAGE, 2) ?></td>
          <td id="ALL_S_SUM"> <?= number_format($sum_FULL_F_SUM, 2) ?></td>
          <td id="ALL_S_SSO"> <?= number_format($sum_FULL_F_SSO, 2) ?></td>
          <td id="ALL_S_COOPERATIVE"> <?= number_format($sum_FULL_F_COOPERATIVE, 2) ?></td>
          <td id="ALL_S_BANK_DEBT"> <?= number_format($sum_FULL_F_BANK_DEBT, 2) ?></td>
          <td id="ALL_S_SLF"> <?= number_format($sum_FULL_F_SLF, 2) ?></td>
          <td id="ALL_S_PDMO"><?= number_format($sum_FULL_F_PDMO, 2) ?> </td>
          <td id="ALL_S_ACCEP"> <?= number_format($sum_FULL_F_ACCEPT, 2) ?></td>
          <td id=""> </td>
        </tr>
        <tfoot>

        </tfoot>
      </table>
    </div>


  </main>
  <footer>
    <!-- place footer here -->
  </footer>

</body>

<script>
  /*********** เเยกสังกัด ***************/
  function get_dep(rows) {
    var arr = [];
    $(rows).each(function(k, v) {
      if (v[5] !== undefined && !arr.includes(v[5]) && v[0] !== undefined && !v[0].includes("รวม")) {
        arr.push(v[5]);
      }
    });
    return arr;

  }
  /*********** จบเเยกสังกัด ***************/

  /*********** เเยกผลรวมตามสังกัด ***************/
  function get_all_sum_by_dep(dep, data) {
    let all_data = [];

    $(data).each(function(k, v) {
      if (v[0] !== undefined && !v[0].includes("รวม")) {

        all_data.push(v);
      }
    })

    let sum_dep = [];

    let sum_FULL_F_WAGE = 0; //ผลรวมทั้งหมดของ อัตราค่าจ้างเดือนละ
    let sum_FULL_F_SUM = 0; //ผลรวมทั้งหมดของ รวมยอดจ่ายจริง
    let sum_FULL_F_SSO = 0; //ผลรวมทั้งหมดของ ประกันสังคม
    let sum_FULL_F_COOPERATIVE = 0; //ผลรวมทั้งหมดของ สหกรณ์
    let sum_FULL_F_BANK_DEBT = 0; //ผลรวมทั้งหมดของ หนี้ธนาคาร กรุงไทย-ออมสิน
    let sum_FULL_F_SLF = 0; //ผลรวมทั้งหมดของ กยศ.
    let sum_FULL_F_PDMO = 0; //ผลรวมทั้งหมดของ หนี้ สบน.
    let sum_FULL_F_ACCEPT = 0; //ผลรวมทั้งหมดของ รับจริง

    $(dep).each(function(k, d) {

      let sumF_WAGE = 0; //ผลรวมของ อัตราค่าจ้างเดือนละ
      let sumF_SUM = 0; //ผลรวมของ รวมยอดจ่ายจริง
      let sumF_SSO = 0; //ผลรวมของ ประกันสังคม
      let sumF_COOPERATIVE = 0; //ผลรวมของ สหกรณ์
      let sumF_BANK_DEBT = 0; //ผลรวมของ หนี้ธนาคาร กรุงไทย-ออมสิน
      let sumF_SLF = 0; //ผลรวมของ กยศ.
      let sumF_PDMO = 0; //ผลรวมของ หนี้ สบน.
      let sumF_ACCEPT = 0; //ผลรวมของ รับจริง

      $(all_data).each(function(k, v) {
        if (d === v[5]) {
          sumF_WAGE += parseFloat(v[6].replace(/,/g, ''));
          sumF_SUM += parseFloat(v[7].replace(/,/g, ''));
          sumF_SSO += parseFloat(v[8].replace(/,/g, ''));
          sumF_COOPERATIVE += parseFloat(v[9].replace(/,/g, ''));
          sumF_BANK_DEBT += parseFloat(v[10].replace(/,/g, ''));
          sumF_SLF += parseFloat(v[11].replace(/,/g, ''));
          sumF_PDMO += parseFloat(v[12].replace(/,/g, ''));
          sumF_ACCEPT += parseFloat(v[13].replace(/,/g, ''));

        }
      });

      sum_dep.push(
        [sumF_WAGE.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }),
          sumF_SUM.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }), sumF_SSO.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }),
          sumF_COOPERATIVE.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }),
          sumF_BANK_DEBT.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }),
          sumF_SLF.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }),
          sumF_PDMO.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }),
          sumF_ACCEPT.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          })
        ]
      );


    })
    return sum_dep;
  }
  /*********** จบเเยกผลรวมตามสังกัด ***************/




  /*********** ดึงข้อมูลจากตารางทั้งหมด ***************/
  function get_all_data() {
    let rows = [];
    $('table tr').each(function() {
      let cols = [];
      $(this).find('td').each(function(k, v) {
        if (k >= 9 && k <= 13) {
          cols.push($(v).find('input').val());
        } else {
          cols.push($(v).text());
        }
      });
      rows.push(cols);
    });
    return rows;
  }
  /*********** จบดึงข้อมูลจากตารางทั้งหมด ***************/



  $(document).ready(function() {

    $('input').click(function(e) {
      $(this).select();

    });

    $('input').change(function() {

      var id = $(this).attr('id');
      var w_id = id.split("#");

      $(this).val().replace(/[^0-9]/g, '');

      /*********** เเปลงค่าให้เป็นทศนิยม *********
       * เเปลงทศนิยมให้เป็น 2 ตำเเหน่ง ช่อง input
       * สหกรณ์
       * หนี้ธนาคาร กรุงไทย-ออมสิน
       * กยศ.
       * หนี้ สบน.
       ***************************************/

      var F_COOPERATIVE = $('#F_COOPERATIVE\\#' + w_id[1]).val().replace(/,/g, '');
      F_COOPERATIVE = isNaN(parseFloat(F_COOPERATIVE)) ? "0.00" : parseFloat(F_COOPERATIVE).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });

      var F_BANK_DEBT = $('#F_BANK_DEBT\\#' + w_id[1]).val().replace(/,/g, '');
      F_BANK_DEBT = isNaN(parseFloat(F_BANK_DEBT)) ? "0.00" : parseFloat(F_BANK_DEBT).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });

      var F_SLF = $('#F_SLF\\#' + w_id[1]).val().replace(/,/g, '');
      F_SLF = isNaN(parseFloat(F_SLF)) ? "0.00" : parseFloat(F_SLF).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });

      var F_PDMO = $('#F_PDMO\\#' + w_id[1]).val().replace(/,/g, '');
      F_PDMO = isNaN(parseFloat(F_PDMO)) ? "0.00" : parseFloat(F_PDMO).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });

      $('#F_COOPERATIVE\\#' + w_id[1]).val(F_COOPERATIVE)
      $('#F_BANK_DEBT\\#' + w_id[1]).val(F_BANK_DEBT)
      $('#F_SLF\\#' + w_id[1]).val(F_SLF)
      $('#F_PDMO\\#' + w_id[1]).val(F_PDMO)

      /*********** จบเเปลงค่าให้เป็นทศนิยม *********/


      /*********** ผลรวมของรับจริง ***************/
      var sum =
        parseFloat($('#T_F_SUM\\#' + w_id[1]).text().replace(/,/g, '')) -
        parseFloat($('#T_F_SSO\\#' + w_id[1]).text().replace(/,/g, '')) -
        parseFloat($('#F_COOPERATIVE\\#' + w_id[1]).val().replace(/,/g, '')) -
        parseFloat($('#F_BANK_DEBT\\#' + w_id[1]).val().replace(/,/g, '')) -
        parseFloat($('#F_SLF\\#' + w_id[1]).val().replace(/,/g, '')) -
        parseFloat($('#F_PDMO\\#' + w_id[1]).val().replace(/,/g, ''));

      sum = isNaN(parseFloat(sum)) ? 0 : parseFloat(sum).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
      $('#F_ACCEPT\\#' + w_id[1]).val(sum);
      /*********** จบผลรวมของรับจริง ***************/

      /*********** จัดการผลรวมทั้งหมด ***************/
      let all_data = get_all_data();
      let all_dep = get_dep(all_data);
      let all_sum_by_dep = get_all_sum_by_dep(all_dep, all_data);

      let ALL_S_WAGE = 0;
      let ALL_S_SUM = 0;
      let ALL_S_SSO = 0;
      let ALL_S_COOPERATIVE = 0;
      let ALL_S_BANK_DEBT = 0;
      let ALL_S_SLF = 0;
      let ALL_S_PDMO = 0;
      let ALL_S_ACCEP = 0;

      $(all_sum_by_dep).each(function(k, v) {
        ALL_S_WAGE += parseFloat(v[0].replace(/,/g, ''));
        ALL_S_SUM += parseFloat(v[1].replace(/,/g, ''));
        ALL_S_SSO += parseFloat(v[2].replace(/,/g, ''));
        ALL_S_COOPERATIVE += parseFloat(v[3].replace(/,/g, ''));
        ALL_S_BANK_DEBT += parseFloat(v[4].replace(/,/g, ''));
        ALL_S_SLF += parseFloat(v[5].replace(/,/g, ''));
        ALL_S_PDMO += parseFloat(v[6].replace(/,/g, ''));
        ALL_S_ACCEP += parseFloat(v[7].replace(/,/g, ''));

        $("#S_F_WAGE\\#" + k).text(v[0]);
        $("#S_F_SUM\\#" + k).text(v[1]);
        $("#S_F_SSO\\#" + k).text(v[2]);
        $("#S_F_COOPERATIVE\\#" + k).text(v[3]);
        $("#S_F_BANK_DEBT\\#" + k).text(v[4]);
        $("#S_F_SLF\\#" + k).text(v[5]);
        $("#S_F_PDMO\\#" + k).text(v[6]);
        $("#S_F_ACCEP\\#" + k).text(v[7]);
      });


      $("#ALL_S_WAGE").text(ALL_S_WAGE.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }));
      $("#ALL_S_SUM").text(ALL_S_SUM.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }));
      $("#ALL_S_SSO").text(ALL_S_SSO).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
      $("#ALL_S_COOPERATIVE").text(ALL_S_COOPERATIVE.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }));
      $("#ALL_S_BANK_DEBT").text(ALL_S_BANK_DEBT.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }));
      $("#ALL_S_SLF").text(ALL_S_SLF.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }));
      $("#ALL_S_PDMO").text(ALL_S_PDMO.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }));
      $("#ALL_S_ACCEP").text(ALL_S_ACCEP.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }));
    });
    /*********** จบจัดการผลรวมทั้งหมด ***************/


  });
</script>

</html>