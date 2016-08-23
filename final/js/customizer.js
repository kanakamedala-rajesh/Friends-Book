var canvasColor;
var ctxColor;
var bMouseDown = false;

$(function(){

    // create canvas and context objects
    canvasColor = document.getElementById('color_canvas');
    ctxColor = canvasColor.getContext('2d');

    drawGradients(ctxColor);

    $('#color_canvas').mousemove(function(e) { // mouse move handler
        var canvasOffset = $(canvasColor).offset();
        var canvasX = Math.floor(e.pageX - canvasOffset.left);
        var canvasY = Math.floor(e.pageY - canvasOffset.top);

        var imageData = ctxColor.getImageData(canvasX, canvasY, 1, 1);
        var pixel = imageData.data;

        var pixelColor = "rgba("+pixel[0]+", "+pixel[1]+", "+pixel[2]+", "+pixel[3]+")";
        $('#preview').css('backgroundColor', pixelColor);
    });

    $('#color_canvas').click(function(e) { // mouse click handler
        var canvasOffset = $(canvasColor).offset();
        var canvasX = Math.floor(e.pageX - canvasOffset.left);
        var canvasY = Math.floor(e.pageY - canvasOffset.top);

        var imageData = ctxColor.getImageData(canvasX, canvasY, 1, 1);
        var pixel = imageData.data;

        var pixelColor = "rgba("+pixel[0]+", "+pixel[1]+", "+pixel[2]+", "+pixel[3]+")";
        $('.container').css('backgroundColor', pixelColor);

        var dColor = pixel[2] + 256 * pixel[1] + 65536 * pixel[0];
        $('#color').val(dColor.toString(16));
    }); 

    $('#panel').mousedown(function(e) { // mouse down handler
        bMouseDown = true;
    });
    $('#panel').mouseup(function(e) { // mouse up handler
        bMouseDown = false;
    });
});

function drawGradients() {
    var grad = ctxColor.createLinearGradient(20, 0, canvasColor.width - 20, 0);
    grad.addColorStop(0, 'red');
    grad.addColorStop(1 / 6, 'orange');
    grad.addColorStop(2 / 6, 'yellow');
    grad.addColorStop(3 / 6, 'green')
    grad.addColorStop(4 / 6, 'aqua');
    grad.addColorStop(5 / 6, 'blue');
    grad.addColorStop(1, 'purple');
    ctxColor.fillStyle=grad;
    ctxColor.fillRect(0, 0, canvasColor.width, canvasColor.height);
}