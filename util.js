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
        document.getElementById('ajout_fond_benevole').innerHTML = "<div class =\"row menu-item\">\n<div class=\"col-lg-8\">\nAjouter des fonds &agrave; un enfant\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/piggy-bank-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('course').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nEnregistrer une course\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/shopping-cart-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('ajout_conso').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nEnregistrer une consommation\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/cash-register-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('gerer_produit').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nG&eacute;rer un produit\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/can-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('gerer_compte_enfant').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nG&eacute;rer un compte enfant\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/note-petit.png\" class=\"align-right\">\n</div>\n</div>";
    }
    if(rang == 1){
        document.getElementById('ajout_fond_benevole').innerHTML = "<div class =\"row menu-item\">\n<div class=\"col-lg-8\">\nAjouter des fonds &agrave; un enfant\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/piggy-bank-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('course').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nEnregistrer une course\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/shopping-cart-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('ajout_conso').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nEnregistrer une consommation\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/cash-register-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('gerer_produit').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nG&eacute;rer un produit\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/can-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('gerer_compte_enfant').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nG&eacute;rer un compte enfant\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/note-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('historique').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nAfficher historique et journaux\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/agenda-petit.png\" class=\"align-right\">\n</div>\n</div>";
        document.getElementById('gerer_compte').innerHTML = "<div class=\"row menu-item\">\n<div class=\"col-lg-8\">\nG&eacute;rer les comptes\n</div>\n<div class=\"col-lg-2\"></div>\n<div class=\"col-lg-1\">\n<img src=\"images/settings-petit.png\" class=\"align-right\">\n</div>\n</div>";
    }
}

function menuRang(rang){
    console.log(rang);
    if(rang == 3){
        document.getElementById('menu-link').href = "menu_normal";
    }
    else if(rang == 2){
        document.getElementById('menu-link').href = "menu_benevole";
    }
    else if(rang == 1){
        document.getElementById('menu-link').href = "menu_admin";
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
        alert(e);
    });
    setCookie("token",0,1);
    window.location.href = "index.html";
}

document.head.innerHTML += "\n<link rel=\"icon\" href=\"images/rugby-ball.ico\" />\n";