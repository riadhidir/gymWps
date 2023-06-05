jQuery(document).ready(function(){
    var tec1Removed = localStorage.getItem('gym_removed');
    if (tec1Removed) {
        jQuery(".updated.notice").remove();
    } 
    jQuery(".notice-dismiss").click(function(){
        jQuery(".updated.notice").remove();
        localStorage.setItem('gym_removed', 'true');
    });
});
