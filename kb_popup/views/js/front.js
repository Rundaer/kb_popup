function addEvent(obj, evt, fn) {
    if (obj.addEventListener) {
        obj.addEventListener(evt, fn, false);
    } else if (obj.attachEvent) {
        obj.attachEvent("on" + evt, fn);
    }
}

addEvent(document, 'mouseout', function(evt) {
    if (evt.toElement == null && evt.relatedTarget == null) {
        var id_popup = $('#myModal').attr('popup-id');
        var id_product = $('#myModal').attr('product-id');
        if (document.cookie.indexOf("Modal"+id_popup+"-"+id_product+"Shown=true") < 0) {

            $('#myModal').modal('show');

            $("#myModalClose").click(function () {
                $("#myModal").modal("hide");
            });
            document.cookie = "Modal"+id_popup+"-"+id_product+"Shown=true; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
        }
    };
});