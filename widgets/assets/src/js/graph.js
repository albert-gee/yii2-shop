/**
 * Created by Albert Gainutdinov.
 */

$(document).ready(function () {
    // var array = JSON.parse(graphPoints);
    var canvas = document.getElementById("graph-canvas");

    var startPoint = {'x': 65, 'y': 50};
    var graphHeight = canvas.height - startPoint.y;
    var graphWidth = canvas.width - startPoint.x - 50;


    /*GETS GRAPH POINTS FROM WIDGET*/
    var points = graphPoints[0];
    var sign = graphPoints[1]['sign'];
    var pointsCount = points.length;


    /*GETS MAX Y*/
    var pointsValues = [];
    for (i = 0; i < points.length; i++) {
        pointsValues.push(points[i][1]);
    }
    var max = Math.round(Math.max.apply(null, pointsValues) / 100) * 100;


    var quotient = graphHeight / max;

    for (var i = 0; i < points.length; i++) {
        points[i][1] = Number(points[i][1]) * quotient;
    }

    drawGraph();

    function drawGraph() {


        if (canvas.getContext) {

            var ctx = canvas.getContext("2d");


            drawGrid();
            drawGraphLine();
            drawAbscissaAndOrdinate();

            function drawGrid() {
                /*Draws straight lines parallel to the x-axis*/
                for (var i = 1; i < 5; i++) {
                    ctx.beginPath();
                    ctx.moveTo(startPoint.x, i * graphHeight / 5);
                    ctx.lineTo(startPoint.x + 500, i * graphHeight / 5);
                    ctx.lineWidth = 1;
                    ctx.strokeStyle = "#afafaf";
                    ctx.stroke();
                    ctx.closePath();

                    /*Draws y-axis values*/
                    var roundedMaxYValue = Math.round(max / 100) * 100;
                    roundedMaxYValue = (roundedMaxYValue > 0) ? roundedMaxYValue : 100000;
                    ctx.fillText((roundedMaxYValue - i * roundedMaxYValue / 5).toString() + ' ' + sign, 0,
                        i * graphHeight / 5);
                }
                /*Draws straight lines parallel to the y-axis*/
                for (i = 1; i < (pointsCount); i++) {
                    ctx.beginPath();
                    var xPosition = startPoint.x + i * graphWidth/(pointsCount - 1);
                    ctx.moveTo(xPosition, 0);
                    ctx.lineTo(xPosition, graphHeight);
                    ctx.lineWidth = 1;
                    ctx.strokeStyle = "#afafaf";
                    ctx.stroke();
                    ctx.closePath();
                }
            }

            /*DRAWS ABSCISSA AND ORDINATE AXIS*/
            function drawAbscissaAndOrdinate() {
                ctx.beginPath();
                ctx.moveTo(startPoint.x, 0);
                ctx.lineTo(startPoint.x, graphHeight);
                ctx.lineTo(startPoint.x + 500, graphHeight);
                ctx.lineWidth = 2;
                ctx.strokeStyle = "#3e3e3e";
                ctx.stroke();
                ctx.closePath();
            }


            /*DRAWS GRAPH LINE BY POINTS*/
            function drawGraphLine() {
                ctx.beginPath();
                ctx.moveTo(startPoint.x, graphHeight - points[0][1]);
                ctx.fillText(points[0][0], startPoint.x / 2, graphHeight + 20);
                for (var i = 1; i < pointsCount; i++) {
                    ctx.lineTo(startPoint.x + i * (graphWidth / (pointsCount - 1)), graphHeight - points[i][1]);

                    /*Draws x-axis values*/
                    ctx.fillText(points[i][0], startPoint.x / 2 + i * (graphWidth / (pointsCount - 1)), graphHeight + 20);
                }

                ctx.lineTo(startPoint.x + i * (graphWidth / (pointsCount - 1)), graphHeight);
                ctx.lineTo(startPoint.x, graphHeight);

                ctx.lineWidth = 1.5;
                ctx.fillStyle = "rgba(0, 0, 255, .5)";

                ctx.fill();
            }



        }
    }
});


