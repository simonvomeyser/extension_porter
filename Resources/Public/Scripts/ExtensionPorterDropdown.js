//Provides the function to toggle the fadeIn or fadeOut of an DOM-Object
//(First used in dropdown table of Mctcategory/List.html)
//
function toggle_fade_in_or_out (id_to_fade, image_to_change) {
    var src_closed = "typo3conf/ext/lms/Resources/Public/Icons/dropdown/dropdown_closed.png";
    var src_open = "typo3conf/ext/lms/Resources/Public/Icons/dropdown/dropdown_open.png";

    var actual_src = jQuery("#"+image_to_change).attr('src');
    console.log (id_to_fade + "- -" + image_to_change + "- -" + actual_src); //Debug

    if (actual_src == src_closed) {
        jQuery("#"+id_to_fade).slideDown("slow", function(){});
        jQuery("#"+image_to_change).attr('src', src_open);
    } else{
        jQuery("#"+id_to_fade).slideUp("slow", function(){});
        jQuery("#"+image_to_change).attr('src', src_closed);
    };
}

