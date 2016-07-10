<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tanks</title>
</head>
<body>
    <canvas id="canvas" width="400" height="400"></canvas>
    <script>
        var img = new Image;
        img.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAxRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTExIDc5LjE1ODMyNSwgMjAxNS8wOS8xMC0wMToxMDoyMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6M0I4M0VFQjAzNkQ3MTFFNjk3OTRENzg0RTZGNzFCNTgiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6M0I4M0VFQUYzNkQ3MTFFNjk3OTRENzg0RTZGNzFCNTgiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgV2luZG93cyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSI3QzJDQkM3OTExMDY0RUQyODlEQzQ0MUQ4N0YyNjI1NSIgc3RSZWY6ZG9jdW1lbnRJRD0iN0MyQ0JDNzkxMTA2NEVEMjg5REM0NDFEODdGMjYyNTUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6QO9hYAAAaX0lEQVR42uxdaYwk51l+q7qqz7nve2d3Z3e9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';
        
        IMAGE_OFFSET_X = 150;
        IMAGE_OFFSET_Y = 150;
        SIZE_IMAGE = 100;
        SIZE_CANVAS = 400;
        CODE_LEFT_ARROW = 37;
        CODE_UP_ARROW = 38;
        CODE_RIGHT_ARROW = 39;
        CODE_DOWN_ARROW = 40;
        CODE_ENTER = 13;
        CODE_ARROWS = [37, 38, 39, 40];
        TRANSLATE_VALUE_X = IMAGE_OFFSET_X+SIZE_IMAGE*0.5;
        TRANSLATE_VALUE_Y = IMAGE_OFFSET_Y+SIZE_IMAGE*0.5;
        TRANSLATE_VALUE_NEGATIVE_X = -(IMAGE_OFFSET_X+SIZE_IMAGE*0.5);
        TRANSLATE_VALUE_NEGATIVE_Y = -(IMAGE_OFFSET_Y+SIZE_IMAGE*0.5);
        RAD_TO_DEG = Math.PI/180;
        ROTATE_COUNTERCLOCKWISE = RAD_TO_DEG*270;
        ROTATE_CLOCKWISE = RAD_TO_DEG*90;
        ROTATE_OPPOSITE = RAD_TO_DEG*180;
        TANK_STEP = 10;
        GUID = createGuid();
        
        var socket = new WebSocket("ws://185.154.13.92:8124");
        tankDataSingle = {'id': GUID,'currentd':37,'newd':38,'status':'initial','x':IMAGE_OFFSET_X,'y':IMAGE_OFFSET_Y};
        socket.onopen = function () {
            console.log("socket opened v2");
            socket.send(JSON.stringify(tankDataSingle));
        };
        tankData = [];
        
        var canvas = document.getElementById('canvas');
        var canvasContext = canvas.getContext('2d');
        canvasContext.save();
//        canvasContext.fillStyle = "#4D4E53";
//        canvasContext.fillRect(IMAGE_OFFSET_X, IMAGE_OFFSET_Y, SIZE_IMAGE, SIZE_IMAGE);
//        canvasContext.clearRect(IMAGE_OFFSET_X+10,IMAGE_OFFSET_Y+10,SIZE_IMAGE/10,SIZE_IMAGE/10);
        canvasContext.drawImage(img, IMAGE_OFFSET_X, IMAGE_OFFSET_Y);
        currentDirectionCode = CODE_LEFT_ARROW;
        window.addEventListener('keydown', function (e) {
            if (e.keyCode === CODE_ENTER) { // not arrow
                console.log("enter code");
                canvasContext.fillStyle = "#4D4E53";
                xsize = 10;
                ysize = 10;
                bofsetx = TRANSLATE_VALUE_X;
                bofsety = TRANSLATE_VALUE_Y;
                console.log("currentDirectionCode"+currentDirectionCode);
//                console.log("bdirection1"+bdirection);
                var intervalID = setInterval(bulletMove, 500, currentDirectionCode);
                function bulletMove(bdirection) {
//                    if(bofsetx == 200) {
//                        clearInterval(intervalID);
//                    }
                    console.log("bdirection2"+bdirection);
                    canvasContext.clearRect(bofsetx,bofsety,xsize,ysize);
                    console.log('bofsetx'+bofsetx+'bofsety'+bofsety);
                    if (bdirection == CODE_LEFT_ARROW) {
                        bofsetx-=10;
                    }
                    console.log("bdirection3"+bdirection);
                    if (bdirection == CODE_UP_ARROW) {
                        bofsety-=10;
                    }
                    console.log("bdirection4"+bdirection);
                    if (bdirection == CODE_RIGHT_ARROW) {
                        bofsetx+=10;
                    }
                    console.log("bdirection5"+bdirection);
                    if (bdirection == CODE_DOWN_ARROW) {
                        bofsety+=10;
                    }
                    console.log("bdirection6"+bdirection);
                    console.log("bullet");
                    console.log("bdirection7"+bdirection);
                    console.log('bofsetx'+bofsetx+'bofsety'+bofsety+'xsize'+xsize+'ysize'+ysize);
                    canvasContext.fillRect(bofsetx, bofsety, xsize, ysize);
                    console.log("bdirection8"+bdirection);
                }
                if (currentDirectionCode == CODE_LEFT_ARROW || currentDirectionCode == CODE_RIGHT_ARROW) {
                    var timend = ((SIZE_CANVAS - bofsetx) / 10) * 500;
                }
                if (currentDirectionCode == CODE_UP_ARROW || currentDirectionCode == CODE_DOWN_ARROW) {
                    var timend = ((SIZE_CANVAS - bofsety) / 10) * 500;
                }
                console.log("timend"+timend);
                setTimeout(function() { clearInterval(intervalID); }, timend);
    
    
                return;
            }
            if (CODE_ARROWS.indexOf(e.keyCode) === -1) { // not arrow
                console.log("not arrow");
                return;
            }
            tempTankData = {'id': GUID, 'newd':e.keyCode};
            socket.send(JSON.stringify(tempTankData));
        });

        socket.onmessage = function (event) {
            tankData = JSON.parse(event.data);
            canvasContext.clearRect(0, 0, SIZE_CANVAS, SIZE_CANVAS);
            tankData.forEach(function(item, index) {
                var currentTankDataParsed = JSON.parse(item);
                console.log("item.id"+currentTankDataParsed.id);
                console.log("GUID"+GUID);
                if(currentTankDataParsed.id == GUID) {
                    currentDirectionCode = currentTankDataParsed.direction;
                    IMAGE_OFFSET_X = currentTankDataParsed.x;
                    IMAGE_OFFSET_Y = currentTankDataParsed.y;
                    console.log('currentDirectionCode'+currentDirectionCode);
                    console.log('IMAGE_OFFSET_X'+IMAGE_OFFSET_X);
                    console.log('IMAGE_OFFSET_Y'+IMAGE_OFFSET_Y);
                }
                tankLogic(currentTankDataParsed, index);
            });
            
        };
        function tankLogic(currentTankData, index) {
            recalcTranslate(currentTankData);
            canvasContext.save();
            canvasContext.translate(TRANSLATE_VALUE_X, TRANSLATE_VALUE_Y);
            if (currentTankData.direction == CODE_LEFT_ARROW) {
            }
            if (currentTankData.direction == CODE_UP_ARROW) {
                canvasContext.rotate(ROTATE_CLOCKWISE);
            }
            if (currentTankData.direction == CODE_RIGHT_ARROW) {
                canvasContext.rotate(ROTATE_OPPOSITE);
            }
            if (currentTankData.direction == CODE_DOWN_ARROW) {
                canvasContext.rotate(ROTATE_COUNTERCLOCKWISE);
            }
            canvasContext.translate(TRANSLATE_VALUE_NEGATIVE_X, TRANSLATE_VALUE_NEGATIVE_Y);
            canvasContext.drawImage(img, currentTankData.x, currentTankData.y);
            canvasContext.restore();
        }

        function recalcTranslate(currentTankData) {
            TRANSLATE_VALUE_X = currentTankData.x+SIZE_IMAGE*0.5;
            TRANSLATE_VALUE_Y = currentTankData.y+SIZE_IMAGE*0.5;
            TRANSLATE_VALUE_NEGATIVE_X = -(currentTankData.x+SIZE_IMAGE*0.5);
            TRANSLATE_VALUE_NEGATIVE_Y = -(currentTankData.y+SIZE_IMAGE*0.5);
        }

        function createGuid()
        {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random()*16|0, v = c === 'x' ? r : (r&0x3|0x8);
                return v.toString(16);
            });
        }

        function findTank(element) {
            if (element.id == GUID) {
                return true;
            }
            return false;
        }

    </script>
</body>
</html>