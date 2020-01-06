<?php
use yii\helpers\Html;

?>
<!DOCTYPE html>
<html>



<h3 style="color:#1a4c8f;font-family:verdana;font-size:150%, 
      7px 7px 0px rgba(0, 0, 0, 0.2);text-align:center";><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Department of Science and Technology</b></h3>
<h3 style="color:#142142;font-family:verdana;font-size:150%;, 
      7px 7px 0px rgba(0, 0, 0, 0.2);text-align:center"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REGIONAL STANDARDS AND TESTING LABORATORY</b></h3>

<h1 style="color:#1a4c8f;font-family:Century Gothic;font-size:250%;, 
      7px 7px 0px rgba(0, 0, 0, 0.2);text-align:center;"><b>&nbsp;&nbsp;&nbsp;&nbsp;Customer Satisfaction Index</b></h1><br>


<div class="row" style="float: right;padding-right: 300px">
      
<?php 
// echo Html::button("<span class='glyphicon glyphicon-print'></span> Customer Satisfaction Measurement Report",['value' => '/lab/csf/printreport','onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Request")]);
// echo "&nbsp;&nbsp;&nbsp;&nbsp;";
// echo Html::button("<span class='glyphicon glyphicon-print'></span> Customer Satisfaction Feedback",['value' => '/lab/csf/printmonthly','onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Request")]);
// echo "<br><br>";
?>

</div>

<!DOCTYPE html>
<html>
  <head>
    <title>Parcel Sandbox</title>
    <meta charset="UTF-8" />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
      crossorigin="anonymous"
    />
    <!-- COPY STYLES FROM HERE -->
    <style>
      .top.table td:nth-child(1) {
        width: 1%;
        white-space: nowrap;
      }

      .score-card-container {
        background-color: #f8f8f8;
        border: 1px solid #eee;
        padding: 8px;
        border-radius: 4px;
      }
      .score-card,
      .score-card > div > div,
      .score-card-label {
        display: flex;
      }
      .score-card h5 {
        text-align: center;
        font-weight: bold;
      }
      .score-card > .detractors {
        flex: 7;
        color: #f85e5e;
      }
      .score-card > .passives {
        flex: 2;
        color: #716f6f;
      }
      .score-card > .promoters {
        flex: 2;
        color: #22bf22;
      }
      .score-card > div > div > div {
        flex: 1;
        border: 1px solid #eee;
        text-align: center;
      }
      .score-card-label > div {
        flex: 1;
        text-align: center;
        margin-top: 9px;
        font-size: 8px;
      }
      .score-card-label > div:first-child {
        text-align: left;
      }
      .score-card-label > div:last-child {
        text-align: right;
      }
    </style>
    <!-- TO HERE -->
  </head>

  <body>
    <!-- COPY CONTENTS FROM HERE -->
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="panel panel-info">
            <div class="panel-heading">
              Technical Services: Regional Standards and Testing Laboratories
            </div>
            <div class="panel-body">
              <table class="table top">
                <tbody>
                  <tr>
                    <td>For the period of</td>
                    <td>: <strong>December</strong></td>
                  </tr>
                  <tr>
                    <td>Total no. of Respondents</td>
                    <td>: <strong id="respondents"><?php  echo $count?></strong></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="panel panel-info">
            <div class="panel-heading">
              Part I: Customer Rating of Service Quality
            </div>
            <div class="panel-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Service Quality Items</th>
                    <th>Very Satisfied</th>
                    <th class="active">5</th>
                    <th>Quite Satisfied</th>
                    <th class="active">4</th>
                    <th>N Sat nor D Sat</th>
                    <th class="active">3</th>
                    <th>Quite Dissatisfied</th>
                    <th class="active">2</th>
                    <th>Very Dissatisfied</th>
                    <th class="active">1</th>
                    <th>Total Score</th>
                    <th>SS</th>
                    <th>GAP</th>
                  </tr>
                </thead>
                <tbody id="part-1"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="panel panel-info">
            <div class="panel-heading">
              Part II: Importance of these attributes to the customers
            </div>
            <div class="panel-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>
                      Importance of Service Quality Attributes to Customers
                    </th>
                    <th>Very Important</th>
                    <th class="active">5</th>
                    <th>Quite Important</th>
                    <th class="active">4</th>
                    <th>Neither Imp nor Unimp</th>
                    <th class="active">3</th>
                    <th>Quite Unimportant</th>
                    <th class="active">2</th>
                    <th>Not important at all</th>
                    <th class="active">1</th>
                    <th>Total Score</th>
                    <th>IS</th>
                    <th>WF</th>
                    <th>SS</th>
                    <th>WS</th>
                  </tr>
                </thead>
                <tbody id="part-2"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-6">
          <div class="panel panel-info">
            <div class="panel-heading">Customer Satisfaction Measurement</div>
            <div class="panel-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Detractors (0-6)</th>
                    <th>Passives (7-8)</th>
                    <th>Promoters (9-10)</th>
                    <th>Net Promoter Score</th>
                  </tr>
                </thead>
                <tbody id="satisfaction-measurement"></tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel panel-info">
            <div class="panel-heading">Legend</div>
            <div class="panel-body">
              <div class="score-card-container">
                <div class="score-card">
                  <div class="detractors">
                    <h5>Detractors</h5>
                    <div>
                      <div>0</div>
                      <div>1</div>
                      <div>2</div>
                      <div>3</div>
                      <div>4</div>
                      <div>5</div>
                      <div>6</div>
                    </div>
                  </div>
                  <div class="passives">
                    <h5>Passives</h5>
                    <div>
                      <div>7</div>
                      <div>8</div>
                    </div>
                  </div>
                  <div class="promoters">
                    <h5>Promoters</h5>
                    <div>
                      <div>9</div>
                      <div>10</div>
                    </div>
                  </div>
                </div>
                <div class="score-card-label text-muted">
                  <div>Not at all likely</div>
                  <div>Neutral</div>
                  <div>Extremely likely</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- TO HERE -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script
      src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
      integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
      crossorigin="anonymous"
    ></script>

    <!-- COPY SCRIPT FROM HERE -->
    <script>
      $(document).ready(function() {
        // ECHO DATA AS respondents VALUE
        let respondents = [];
        let part1 = {};
        let part2 = {};
        let part2_totalIS = 0;
        let part2_totalWF = 0;
        let part2_totalWS = 0;
        let items = {
          deliverytime: "Delivery Time",
          accuracy: "Correctness and Accuracy of Results",
          speed: "Speed of Service",
          cost: "Cost",
          attitude: "Attitude of Staff",
          overall: "Over-all Customer Experience"
        };
        let satMeasurement = {
          detractors: 0,
          passives: 0,
          promoters: 0
        };

        $.get('/lab/csf/csf', function (data) {
          respondents = data[0]

          respondents.forEach(respondent => {
            Object.keys(respondent).forEach(key => {
              if (key.startsWith("d_")) {
                if (!part1[key]) part1[key] = { 5: 0, 4: 0, 3: 0, 2: 0, 1: 0 };
                part1[key][respondent[key]] += 1;
              }

              if (key.startsWith("i_")) {
                if (!part2[key]) part2[key] = { 5: 0, 4: 0, 3: 0, 2: 0, 1: 0 };
                part2[key][respondent[key]] += 1;
              }

              if (key === "recommend") {
                if (respondent[key] <= 6) satMeasurement.detractors += 1;
                if (respondent[key] >= 7 && respondent[key] <= 8)
                  satMeasurement.passives += 1;
                if (respondent[key] >= 9) satMeasurement.promoters += 1;
              }
            });
          });

          Object.keys(items).forEach(key => {
            let part1_totalScore =
              part1["d_" + key][5] * 5 +
              part1["d_" + key][4] * 4 +
              part1["d_" + key][3] * 3 +
              part1["d_" + key][2] * 2 +
              part1["d_" + key][1] * 1;
            let part1_ss = parseFloat(
              Math.round((part1_totalScore / respondents.length) * 100) / 100
            ).toFixed(2);

            let part2_totalScore =
              part2["i_" + key][5] * 5 +
              part2["i_" + key][4] * 4 +
              part2["i_" + key][3] * 3 +
              part2["i_" + key][2] * 2 +
              part2["i_" + key][1] * 1;
            let part2_is = parseFloat(
              Math.round((part2_totalScore / respondents.length) * 100) / 100
            ).toFixed(2);
            let part2_wf = parseFloat(
              Math.round((part2_is / part2_totalScore) * 100 * 100) / 100
            ).toFixed(2);
            let part2_ws = parseFloat(
              Math.round(((part1_ss * part2_wf) / 100) * 100) / 100
            ).toFixed(2);

            let part1_gap = parseFloat(
              Math.round((part2_is - part1_ss) * 100) / 100
            ).toFixed(2);

            $(`
            <tr>
              <td>${items[key]}</td>
              <td>${part1["d_" + key][5]}</td>
              <td class="active">${part1["d_" + key][5] * 5}</td>
              <td>${part1["d_" + key][4]}</td>
              <td class="active">${part1["d_" + key][4] * 4}</td>
              <td>${part1["d_" + key][3]}</td>
              <td class="active">${part1["d_" + key][3] * 3}</td>
              <td>${part1["d_" + key][2]}</td>
              <td class="active">${part1["d_" + key][2] * 2}</td>
              <td>${part1["d_" + key][1]}</td>
              <td class="active">${part1["d_" + key][1] * 1}</td>
              <td>${part1_totalScore}</td>
              <td>${part1_ss}</td>
              <td>${part1_gap}</td>
            </tr>
          `).appendTo("#part-1");

            if (key !== "overall") {
              $(`
                <tr>
                  <td>${items[key]}</td>
                  <td>${part2["i_" + key][5]}</td>
                  <td class="active">${part2["i_" + key][5] * 5}</td>
                  <td>${part2["i_" + key][4]}</td>
                  <td class="active">${part2["i_" + key][4] * 4}</td>
                  <td>${part2["i_" + key][3]}</td>
                  <td class="active">${part2["i_" + key][3] * 3}</td>
                  <td>${part2["i_" + key][2]}</td>
                  <td class="active">${part2["i_" + key][2] * 2}</td>
                  <td>${part2["i_" + key][1]}</td>
                  <td class="active">${part2["i_" + key][1] * 1}</td>
                  <td>${part2_totalScore}</td>
                  <td>${part2_is}</td>
                  <td>${part2_wf}</td>
                  <td>${part1_ss}</td>
                  <td>${part2_ws}</td>
                </tr>
              `).appendTo("#part-2");

              part2_totalIS += Number(part2_is);
              part2_totalWF += Number(part2_wf);
              part2_totalWS += Number(part2_ws);
            }
          });

          $(`
          <tr>
            <td colspan="12"></td>
            <td>${part2_totalIS}</td>
            <td>${parseFloat(
                Math.round(part2_totalWF * 100) / 100
              ).toFixed(2)}</td>
            <td></td>
            <td>${part2_totalWS.toFixed(2)}</td>
          </tr>
          <tr class="bg-warning">
            <td colspan="12" class="text-right">
              <strong>SATISFACTION INDEX:</strong>
            </td>
            <td colspan="3"></td>
            <td>${parseFloat(
                Math.round(((part2_totalWS / 5) * 100) * 100) / 100
              ).toFixed(2)}</td>
          </tr>
        `).appendTo("#part-2");

          $(`
          <tr>
            <td>${satMeasurement.detractors}</td>
            <td>${satMeasurement.passives}</td>
            <td>${satMeasurement.promoters}</td>
            <td>${
              parseFloat(
                Math.round(((satMeasurement.promoters * 100) / respondents.length -
              (satMeasurement.detractors * 100) / respondents.length) * 100) / 100
              ).toFixed(2)}</td>
          </tr>
        `).appendTo("#satisfaction-measurement");
        })
      });
    </script>
    <!-- TO HERE -->
  


</body>
</html>