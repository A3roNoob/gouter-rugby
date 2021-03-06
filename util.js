function httpPostAsync(theUrl, params, callback) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
            callback(xmlHttp.responseText);
    }
    xmlHttp.open("POST", theUrl, true); // true for asynchronous 
    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xmlHttp.send(params);
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function hideRang(rang){
    if(rang == 2){
        document.getElementById('course').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nEnregistrer une course\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/shopping-cart-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('ajout_conso').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nEnregistrer une consommation\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/cash-register-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('gerer_produit').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nG&eacute;rer un produit\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/can-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('gerer_compte_enfant').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nG&eacute;rer un compte enfant\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/note-petit.png\" class=\"align-right\">\n</div>\n</div>";
    }
    if(rang == 1){
        document.getElementById('course').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nEnregistrer une course\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/shopping-cart-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('ajout_conso').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nEnregistrer une consommation\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/cash-register-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('gerer_produit').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nG&eacute;rer un produit\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/can-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('gerer_compte_enfant').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nG&eacute;rer un compte enfant\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/note-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('historique').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nAfficher historique et journaux\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/agenda-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('gerer_compte').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nG&eacute;rer les comptes\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/settings-petit.png\" class=\"align-right\">\n</div>\n</div>";
    }
}

function getRang(param){
    var rang;
    httpPostAsync("/api/rang",param,function(e){
        res = JSON.parse(e);
        if(res['Code'] == "0"){
            rang = res["Rang"];
            menuConnect(rang);
        }
    });
    return rang;
}

function menuRang(rang){
    if(rang == 3){
        document.getElementById('menu-link').href = "menu_normal.html";
    }
    else if(rang == 2){
        document.getElementById('menu-link').href = "menu_benevole.html";
    }
    else if(rang == 1){
        document.getElementById('menu-link').href = "menu_admin.html";
    }
}

function menuRedirect(rang){
    var path = window.location.pathname;
    var page = path.split("/").pop();
    console.log(page);
    if(rang == 3 && page != "menu_normal.html"){
        window.location.href = "menu_normal.html";
    }
    if(rang == 2 && page != "menu_benevole.html"){
        window.location.href = "menu_benevole.html";
    }
    if(rang == 1 && page != "menu_admin.html"){
        window.location.href = "menu_admin.html";
    }
}

function menuConnect(rang){
    if(rang == 1){
        window.location.href = "menu_admin.html";
    }
    if(rang == 2){
        window.location.href = "menu_benevole.html";
    }
    if(rang == 3){
        window.location.href = "menu_normal.html";
    }
}

function checkRang2(rang){
    if(rang == 3){
        window.location.href = document.getElementById("menu-link").href;
    }
}

function checkRang1(rang){
    if(rang == 3 || rang == 2){
        window.location.href = document.getElementById("menu-link").href;
    }
}

function findGetParameter(parameterName) {
    var result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
          tmp = item.split("=");
          if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}

function deleteToken(){
    var token = getCookie("token");
    var login = getCookie("login");
    var param = "token="+token+"&login="+login;
    httpPostAsync("api/deconnexion",param,function(e){
    });
    setCookie("token",0,1);
    window.location.href = "index.html";
}

function afficherCompte(param){
    httpPostAsync("/api/etatcomptes",param,function(e){
        var res = JSON.parse(e);
        var i;
        var solde = res['Solde'];
        document.getElementById('etats').innerHTML += "<a href=\"etat_compte.html?parent=yes&nb=-1\">\n<div class=\"row compte\">\n<p><h3><strong>"+prenom+" "+nom.toUpperCase()+"</strong> :</h3></p>\n<p class=\"tab\">Solde : <span id=\"solde\">"+solde+"</span> &euro;</p>\n</div>\n</a>\n";
        for(i=0;i<res['Enfants'].length;i++){
            var nomE = res['Enfants'][i]['Nom'].toUpperCase();
            var prenomE = res['Enfants'][i]['Prenom'];
            var soldeE = res['Enfants'][i]['Solde'];
            document.getElementById('etats').innerHTML += "<a href=\"etat_compte.html?parent=no&nb="+i+"\">\n<div class=\"row compte\">\n<p><h3>"+prenomE+" "+nomE+" :</h3></p>\n<p class=\"tab\">Solde : <span id=\"solde\">"+soldeE+"</span> &euro;</p>\n</div>\n</a>\n";
        }
    });
}

function stringProduit(res,i){
    return "<div class=\"panel-group\">"+
                "<a data-toggle=\"collapse\" href=\"#collapse"+i+"\">"+
                    "<div class=\"panel panel-default\">"+
                        "<div class=\"panel-heading\">"+
                            "<h4 class=\"panel-title\"><strong>"+
                            res['Produits'][i]['Nom']+
                            "</strong></h4>"+
                        "</div>"+
                    "</div>"+    
                    "<div id=\"collapse"+i+"\" class=\"panel-collapse collapse\">"+
                        "<div class=\"panel-body\">"+
                            "<p>Prix : <span style=\"font-style:italic;\">"+res['Produits'][i]['Prix']+"</span> &euro;</p>"+
                            "<p>"+res['Produits'][i]['Description']+"</p>"+
                        "</div>"+
                    "</div>"+
                "</a>"+
            "</div>";
}

function stringProduitC(res,i,j){
    return "<div class=\"panel-group\">"+
                "<a data-toggle=\"collapse\" href=\"#collapse"+j+"C"+i+"\">"+
                    "<div class=\"panel panel-default\">"+
                        "<div class=\"panel-heading\">"+
                            "<h4 class=\"panel-title\"><strong>"+
                            res['Produits'][i]['Nom']+
                            "</strong></h4>"+
                        "</div>"+
                    "</div>"+    
                    "<div id=\"collapse"+j+"C"+i+"\" class=\"panel-collapse collapse\">"+
                        "<div class=\"panel-body\">"+
                            "<p>Prix : <span style=\"font-style:italic;\">"+res['Produits'][i]['Prix']+"</span> &euro;</p>"+
                            "<p>"+res['Produits'][i]['Description']+"</p>"+
                        "</div>"+
                    "</div>"+
                "</a>"+
            "</div>";
}

function stringProduitCompo(res,i){
    var compo = "";
    console.log("in stringProduitCompo, i :"+ i);
    for(j=0;j<res['ProduitsComposes'][i]['Produits'].length;j++){
        compo += "<li class=\"list-group-item\">\n";
        compo += stringProduitC(res['ProduitsComposes'][i],j,i);
        compo += "</li>\n";
    }

    console.log(compo);

    var ret = "<div class=\"panel-group\">"+
                "<a data-toggle=\"collapse\" href=\"#collapse0"+i+"\">"+
                    "<div class=\"panel panel-default\">"+
                        "<div class=\"panel-heading\">"+
                            "<h4 class=\"panel-title\">"+
                                "<strong>"+res['ProduitsComposes'][i]['Nom']+"</strong>"+
                            "</h4>"+
                        "</div>"+
                        "<div id=\"collapse0"+i+"\" class=\"panel-collapse collapse\">"+
                        "<ul class=\"list-group\">"+
                            "<li class=\"list-group-item\">"+res['ProduitsComposes'][i]['Prix']+" &euro;</li>"+
                            compo+
                        "</ul>"+
                    "</div>"+
                "</a>"+
            "</div>";

    return ret;
}

document.head.innerHTML += "\n<link rel=\"icon\" href=\"images/rugby-ball.ico\" />\n";