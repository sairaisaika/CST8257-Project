let params = new URLSearchParams(location.search);
$(document).ready(function(){
    $.ajax({
        type:"POST",
        url:"./ImageLoader.php",
        data:{imgId:params.get('img')},
        success: function(response){
            var jsonData = JSON.parse(response);
            if(jsonData.img!='error'){
                $("#canvas").append(jsonData.img);
            }else{
                alert("Opps, something went wrong!");
            }
        }
    });
    $("#canvas").append('')
});

$("#canvas").mouseenter(function(){
    $("#control").fadeIn(200);
}).mouseleave(function(){
    $("#control").fadeOut(200);
});

$("#rc").mouseenter(function(){
    $("#control").fadeIn(200);
}).mouseleave(function(){
    $("#control").fadeOut(200);
});
$("#lc").mouseenter(function(){
    $("#control").fadeIn(200);
}).mouseleave(function(){
    $("#control").fadeOut(200);
});
$("#control").mouseenter(function(){
    $("#control").fadeIn(200);
}).mouseleave(function(){
    $("#control").fadeOut(200);
});
//Could be better

function downloadImg(id){
    window.location.href = "./ImageDownload.php?imgId="+id;
}

function loadimage(direction) {
    $.ajax({
        type:"POST",
        url:"ImageLoader.php",
        data: {imgId:params.get('img'), r:direction},
        success: function(response){
            var jsonData = JSON.parse(response);
            //alert(jsonData.err);
            if(jsonData.img!='error'){
                $("#canvas").html("");
                $("#canvas").append(jsonData.img);
            }else{
                alert("Opps, something wrong!");
            }
        }
    });
}

function changeImg(imgId) {
    window.location.href = "./MyPictures.php?img="+imgId;
}

function changeImgFri(imgId) {
    window.location.href = "./FriendPictures.php?img="+imgId;
}
function delImage(id){
    window.location.href = "./del_image.php?imgId="+id;
}
