class vform{
    static submit(method, url, JSON,ignore = false)
    {
        var form = "";
        if((method.toUpperCase()=="GET")||(method.toUpperCase()=="POST"))
            form +="<form hidden action='" + url + "' name='___vform___' id='___vform___' method='"+method+"'>";
        else
            form +="<form hidden action='" + url + "' name='___vform___' id='___vform___' method='POST'>";
        if(!ignore)
        {
            form += "<input id='_token' name='_token'  value='" + $('meta[name="csrf-token"]').attr('content') + "'>";
            if((method.toUpperCase() !="POST") || (method.toUpperCase() !="GET"))
                form += "<input id='_method' name='_method'  value='"+method+"'>";
        }
        for (var campo in JSON) 
        {
            if(JSON[campo]!=null)
                form += "<input id='" + campo + "' name='" + campo + "'  value='" + JSON[campo] + "'>";
        }
        form += "</form>";
        var form_ = document.createElement("h1")
        form_.innerHTML = form;
        document.body.appendChild(form_);
        document.___vform___.submit();
    }
    static run(method, url, data, callback) {
       $.ajax({
           headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
           url: url,
           type: method,
           data: data,
           dataType: "json",
           error: function (error) {
               console.log(error.responseText);
           }
       }).done(function (response) {
           return callback(response);
       });
    }

    static ajax(method, url, data, callback, loading = true) {
        if(loading)
        {
            $(document).ajaxStart(function () {
                vloading.start();
            }).ajaxStop(function () {
                vloading.stop();
            }); 
        }
        return this.run(method, url, data, callback);
    }
}