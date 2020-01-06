<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Customer Satisfaction Feedback - Print Layout</title>

  <style>
    /* CSS Reset */
    html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{border:0;font-size:100%;font:inherit;vertical-align:baseline;margin:0;padding:0}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}body{line-height:1}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:before,blockquote:after,q:before,q:after{content:none}table{border-collapse:collapse;border-spacing:0}

    body {
      width: 8.27in;
      max-height: 11.69in;
      margin: 0.25in auto;
      font-family: Arial, sans-serif;
      font-size: 11pt;
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

    .header {
      text-align: center;
    }

    .font-small { font-size: 10px; }
    .highlight { background-color: #ffde37; }

    .score-card-container {
      border: 1px solid #000;
      padding: 8px;
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
      border: 1px solid rgb(214, 213, 213);
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

    .bg-grey { background-color: #f5f5f5; }
    .color { color: #f85e5e; }
    .color2 { color: blue; }
  </style>
</head>
<body>
  <div class="header">
    <p><strong>Customer Satisfaction Feedback</strong></p>
    <p><span>DOST Regional Office No. IX</span></p>
    <br />
  </div>
  <div class="content">
    <table>
      <thead>
        <tr>
          <th colspan="2">Technical Services: Regional Standards and Testing Laboratories</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width: 25%;">For the period:</td>
          <td>June 2019</td><!-- PERIOD DATE-->
        </tr>
        <tr>
          <td style="width: 25%;">Total no. of Respondents:</td>
          <td>24</td><!-- No. of Respondents -->
        </tr>
      </tbody>
    </table>
    <br />

    <!-- PART I -->
    <table>
      <tbody>
        <tr>
          <th colspan="14">PART I: Customer Rating of Service Quality</th>
        </tr>
        <tr>
          <th>Service Quality Items</th>
          <th class="font-small">Very Satisfied</th>
          <th class="bg-grey">5</th>
          <th class="font-small">Quite Satisfied</th>
          <th class="bg-grey">4</th>
          <th class="font-small">N Sat nor D Sat</th>
          <th class="bg-grey">3</th>
          <th class="font-small">Quite Dissatisfied</th>
          <th class="bg-grey">2</th>
          <th class="font-small">Very Dissatisfied</th>
          <th class="bg-grey">1</th>
          <th class="font-small">Total Score</th>
          <th>SS</th>
          <th>GAP</th>
        </tr>
        <tr>
          <td>Delivery Time</td>
          <td>21</td><!-- Very Satisfied -->
          <td class="bg-grey color">105</td><!-- 5 -->
          <td>3</td><!-- Quite Satisfied -->
          <td class="bg-grey color">12</td><!-- 4 -->
          <td>0</td><!-- N Sat nor D Sat -->
          <td class="bg-grey color">0</td><!-- 3 -->
          <td>0</td><!-- Quite Dissatisfied -->
          <td class="bg-grey color">0</td><!-- 2 -->
          <td>0</td><!-- Very Dissatisfied -->
          <td class="bg-grey color">0</td><!-- 1 -->
          <td class="color">117</td><!-- TOTAL SCORE -->
          <td class="color2">4.87</td><!-- SS -->
          <td>0.04</td><!-- GAP -->
        </tr>
        <tr>
          <td>Correctness and Accuracy of Results</td>
          <td>22</td><!-- Very Satisfied -->
          <td class="bg-grey color">110</td><!-- 5 -->
          <td>2</td><!-- Quite Satisfied -->
          <td class="bg-grey color">8</td><!-- 4 -->
          <td>0</td><!-- N Sat nor D Sat -->
          <td class="bg-grey color">0</td><!-- 3 -->
          <td>0</td><!-- Quite Dissatisfied -->
          <td class="bg-grey color">0</td><!-- 2 -->
          <td>0</td><!-- Very Dissatisfied -->
          <td class="bg-grey color">0</td><!-- 1 -->
          <td class="color">118</td><!-- TOTAL SCORE -->
          <td class="color2">4.92</td><!-- SS -->
          <td>0.00</td><!-- GAP -->
        </tr>
        <tr>
          <td>Speed of Service</td>
          <td>21</td><!-- Very Satisfied -->
          <td class="bg-grey color">105</td><!-- 5 -->
          <td>3</td><!-- Quite Satisfied -->
          <td class="bg-grey color">12</td><!-- 4 -->
          <td>0</td><!-- N Sat nor D Sat -->
          <td class="bg-grey color">0</td><!-- 3 -->
          <td>0</td><!-- Quite Dissatisfied -->
          <td class="bg-grey color">0</td><!-- 2 -->
          <td>0</td><!-- Very Dissatisfied -->
          <td class="bg-grey color">0</td><!-- 1 -->
          <td class="color">117</td><!-- TOTAL SCORE -->
          <td class="color2">4.88</td><!-- SS -->
          <td>0.04</td><!-- GAP -->
        </tr>
        <tr>
          <td>Cost</td>
          <td>21</td><!-- Very Satisfied -->
          <td class="bg-grey color">105</td><!-- 5 -->
          <td>3</td><!-- Quite Satisfied -->
          <td class="bg-grey color">12</td><!-- 4 -->
          <td>0</td><!-- N Sat nor D Sat -->
          <td class="bg-grey color">0</td><!-- 3 -->
          <td>0</td><!-- Quite Dissatisfied -->
          <td class="bg-grey color">0</td><!-- 2 -->
          <td>0</td><!-- Very Dissatisfied -->
          <td class="bg-grey color">0</td><!-- 1 -->
          <td class="color">117</td><!-- TOTAL SCORE -->
          <td class="color2">4.88</td><!-- SS -->
          <td>0.04</td><!-- GAP -->
        </tr>
        <tr>
          <td>Attitude of Staff</td>
          <td>22</td><!-- Very Satisfied -->
          <td class="bg-grey color">110</td><!-- 5 -->
          <td>2</td><!-- Quite Satisfied -->
          <td class="bg-grey color">8</td><!-- 4 -->
          <td>0</td><!-- N Sat nor D Sat -->
          <td class="bg-grey color">0</td><!-- 3 -->
          <td>0</td><!-- Quite Dissatisfied -->
          <td class="bg-grey color">0</td><!-- 2 -->
          <td>0</td><!-- Very Dissatisfied -->
          <td class="bg-grey color">0</td><!-- 1 -->
          <td class="color">118</td><!-- TOTAL SCORE -->
          <td class="color2">4.92</td><!-- SS -->
          <td>0.00</td><!-- GAP -->
        </tr>
        <tr>
          <td>Over-all Customer Experience</td>
          <td>21</td><!-- Very Satisfied -->
          <td class="bg-grey color">87.50</td><!-- 5 -->
          <td>3</td><!-- Quite Satisfied -->
          <td class="bg-grey color">12.50</td><!-- 4 -->
          <td>0</td><!-- N Sat nor D Sat -->
          <td class="bg-grey color">0</td><!-- 3 -->
          <td>0</td><!-- Quite Dissatisfied -->
          <td class="bg-grey color">0</td><!-- 2 -->
          <td>0</td><!-- Very Dissatisfied -->
          <td class="bg-grey color">0</td><!-- 1 -->
          <td class="color">100</td><!-- TOTAL SCORE -->
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
    <br />

    <!-- PART II -->
    <table>
      <thead>
        <tr>
          <th colspan="16">PART II: Importance of these Attributes to the Customers</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>Importance of Service Quality Attributes to Customers</th>
          <th class="font-small">Very Important</th>
          <th class="bg-grey">5</th>
          <th class="font-small">Quite Important</th>
          <th class="bg-grey">4</th>
          <th class="font-small">Neither Imp nor Unimp</th>
          <th class="bg-grey">3</th>
          <th class="font-small">Quite Unimportant</th>
          <th class="bg-grey">2</th>
          <th class="font-small">Not Important at All</th>
          <th class="bg-grey">1</th>
          <th class="font-small">TOTAL SCORE</th>
          <th>IS</th>
          <th>WF</th>
          <th>SS</th>
          <th>WS</th>
        </tr>
        <tr>
          <td>Delivery Time</td>
          <td>22</td><!-- Very Important -->
          <td class="bg-grey color">110</td><!-- 5 -->
          <td>2</td><!-- Quite Important -->
          <td class="bg-grey color">8</td><!-- 4 -->
          <td>0</td><!-- Neither Imp no Unimp -->
          <td class="bg-grey color">0</td><!-- 3 -->
          <td>0</td><!-- Quite Unimportant -->
          <td class="bg-grey color">0</td><!-- 2 -->
          <td>0</td><!-- Not Important at all -->
          <td class="bg-grey color">0</td><!-- 1 -->
          <td class="color">118</td><!-- TOTAL SCORE -->
          <td class="color2">4.92</td><!-- IS -->
          <td>20.00</td><!-- WF -->
          <td>4.88</td><!-- SS -->
          <td>0.98</td><!-- WS -->
        </tr>
        <tr>
          <td>Correctness and Accuracy of Results</td>
          <td>22</td><!-- Very Important -->
          <td class="bg-grey color">110</td><!-- 5 -->
          <td>2</td><!-- Quite Important -->
          <td class="bg-grey color">8</td><!-- 4 -->
          <td>0</td><!-- Neither Imp no Unimp -->
          <td class="bg-grey color">0</td><!-- 3 -->
          <td>0</td><!-- Quite Unimportant -->
          <td class="bg-grey color">0</td><!-- 2 -->
          <td>0</td><!-- Not Important at all -->
          <td class="bg-grey color">0</td><!-- 1 -->
          <td class="color">118</td><!-- TOTAL SCORE -->
          <td class="color2">4.92</td><!-- IS -->
          <td>20.00</td><!-- WF -->
          <td>4.92</td><!-- SS -->
          <td>0.98</td><!-- WS -->
        </tr>
        <tr>
          <td>Speed of Delivery</td>
          <td>22</td><!-- Very Important -->
          <td class="bg-grey color">110</td><!-- 5 -->
          <td>2</td><!-- Quite Important -->
          <td class="bg-grey color">8</td><!-- 4 -->
          <td>0</td><!-- Neither Imp no Unimp -->
          <td class="bg-grey color">0</td><!-- 3 -->
          <td>0</td><!-- Quite Unimportant -->
          <td class="bg-grey color">0</td><!-- 2 -->
          <td>0</td><!-- Not Important at all -->
          <td class="bg-grey color">0</td><!-- 1 -->
          <td class="color">118</td><!-- TOTAL SCORE -->
          <td class="color2">4.92</td><!-- IS -->
          <td>20.00</td><!-- WF -->
          <td>4.88</td><!-- SS -->
          <td>0.98</td><!-- WS -->
        </tr>
        <tr>
          <td>Cost</td>
          <td>22</td><!-- Very Important -->
          <td class="bg-grey color">110</td><!-- 5 -->
          <td>2</td><!-- Quite Important -->
          <td class="bg-grey color">8</td><!-- 4 -->
          <td>0</td><!-- Neither Imp no Unimp -->
          <td class="bg-grey color">0</td><!-- 3 -->
          <td>0</td><!-- Quite Unimportant -->
          <td class="bg-grey color">0</td><!-- 2 -->
          <td>0</td><!-- Not Important at all -->
          <td class="bg-grey color">0</td><!-- 1 -->
          <td class="color">118</td><!-- TOTAL SCORE -->
          <td class="color2">4.92</td><!-- IS -->
          <td>20.00</td><!-- WF -->
          <td>4.88</td><!-- SS -->
          <td>0.98</td><!-- WS -->
        </tr>
        <tr>
          <td>Attitude of Staff</td>
          <td>22</td><!-- Very Important -->
          <td class="bg-grey color">110</td><!-- 5 -->
          <td>2</td><!-- Quite Important -->
          <td class="bg-grey color">8</td><!-- 4 -->
          <td>0</td><!-- Neither Imp no Unimp -->
          <td class="bg-grey color">0</td><!-- 3 -->
          <td>0</td><!-- Quite Unimportant -->
          <td class="bg-grey color">0</td><!-- 2 -->
          <td>0</td><!-- Not Important at all -->
          <td class="bg-grey color">0</td><!-- 1 -->
          <td class="color">118</td><!-- TOTAL SCORE -->
          <td class="color2">4.92</td><!-- IS -->
          <td>20.00</td><!-- WF -->
          <td>4.92</td><!-- SS -->
          <td>0.98</td><!-- WS -->
        </tr>
        <tr>
          <td colspan="12"></td>
          <td>24.58</td><!-- TOTAL IS -->
          <td>80.00</td><!-- TOTAL WF -->
          <td></td>
          <td>4.89</td><!-- TOTAL WS  -->
        </tr>
        <tr class="highlight">
          <td colspan="15" style="text-align: right;">
            <strong>SATISFACTION INDEX:</strong>
          </td>
          <td>97.83</td><!-- SATISFACTION INDEX -->
        </tr>
      </tbody>
    </table>
    <br />

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
    <br />

    <table style="table-layout: fixed;">
      <thead>
        <tr>
          <th>Detractors (0-6)</th>
          <th>Passives (7-8)</th>
          <th>Promoters(9-10)</th>
          <th>Net Promoter Score</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>0</td>
          <td>1</td>
          <td>7</td>
          <td>87.5</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="footer"></div>
</body>
</html>