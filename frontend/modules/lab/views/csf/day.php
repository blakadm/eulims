<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Customer Satisfaction Feedback Survey - Print Layout</title>

  <style>
    /* CSS Reset */
    /* html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{border:0;font-size:100%;font:inherit;vertical-align:baseline;margin:0;padding:0}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}body{line-height:1}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:before,blockquote:after,q:before,q:after{content:none}table{border-collapse:collapse;border-spacing:0} */

    body {
      width: 8.27in;
      max-height: 11.69in;
      margin: 0.25in auto;
      font-family: Arial, sans-serif;
      font-size: 10pt;
      -webkit-print-color-adjust: exact !important;
    }

    p { margin: 5px 0; }
    strong, th { font-weight: bold; }

    table, th, td {
      border: 1px solid #000;
      border-collapse: collapse;
      width: 100%;
      padding: 0.5em;
      text-align: initial;
    }

    h1 {
      font-size: large;
      text-align: center;
    }

    h2 { font-size: larger; }

    table {
      margin: 5px 0;
    }

    .text-center { text-align: center; }
    .header {
      text-align: center;
      margin-bottom: 15px;
    }

    .header > p:nth-child(1), .header > p:nth-child(2) {
      font-weight: bold;
    }

    .checkbox {
      /* display: flex; */
      margin: 5px 0 0 20px;
    }
    .checkbox > div {
      display: inline-block;
      width: 33%;
     }
    .checkbox > div:last-child {
      vertical-align: top;
    }

    .custom-table td, .custom-table th {
      text-align: center;
      font-size: smaller;
    }
    .custom-table td:first-child, .custom-table th:first-child {
      text-align: left;
      font-size: initial;
    }
    .thtd-center th, .thtd-center td {
      text-align: center;
      vertical-align: middle;
    }

    hr {
      margin-top: 25px;
      border: 0;
      height: 1px;
      background-color: #000;
    }
  </style>
</head>
<body>
  <div class="header">
    <p>Department of Science and Technology</p>
    <p>REGIONAL STANDARDS AND TESTING LABORATORIES</p>
    <p>Pettit barracks, Zamboanga City</p>
    <p>TEl. No. (63) (62) 991-1024; Fax No. (63) (62) 992-1114</p>
  </div>
  <div class="content">
    <h1>Customer Satisfaction Feedback Survey</h1>
    <br />

    <h2>I. Information</h2>
    <table>
      <tbody>
        <tr>
          <td>
            Customer Name: <strong>Lorem Ipsum</strong><!-- Customer Name -->
          </td>
        </tr>
        <tr>
          <td>
            Nature of Business:
            <div class="checkbox">
              <div>
                <div><input type="checkbox" name="" id=""> Raw and processed food</div>
                <div><input type="checkbox" name="" id=""> Marine products</div>
                <div><input type="checkbox" name="" id=""> Canned/Bottled Fish</div>
                <div><input type="checkbox" name="" id=""> Fishmeal</div>
                <div><input type="checkbox" name="" id=""> Seaweeds</div>
              </div>
              <div>
                <div><input type="checkbox" name="" id=""> Petroleum Products/Haulers</div>
                <div><input type="checkbox" name="" id=""> Mining</div>
                <div><input type="checkbox" name="" id=""> Hospitals</div>
                <div><input type="checkbox" name="" id=""> Academe/Students</div>
                <div><input type="checkbox" name="" id=""> Beverage and juices</div>
              </div>
              <div>
                <div><input type="checkbox" name="" id=""> Government/LGUs</div>
                <div><input type="checkbox" name="" id=""> Construction</div>
                <div><input type="checkbox" name="" id=""> Water Refilling/Bottled Water</div>
                <div><input type="checkbox" name="" id=""> Others</div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            Type of Market:
            <div class="checkbox">
              <div>
                <div><input type="checkbox" name="" id=""> Local</div>
              </div>
              <div>
                <div><input type="checkbox" name="" id=""> Export</div>
              </div>
              <div>
                <div><input type="checkbox" name="" id=""> Both</div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            What services of the RSTL have you availed?
            <div class="checkbox">
              <div>
                <div><input type="checkbox" name="" id=""> Microbiological Testing</div>
              </div>
              <div>
                <div><input type="checkbox" name="" id=""> Chemical Testing</div>
              </div>
              <div>
                <div><input type="checkbox" name="" id=""> Calibration</div>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <br />

    <h2>II. Delivery of Service</h2>
    <table class="custom-table">
      <thead>
        <tr>
          <th>Service Quality Items</th>
          <th>Very Satisfied (5)</th>
          <th>Quite Satisfied (4)</th>
          <th>Neither satisfied nor Dissatisfied (3)</th>
          <th>Quite Dissatisfied (2)</th>
          <th>Very Dissatisfied (1)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Delivery Time</td>
          <td><input type="checkbox" name="" id=""></td><!-- 5 -->
          <td><input type="checkbox" name="" id=""></td><!-- 4 -->
          <td><input type="checkbox" name="" id=""></td><!-- 3 -->
          <td><input type="checkbox" name="" id=""></td><!-- 2 -->
          <td><input type="checkbox" name="" id=""></td><!-- 1 -->
        </tr>
        <tr>
          <td>Correctness and accuracy of test results</td>
          <td><input type="checkbox" name="" id=""></td><!-- 5 -->
          <td><input type="checkbox" name="" id=""></td><!-- 4 -->
          <td><input type="checkbox" name="" id=""></td><!-- 3 -->
          <td><input type="checkbox" name="" id=""></td><!-- 2 -->
          <td><input type="checkbox" name="" id=""></td><!-- 1 -->
        </tr>
        <tr>
          <td>Speed of service</td>
          <td><input type="checkbox" name="" id=""></td><!-- 5 -->
          <td><input type="checkbox" name="" id=""></td><!-- 4 -->
          <td><input type="checkbox" name="" id=""></td><!-- 3 -->
          <td><input type="checkbox" name="" id=""></td><!-- 2 -->
          <td><input type="checkbox" name="" id=""></td><!-- 1 -->
        </tr>
        <tr>
          <td>Cost</td>
          <td><input type="checkbox" name="" id=""></td><!-- 5 -->
          <td><input type="checkbox" name="" id=""></td><!-- 4 -->
          <td><input type="checkbox" name="" id=""></td><!-- 3 -->
          <td><input type="checkbox" name="" id=""></td><!-- 2 -->
          <td><input type="checkbox" name="" id=""></td><!-- 1 -->
        </tr>
        <tr>
          <td>Attitude of staff</td>
          <td><input type="checkbox" name="" id=""></td><!-- 5 -->
          <td><input type="checkbox" name="" id=""></td><!-- 4 -->
          <td><input type="checkbox" name="" id=""></td><!-- 3 -->
          <td><input type="checkbox" name="" id=""></td><!-- 2 -->
          <td><input type="checkbox" name="" id=""></td><!-- 1 -->
        </tr>
        <tr>
          <td>Over-all customer experience</td>
          <td><input type="checkbox" name="" id=""></td><!-- 5 -->
          <td><input type="checkbox" name="" id=""></td><!-- 4 -->
          <td><input type="checkbox" name="" id=""></td><!-- 3 -->
          <td><input type="checkbox" name="" id=""></td><!-- 2 -->
          <td><input type="checkbox" name="" id=""></td><!-- 1 -->
        </tr>
      </tbody>
    </table>
    <br />

    <h2>III. How <span>important</span> are these items to you?</h2>
    <table class="custom-table">
      <thead>
        <tr>
          <th>Service Quality Items</th>
          <th>Very important (5)</th>
          <th>Quite important (4)</th>
          <th>Neither important nor unimportant (3)</th>
          <th>Quite unimportant (2)</th>
          <th>Not at all important (1)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Delivery Time</td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
          <td>Correctness and accuracy of test results</td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
          <td>Speed of service</td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
          <td>Cost</td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
          <td>Attitude of staff</td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
        </tr>
        <tr>
          <td>Over-all customer experience</td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
        </tr>
      </tbody>
    </table>
    <br />

    <h2>IV. How likely is it that you would <span>recommend</span> our service to others?</h2>
    <table style="table-layout: fixed;" class="thtd-center">
      <thead>
        <tr>
          <th>0<br /><span style="font-size: smaller;">Not at all likely</span></th>
          <th>1</th>
          <th>2</th>
          <th>3</th>
          <th>4</th>
          <th>5</th>
          <th>6</th>
          <th>7</th>
          <th>8</th>
          <th>9</th>
          <th>10<br /><span style="font-size: smaller;">Extremely likely</span></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
          <td><input type="checkbox" name="" id=""></td>
        </tr>
      </tbody>
    </table>
    <br />

    <h2>V. Please give us your comments/suggestions to improve our services. Also, let us know other tests you require that we are not able to provide yet.</h2>
    <hr>
    <hr>
    <hr>
  </div>
  <div class="footer">
    <div class="checkbox" style="margin: 30px 0 !important;">
      <div style="flex: 1; display: flex;">
        Feedback given by:
        <div style="
          flex: 1;
          border-bottom: 1px solid #000;
          margin: 0 10px 0 5px;
          padding: 0 2px;
        ">
          Lorem ipsum
        </div>
      </div>
      <div style="flex: 1; display: flex;">
        Date:
        <div style="
          flex: 1;
          border-bottom: 1px solid #000;
          margin-left: 5px;
          padding: 0 2px;
        ">
          6-29-19
        </div>
      </div>
    </div>
  </div>
</body>
</html>