<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Customer Satisfaction Feedback Surver - Print Layout</title>

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

    .text-center { text-align: center; }
    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    .header > p:nth-child(1), .header > p:nth-child(2) {
      font-weight: bold;
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
    <h1 style="
      text-align: center;
      font-size: large;
    ">
      Customer Satisfaction Feedback Survey
    </h1>
    <br />
    <h2 style="font-size: larger;">I. Information</h2>
    <table style="table-layout: fixed;">
      <tbody>
        <tr>
          <td colspan="3">Customer Name:</td>
        </tr>
        <tr style="border-width: 0 !important;">
          <td>
            <div><input type="checkbox" name="" id=""> Raw and processed food</div>
            <div><input type="checkbox" name="" id=""> Marine products</div>
            <div><input type="checkbox" name="" id=""> Canned/Bottled Fish</div>
            <div><input type="checkbox" name="" id=""> Fishmeal</div>
            <div><input type="checkbox" name="" id=""> Seaweeds</div>
          </td>
          <td>
            <div><input type="checkbox" name="" id=""> Petroleum Products/Haulers</div>
            <div><input type="checkbox" name="" id=""> Mining</div>
            <div><input type="checkbox" name="" id=""> Hospitals</div>
            <div><input type="checkbox" name="" id=""> Academe/Students</div>
            <div><input type="checkbox" name="" id=""> Beverage and juices</div>
          </td>
          <td>
            <div><input type="checkbox" name="" id=""> Government/LGUs</div>
            <div><input type="checkbox" name="" id=""> Construction</div>
            <div><input type="checkbox" name="" id=""> Water Refilling/Bottled Water</div>
            <div><input type="checkbox" name="" id=""> Others</div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="footer"></div>
</body>
</html>