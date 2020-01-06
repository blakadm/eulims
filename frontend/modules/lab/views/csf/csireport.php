

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Document</title>

    <style>
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

      .header {
        text-align: center;
      }

      table, th, td {
        border: 1px solid #000;
        border-collapse: collapse;
        width: 100%;
        padding: 0.5em;
      }

      td:nth-child(1) {
        width: 25%;
        white-space: nowrap;
      }

      ul > li { margin-bottom: 4px; }
      ol {
        list-style: decimal;
        padding-inline-start: 1em;
      }

      .delivery-of-service > tr > td:nth-child(1) { color: #fb0b0b; }
      .delivery-of-service > tr > td:nth-child(2) { color: blue; }

      td[colspan="5"] {
        text-align: center;
        background-color: #ebfdf3;
        color: #fb0b0b;
      }
      td[colspan="4"] { text-align: right; }

      .highlight { background-color: #ffde37; }

      .footer {
        display: flex;
        margin-top: 25px;
      }
      .footer > div { flex: 1; }
      .footer > div > div { margin: 30px 0 0 0; }
    </style>
  </head>
  <body>
    <div class="header">
      <p><strong>Customer Satisfaction Measurement Report</strong></p>
      <p><span>For the month of June 2019</span></p>
    </div>
    <div class="content">
      <table>
        <tbody>
          <tr>
            <td colspan="2">
              <strong>I. Information</strong>
            </td>
          </tr>
          <tr>
            <td>No. of Customers</td>
            <td>
              <!-- NUMBER OF CUSTOMERS -->
              <strong>24</strong>
            </td>
          </tr>
          <tr>
            <td>Type of Industry</td>
            <td>
              <ul>
                <!-- LOOP THROUGH EACH TYPE OF INDUSTRIES -->
                <li>3 Academe</li>
                <li>7 Canned/Bottled Fish</li>
                <li>1 Seaweeds</li>
                <li>5 Petroleum products/haulers</li>
                <li>3 Marine Products</li>
                <li>3 Fishmeal</li>
                <li>1 Rubber</li>
                <li>2 Hospital</li>
                <li>6 Others</li>
              </ul>
            </td>
          </tr>
          <tr>
            <td>Type of market</td>
            <td>
              <ul>
                <!-- LOOP THROUGH EACH TYPE OF MARKET -->
                <li>Local - 19</li>
                <li>Export - 3</li>
                <li>Both - 3</li>
              </ul>
            </td>
          </tr>
          <tr>
            <td>
              <strong>II. Delivery of Service</strong>
            </td>
            <td>
              <table style="table-layout: fixed;">
                <thead>
                  <tr>
                    <th>TOTAL SCORE</th>
                    <th>Importance Score</th>
                    <th>WF</th>
                    <th>Satisfaction Score</th>
                    <th>Weighted Score</th>
                  </tr>
                </thead>
                <tbody class="delivery-of-service">
                  <tr>
                    <td colspan="5">
                      <strong>Delivery Time</strong>
                    </td>
                  </tr>
                  <tr>
                    <td>0</td><!-- : TOTAL SCORE -->
                    <td>0</td><!-- : Importance Score -->
                    <td>0</td><!-- : WF -->
                    <td>0</td><!-- : Satisfaction Score -->
                    <td>0</td><!-- : Weighted Score -->
                  </tr>
                  <tr>
                    <td colspan="5">
                      <strong>Correctness and accuracy of test results</strong>
                    </td>
                  </tr>
                  <tr>
                    <td>0</td><!-- Delivery Time: TOTAL SCORE -->
                    <td>0</td><!-- Delivery Time: Importance Score -->
                    <td>0</td><!-- Delivery Time: WF -->
                    <td>0</td><!-- Delivery Time: Satisfaction Score -->
                    <td>0</td><!-- Delivery Time: Weighted Score -->
                  </tr>
                  <tr>
                    <td colspan="5">
                      <strong>Speed of service</strong>
                    </td>
                  </tr>
                  <tr>
                    <td>0</td><!-- Speed of Service: TOTAL SCORE -->
                    <td>0</td><!-- Speed of Service: Importance Score -->
                    <td>0</td><!-- Speed of Service: WF -->
                    <td>0</td><!-- Speed of Service: Satisfaction Score -->
                    <td>0</td><!-- Speed of Service: Weighted Score -->
                  </tr>
                  <tr>
                    <td colspan="5">
                      <strong>Cost</strong>
                    </td>
                  </tr>
                  <tr>
                    <td>0</td><!-- Cost: TOTAL SCORE -->
                    <td>0</td><!-- Cost: Importance Score -->
                    <td>0</td><!-- Cost: WF -->
                    <td>0</td><!-- Cost: Satisfaction Score -->
                    <td>0</td><!-- Cost: Weighted Score -->
                  </tr>
                  <tr>
                    <td colspan="5">
                      <strong>Attitude of staff</strong>
                    </td>
                  </tr>
                  <tr>
                    <td>0</td><!-- Attitude of Staff: TOTAL SCORE -->
                    <td>0</td><!-- Attitude of Staff: Importance Score -->
                    <td>0</td><!-- Attitude of Staff: WF -->
                    <td>0</td><!-- Attitude of Staff: Satisfaction Score -->
                    <td>0</td><!-- Attitude of Staff: Weighted Score -->
                  </tr>
                  <tr>
                    <td></td>
                    <td>0</td><!-- Total Importance Score  -->
                    <td>0</td><!-- Total WF -->
                    <td></td>
                    <td>0</td><!-- Total Weighted Score -->
                  </tr>
                  <tr class="highlight">
                    <td colspan="4">
                      <strong>SATISFACTION INDEX:</strong>
                    </td>
                    <td>0</td><!-- SATISFACTION INDEX -->
                  </tr>
                  <tr class="highlight">
                    <td colspan="4">
                      <strong>OVER-ALL CUSTOMER EXPERIENCE:</strong>
                    </td>
                    <td>0</td><!-- OVER-ALL CUSTOMER EXPERIENCE -->
                  </tr>
                  <tr class="highlight">
                    <td colspan="4">
                      <strong>NET PROMOTER SCORE:</strong>
                    </td>
                    <td>0</td><!-- NET PROMOTER SCORE -->
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td>
              <strong>III. Comment's/Suggestions</strong>
            </td>
            <td>
              <ol>
                <!-- LOOP THROUGH EACH COMMENT -->
                <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam dicta error vel ducimus tempore distinctio saepe velit exercitationem minima ipsum ullam id porro totam ipsam, quae cumque adipisci! Dicta, in.</li>
                <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse consequatur recusandae voluptates animi repudiandae facere earum cum veniam suscipit nulla dolores sapiente sed adipisci maiores, alias culpa, facilis modi libero?</li>
                <li>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Iste esse nesciunt, aspernatur unde veritatis autem! Iusto fuga, voluptas consequuntur dolorem vero recusandae, eius earum sint soluta eaque, autem aspernatur est?</li>
              </ol>
            </td>
          </tr>
          <tr>
            <td>
              <strong>IV. Actions</strong>
            </td>
            <td></td><!-- ACTIONS TAKEN -->
          </tr>
        </tbody>
      </table>
    </div>
    <div class="footer">
      <div></div>
      <div></div>
      <div>
        <p>Processed by:</p>
        <div>
          <strong>ROSEMARIE S. SALAZAR</strong>
          <p>Quality Manager</p>
        </div>
      </div>
    </div>
  </body>
</html>


