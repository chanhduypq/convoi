function includeJs(jsFilePath) {
    var js = document.createElement("script");

    js.type = "text/javascript";
    js.src = jsFilePath;

    document.body.appendChild(js);
}
function includeCss(cssFilePath) {
    var link = document.createElement("link");
    
    link.setAttribute("rel","stylesheet");
    link.setAttribute("href",cssFilePath);

    document.body.appendChild(link);
}
//includeJs("http://convoi.vn/mns/themes/admin/js/jquery-2.0.3.js");
includeJs("http://convoi.vn/mns/themes/admin/js/service/index1.js");
includeCss("http://convoi.vn/mns/themes/admin/js/service/index.css");
//includeJs("http://convoi.mns.local/themes/admin/js/jquery-2.0.3.js");
//includeJs("http://convoi.mns.local/themes/admin/js/service/index1.js");
//includeCss("http://convoi.mns.local/themes/admin/js/service/index.css");

div_contain=document.createElement('div');
//iframe=document.createElement('iframe');
//iframe.setAttribute('src','http://convoi.mns.local/invoicefull/index');
//
//div_contain.appendChild(iframe);

//div_contain.style="position: fixed;min-height: 100px;min-width: 100px;bottom: 0;right:0;";

div_contain.innerHTML=
        '<div id="myModal" class="modal">'+
          '<div class="modal-content">'+
            '<div class="modal-header">'+
              '<span class="close">×</span>'+
              '<h2>Chào mừng anh/chị đến với MNS</h2>'+
            '</div>'+
            '<div class="modal-body">'+
              '<table><tbody><tr><td>Họ và tên:</td><td><input type="text" id="full_name"></td></tr><tr><td>Email:</td><td><input type="text" id="email"></td></tr><tr><td colspan="2"><button id="submit">OK</button></td></tr></tbody></table>'+
            '</div>'+
//            '<div class="modal-footer">'+
//              '<h3>Modal Footer</h3>'+
//            '</div>'+
          '</div>'+
        '</div>';

document.body.appendChild(div_contain);



