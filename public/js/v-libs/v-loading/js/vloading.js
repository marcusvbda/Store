class vloading 
{
    static start() 
    {   
        $("html").prepend("<div id='PopupMask' class='ui-widget-overlay ui-front' style='z-index: 9999998;cursor: wait;cursor: progress;background-color:unset;'></div>");
        // $("html").prepend("<div id='PopupMask' style='position:fixed;width:100%;height:100%;z-index:9999998;cursor: wait;cursor: progress;'></div>");
        // $("#PopupMask").css('opacity', .7);
    }
    static stop() 
    {
        $("#PopupMask").remove();
    }
}