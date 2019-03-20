//https://www.w3.org/TR/WCAG20-TECHS/SCR19.html
//https://www.packtpub.com/application-development/building-restful-web-services-php-7
document.addEventListener('DOMContentLoaded', function() {

    var provincia = document.getElementById("province");
    /*debido a que la vistas son distintas solo llamaremos al método cargaLocalidades
    cuando tengamos seguridad que en la vista se encuentre el id province*/
    if(provincia) {
        provincia.onchange = cargaLocalidades  
    }

    if(provincia){
        cargaLocalidades();
    }

    function inicializa_xhr()
    {
        if (window.XMLHttpRequest) {
            return new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            return new ActiveXObject("Microsoft.XMLHTTP");
        }
    }

    function cargaLocalidades()
    {
        let lista = document.getElementById("province");
        let provincia = lista.options[lista.selectedIndex].value;

        let listaLocalidad = document.getElementById("localidad");

        if(provincia != "" && lista.selectedIndex != 0){
            listaLocalidad.removeAttribute("disabled");
            // remueve todos los espacios en blanco del string
            provincia = provincia.replace(/ /g, '');
            // !isNaN(provincia)

            if (provincia) {

                peticion = inicializa_xhr();
                if (peticion) {
                    peticion.onreadystatechange = muestraLocalidades;

                    peticion.open("POST", "http://localhost/postcode/json/facade.php?nocache=" + Math.random(), true);
                    peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    peticion.send("provincia=" + provincia);
                }
            }
            
        } else {
            listaLocalidad.setAttribute("disabled", true);
            listaLocalidad.options.length = 0;//elimina todos los elementos options
            return;
        }
    }

    function muestraLocalidades()
    {
        if (peticion.readyState == 4) {
            if (peticion.status == 200) {
                let lista = document.getElementById("localidad");
                // let localidades = eval('(' + peticion.responseText + ')');
                let localidades = JSON.parse(peticion.responseText);

                lista.options.length = 0;//("-- Seleccione una localidad --", "value aquí");
                lista.options[0] = new Option("-- Seleccione una localidad --");
                lista.options[0].setAttribute("disabled", true);
                lista.options[0].setAttribute("selected", true);
                let i = 1;
                let cp;
                for (let codigo in localidades) {
                    cp = localidades[codigo].cp;
                    cp = (cp != "") ? '(' + cp + ')' : "";   
                    lista.options[i] = new Option(localidades[codigo].nombre + ' ' + cp, localidades[codigo].id);
                    i++;
                }
            }
        }
    }

    function ucwords(str)
    {
        str = str.toLowerCase();
        var words = str.split(' ');
        str = '';
        for (var i = 0; i < words.length; i++) {
            var word = words[i];
            word = word.charAt(0).toUpperCase() + word.slice(1);
            if (i > 0) { str = str + ' '; }
            str = str + word;
        }
        return str;
    }     
});

