$('#myTable').DataTable({
    responsive: true,
    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
    "aaSorting":[[0,"desc"]],
    "language": {
        "lengthMenu": "Mostrar _MENU_ registros",
        "zeroRecords": "No se encontraron registros coincidentes",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty": "No hay registros disponibles",
        "infoFiltered": "(filtrado de _MAX_ registros totales)",
        "sLoadingRecords": "Cargando...",
        "sProcessing": "Por favor espere...",
        "search": "",
        "sSearchPlaceholder": "Buscar...",
        "aLengthMenu": "Display _MENU_ records",
        "oPaginate": {
            "sFirst": "Primero",
            "sPrevious": "Anterior",
            "sNext": "Siguiente",
            "sLast": "Ultimo"
        }
    }
    // para ordenar la primera columna en orden desc en el archivo  jquery.dataTables.min.js
    // buscar aaSorting:[[0,"asc"]] y setear a desc
});

// BACK TO TOP
document.addEventListener('DOMContentLoaded', function() {
    // Back to Top - by CodyHouse.co
    var backTop = document.getElementsByClassName('js-cd-top')[0],
        // browser window scroll (in pixels) after which the "back to top" link is shown
        offset = 20,
        //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
        offsetOpacity = 1200,
        scrollDuration = 700,
        scrolling = false;
    if( backTop ) {
        //update back to top visibility on scrolling
        window.addEventListener("scroll", function(event) {
            if( !scrolling ) {
                scrolling = true;
                (!window.requestAnimationFrame) ? setTimeout(checkBackToTop, 250) : window.requestAnimationFrame(checkBackToTop);
            }
        });
        //smooth scroll to top
        backTop.addEventListener('click', function(event) {
            event.preventDefault();
            (!window.requestAnimationFrame) ? window.scrollTo(0, 0) : scrollTop(scrollDuration);
        });
    }

    function checkBackToTop() {
        var windowTop = window.scrollY || document.documentElement.scrollTop;
        ( windowTop > offset ) ? addClass(backTop, 'cd-top--show') : removeClass(backTop, 'cd-top--show', 'cd-top--fade-out');
        ( windowTop > offsetOpacity ) && addClass(backTop, 'cd-top--fade-out');
        scrolling = false;
    }
    
    function scrollTop(duration) {
        var start = window.scrollY || document.documentElement.scrollTop,
            currentTime = null;
            
        var animateScroll = function(timestamp){
            if (!currentTime) currentTime = timestamp;        
            var progress = timestamp - currentTime;
            var val = Math.max(Math.easeInOutQuad(progress, start, -start, duration), 0);
            window.scrollTo(0, val);
            if(progress < duration) {
                window.requestAnimationFrame(animateScroll);
            }
        };

        window.requestAnimationFrame(animateScroll);
    }

    Math.easeInOutQuad = function (t, b, c, d) {
        t /= d/2;
        if (t < 1) return c/2*t*t + b;
        t--;
        return -c/2 * (t*(t-2) - 1) + b;
    };

    //class manipulations - needed if classList is not supported
    function hasClass(el, className) {
        if (el.classList) return el.classList.contains(className);
        else return !!el.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'));
    }
    function addClass(el, className) {
        var classList = className.split(' ');
        if (el.classList) el.classList.add(classList[0]);
        else if (!hasClass(el, classList[0])) el.className += " " + classList[0];
        if (classList.length > 1) addClass(el, classList.slice(1).join(' '));
    }
    function removeClass(el, className) {
        var classList = className.split(' ');
        if (el.classList) el.classList.remove(classList[0]);    
        else if(hasClass(el, classList[0])) {
            var reg = new RegExp('(\\s|^)' + classList[0] + '(\\s|$)');
            el.className=el.className.replace(reg, ' ');
        }
        if (classList.length > 1) removeClass(el, classList.slice(1).join(' '));
    }
});

// END BACK TO TOP

$(function () {
  $('[data-toggle="popover"]').popover()
})