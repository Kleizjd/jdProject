/*
 Highcharts JS v7.2.0 (2019-09-03)

 Boost module

 (c) 2010-2019 Highsoft AS
 Author: Torstein Honsi

 License: www.highcharts.com/license

 This is a Highcharts module that draws long data series on a canvas in order
 to increase performance of the initial load time and tooltip responsiveness.

 Compatible with WebGL compatible browsers (not IE < 11).

 If this module is taken in as part of the core
 - All the loading logic should be merged with core. Update styles in the
   core.
 - Most of the method wraps should probably be added directly in parent
   methods.

 Notes for boost mode
 - Area lines are not drawn
 - Lines are not drawn on scatter charts
 - Zones and negativeColor don't work
 - Dash styles are not rendered on lines.
 - Columns are always one pixel wide. Don't set the threshold too low.
 - Disable animations
 - Marker shapes are not supported: markers will always be circles

 Optimizing tips for users
 - Set extremes (min, max) explicitly on the axes in order for Highcharts to
   avoid computing extremes.
 - Set enableMouseTracking to false on the series to improve total rendering
      time.
 - The default threshold is set based on one series. If you have multiple,
   dense series, the combined number of points drawn gets higher, and you may
   want to set the threshold lower in order to use optimizations.
 - If drawing large scatter charts, it's beneficial to set the marker radius
   to a value less than 1. This is to add additional spacing to make the chart
   more readable.
 - If the value increments on both the X and Y axis aren't small, consider
   setting useGPUTranslations to true on the boost settings object. If you do
   this and the increments are small (e.g. datetime axis with small time
   increments) it may cause rendering issues due to floating point rounding
   errors, so your millage may vary.

 Settings
    There are two ways of setting the boost threshold:
    - Per series: boost based on number of points in individual series
    - Per chart: boost based on the number of series

  To set the series boost threshold, set seriesBoostThreshold on the chart
  object.
  To set the series-specific threshold, set boostThreshold on the series
  object.

  In addition, the following can be set in the boost object:
  {
      //Wether or not to use alpha blending
      useAlpha: boolean - default: true
      //Set to true to perform translations on the GPU.
      //Much faster, but may cause rendering issues
      //when using values far from 0 due to floating point
      //rounding issues
      useGPUTranslations: boolean - default: false
      //Use pre-allocated buffers, much faster,
      //but may cause rendering issues with some data sets
      usePreallocated: boolean - default: false
  }
*/
(function(f){"object"===typeof module&&module.exports?(f["default"]=f,module.exports=f):"function"===typeof define&&define.amd?define("highcharts/modules/boost",["highcharts"],function(t){f(t);f.Highcharts=t;return f}):f("undefined"!==typeof Highcharts?Highcharts:void 0)})(function(f){function t(e,f,d,G){e.hasOwnProperty(f)||(e[f]=G.apply(null,d))}f=f?f._modules:{};t(f,"modules/boost/boostables.js",[],function(){return"area arearange column columnrange bar line scatter heatmap bubble treemap".split(" ")});
t(f,"modules/boost/boostable-map.js",[f["modules/boost/boostables.js"]],function(e){var f={};e.forEach(function(d){f[d]=1});return f});t(f,"modules/boost/wgl-shader.js",[f["parts/Globals.js"]],function(e){var f=e.pick;return function(d){function w(){v.length&&e.error("[highcharts boost] shader error - "+v.join("\n"))}function k(a,b){var c=d.createShader("vertex"===b?d.VERTEX_SHADER:d.FRAGMENT_SHADER);d.shaderSource(c,a);d.compileShader(c);return d.getShaderParameter(c,d.COMPILE_STATUS)?c:(v.push("when compiling "+
b+" shader:\n"+d.getShaderInfoLog(c)),!1)}function q(){function e(b){return d.getUniformLocation(a,b)}var h=k("#version 100\n#define LN10 2.302585092994046\nprecision highp float;\nattribute vec4 aVertexPosition;\nattribute vec4 aColor;\nvarying highp vec2 position;\nvarying highp vec4 vColor;\nuniform mat4 uPMatrix;\nuniform float pSize;\nuniform float translatedThreshold;\nuniform bool hasThreshold;\nuniform bool skipTranslation;\nuniform float xAxisTrans;\nuniform float xAxisMin;\nuniform float xAxisMinPad;\nuniform float xAxisPointRange;\nuniform float xAxisLen;\nuniform bool  xAxisPostTranslate;\nuniform float xAxisOrdinalSlope;\nuniform float xAxisOrdinalOffset;\nuniform float xAxisPos;\nuniform bool  xAxisCVSCoord;\nuniform bool  xAxisIsLog;\nuniform bool  xAxisReversed;\nuniform float yAxisTrans;\nuniform float yAxisMin;\nuniform float yAxisMinPad;\nuniform float yAxisPointRange;\nuniform float yAxisLen;\nuniform bool  yAxisPostTranslate;\nuniform float yAxisOrdinalSlope;\nuniform float yAxisOrdinalOffset;\nuniform float yAxisPos;\nuniform bool  yAxisCVSCoord;\nuniform bool  yAxisIsLog;\nuniform bool  yAxisReversed;\nuniform bool  isBubble;\nuniform bool  bubbleSizeByArea;\nuniform float bubbleZMin;\nuniform float bubbleZMax;\nuniform float bubbleZThreshold;\nuniform float bubbleMinSize;\nuniform float bubbleMaxSize;\nuniform bool  bubbleSizeAbs;\nuniform bool  isInverted;\nfloat bubbleRadius(){\nfloat value = aVertexPosition.w;\nfloat zMax = bubbleZMax;\nfloat zMin = bubbleZMin;\nfloat radius = 0.0;\nfloat pos = 0.0;\nfloat zRange = zMax - zMin;\nif (bubbleSizeAbs){\nvalue = value - bubbleZThreshold;\nzMax = max(zMax - bubbleZThreshold, zMin - bubbleZThreshold);\nzMin = 0.0;\n}\nif (value < zMin){\nradius = bubbleZMin / 2.0 - 1.0;\n} else {\npos = zRange > 0.0 ? (value - zMin) / zRange : 0.5;\nif (bubbleSizeByArea && pos > 0.0){\npos = sqrt(pos);\n}\nradius = ceil(bubbleMinSize + pos * (bubbleMaxSize - bubbleMinSize)) / 2.0;\n}\nreturn radius * 2.0;\n}\nfloat translate(float val,\nfloat pointPlacement,\nfloat localA,\nfloat localMin,\nfloat minPixelPadding,\nfloat pointRange,\nfloat len,\nbool  cvsCoord,\nbool  isLog,\nbool  reversed\n){\nfloat sign = 1.0;\nfloat cvsOffset = 0.0;\nif (cvsCoord) {\nsign *= -1.0;\ncvsOffset = len;\n}\nif (isLog) {\nval = log(val) / LN10;\n}\nif (reversed) {\nsign *= -1.0;\ncvsOffset -= sign * len;\n}\nreturn sign * (val - localMin) * localA + cvsOffset + \n(sign * minPixelPadding);\n}\nfloat xToPixels(float value) {\nif (skipTranslation){\nreturn value;// + xAxisPos;\n}\nreturn translate(value, 0.0, xAxisTrans, xAxisMin, xAxisMinPad, xAxisPointRange, xAxisLen, xAxisCVSCoord, xAxisIsLog, xAxisReversed);// + xAxisPos;\n}\nfloat yToPixels(float value, float checkTreshold) {\nfloat v;\nif (skipTranslation){\nv = value;// + yAxisPos;\n} else {\nv = translate(value, 0.0, yAxisTrans, yAxisMin, yAxisMinPad, yAxisPointRange, yAxisLen, yAxisCVSCoord, yAxisIsLog, yAxisReversed);// + yAxisPos;\nif (v > yAxisLen) {\nv = yAxisLen;\n}\n}\nif (checkTreshold > 0.0 && hasThreshold) {\nv = min(v, translatedThreshold);\n}\nreturn v;\n}\nvoid main(void) {\nif (isBubble){\ngl_PointSize = bubbleRadius();\n} else {\ngl_PointSize = pSize;\n}\nvColor = aColor;\nif (skipTranslation && isInverted) {\ngl_Position = uPMatrix * vec4(aVertexPosition.y + yAxisPos, aVertexPosition.x + xAxisPos, 0.0, 1.0);\n} else if (isInverted) {\ngl_Position = uPMatrix * vec4(yToPixels(aVertexPosition.y, aVertexPosition.z) + yAxisPos, xToPixels(aVertexPosition.x) + xAxisPos, 0.0, 1.0);\n} else {\ngl_Position = uPMatrix * vec4(xToPixels(aVertexPosition.x) + xAxisPos, yToPixels(aVertexPosition.y, aVertexPosition.z) + yAxisPos, 0.0, 1.0);\n}\n}",
"vertex"),f=k("precision highp float;\nuniform vec4 fillColor;\nvarying highp vec2 position;\nvarying highp vec4 vColor;\nuniform sampler2D uSampler;\nuniform bool isCircle;\nuniform bool hasColor;\nvoid main(void) {\nvec4 col = fillColor;\nvec4 tcol;\nif (hasColor) {\ncol = vColor;\n}\nif (isCircle) {\ntcol = texture2D(uSampler, gl_PointCoord.st);\ncol *= tcol;\nif (tcol.r < 0.0) {\ndiscard;\n} else {\ngl_FragColor = col;\n}\n} else {\ngl_FragColor = col;\n}\n}","fragment");if(!h||!f)return a=!1,
w(),!1;a=d.createProgram();d.attachShader(a,h);d.attachShader(a,f);d.linkProgram(a);if(!d.getProgramParameter(a,d.LINK_STATUS))return v.push(d.getProgramInfoLog(a)),w(),a=!1;d.useProgram(a);d.bindAttribLocation(a,0,"aVertexPosition");A=e("uPMatrix");C=e("pSize");x=e("fillColor");n=e("isBubble");r=e("bubbleSizeAbs");c=e("bubbleSizeByArea");I=e("uSampler");l=e("skipTranslation");b=e("isCircle");H=e("isInverted");return!0}function p(b,c){d&&a&&(b=m[b]=m[b]||d.getUniformLocation(a,b),d.uniform1f(b,c))}
var m={},a,A,C,x,n,r,c,l,b,H,v=[],I;return d&&!q()?!1:{psUniform:function(){return C},pUniform:function(){return A},fillColorUniform:function(){return x},setBubbleUniforms:function(l,e,v){var g=l.options,h=Number.MAX_VALUE,k=-Number.MAX_VALUE;d&&a&&"bubble"===l.type&&(h=f(g.zMin,Math.min(h,Math.max(e,!1===g.displayNegative?g.zThreshold:-Number.MAX_VALUE))),k=f(g.zMax,Math.max(k,v)),d.uniform1i(n,1),d.uniform1i(b,1),d.uniform1i(c,"width"!==l.options.sizeBy),d.uniform1i(r,l.options.sizeByAbsoluteValue),
p("bubbleZMin",h),p("bubbleZMax",k),p("bubbleZThreshold",l.options.zThreshold),p("bubbleMinSize",l.minPxSize),p("bubbleMaxSize",l.maxPxSize))},bind:function(){d&&a&&d.useProgram(a)},program:function(){return a},create:q,setUniform:p,setPMatrix:function(b){d&&a&&d.uniformMatrix4fv(A,!1,b)},setColor:function(b){d&&a&&d.uniform4f(x,b[0]/255,b[1]/255,b[2]/255,b[3])},setPointSize:function(b){d&&a&&d.uniform1f(C,b)},setSkipTranslation:function(b){d&&a&&d.uniform1i(l,!0===b?1:0)},setTexture:function(b){d&&
a&&d.uniform1i(I,b)},setDrawAsCircle:function(c){d&&a&&d.uniform1i(b,c?1:0)},reset:function(){d&&a&&(d.uniform1i(n,0),d.uniform1i(b,0))},setInverted:function(b){d&&a&&d.uniform1i(H,b)},destroy:function(){d&&a&&(d.deleteProgram(a),a=!1)}}}});t(f,"modules/boost/wgl-vbuffer.js",[],function(){return function(e,f,d){function w(){k&&(e.deleteBuffer(k),q=k=!1);a=0;p=d||2;A=[]}var k=!1,q=!1,p=d||2,m=!1,a=0,A;return{destroy:w,bind:function(){if(!k)return!1;e.vertexAttribPointer(q,p,e.FLOAT,!1,0,0)},data:A,
build:function(a,d,n){var r;A=a||[];if(!(A&&0!==A.length||m))return w(),!1;p=n||p;k&&e.deleteBuffer(k);m||(r=new Float32Array(A));k=e.createBuffer();e.bindBuffer(e.ARRAY_BUFFER,k);e.bufferData(e.ARRAY_BUFFER,m||r,e.STATIC_DRAW);q=e.getAttribLocation(f.program(),d);e.enableVertexAttribArray(q);return!0},render:function(a,d,n){var f=m?m.length:A.length;if(!k||!f)return!1;if(!a||a>f||0>a)a=0;if(!d||d>f)d=f;e.drawArrays(e[(n||"points").toUpperCase()],a/p,(d-a)/p);return!0},allocate:function(d){a=-1;m=
new Float32Array(4*d)},push:function(d,e,f,k){m&&(m[++a]=d,m[++a]=e,m[++a]=f,m[++a]=k)}}}});t(f,"modules/boost/wgl-renderer.js",[f["modules/boost/wgl-shader.js"],f["modules/boost/wgl-vbuffer.js"],f["parts/Globals.js"],f["parts/Utilities.js"]],function(e,f,d,t){var k=t.isNumber,q=d.win.document,p=d.merge,m=d.objEach,a=d.some,w=d.Color,C=d.pick;return function(A){function n(a){if(a.isSeriesBoosting){var b=!!a.options.stacking;var d=a.xData||a.options.xData||a.processedXData;b=(b?a.data:d||a.options.data).length;
"treemap"===a.type?b*=12:"heatmap"===a.type?b*=6:R[a.type]&&(b*=2);return b}return 0}function r(){g.clear(g.COLOR_BUFFER_BIT|g.DEPTH_BUFFER_BIT)}function c(b,c){function g(b){b&&(c.colorData.push(b[0]),c.colorData.push(b[1]),c.colorData.push(b[2]),c.colorData.push(b[3]))}function l(b,a,c,d,l){g(l);y.usePreallocated?J.push(b,a,c?1:0,d||1):(K.push(b),K.push(a),K.push(c?1:0),K.push(d||1))}function e(){c.segments.length&&(c.segments[c.segments.length-1].to=K.length)}function f(){c.segments.length&&c.segments[c.segments.length-
1].from===K.length||(e(),c.segments.push({from:K.length}))}function h(b,a,c,d,e){g(e);l(b+c,a);g(e);l(b,a);g(e);l(b,a+d);g(e);l(b,a+d);g(e);l(b+c,a+d);g(e);l(b+c,a)}function n(b,a){y.useGPUTranslations||(c.skipTranslation=!0,b.x=ea.toPixels(b.x,!0),b.y=t.toPixels(b.y,!0));a?K=[b.x,b.y,0,2].concat(K):l(b.x,b.y,0,2)}var Q=b.pointArrayMap&&"low,high"===b.pointArrayMap.join(","),O=b.chart,v=b.options,k=!!v.stacking,r=v.data,H=b.xAxis.getExtremes(),m=H.min;H=H.max;var p=b.yAxis.getExtremes(),w=p.min;p=
p.max;var q=b.xData||v.xData||b.processedXData,A=b.yData||v.yData||b.processedYData,I=b.zData||v.zData||b.processedZData,t=b.yAxis,ea=b.xAxis,G=b.chart.plotWidth,x=!q||0===q.length,C=v.connectNulls,u=b.points||!1,X=!1,E=!1,D,N;r=k?b.data:q||r;q={x:Number.MAX_VALUE,y:0};var M={x:-Number.MAX_VALUE,y:0},V=0,W=!1,F=-1,P=!1,T=!1,aa="undefined"===typeof O.index,ha=!1,ia=!1,L=!1,ua=R[b.type],ja=!1,qa=!0,ra=!0,Z=v.zones||!1,U=!1,sa=v.threshold,ka=!1;if(!(v.boostData&&0<v.boostData.length)){v.gapSize&&(ka=
"value"!==v.gapUnit?v.gapSize*b.closestPointRange:v.gapSize);Z&&(a(Z,function(b){if("undefined"===typeof b.value)return U=d.Color(b.color),!0}),U||(U=b.pointAttribs&&b.pointAttribs().fill||b.color,U=d.Color(U)));O.inverted&&(G=b.chart.plotHeight);b.closestPointRangePx=Number.MAX_VALUE;f();if(u&&0<u.length)c.skipTranslation=!0,c.drawMode="triangles",u[0].node&&u[0].node.levelDynamic&&u.sort(function(b,a){if(b.node){if(b.node.levelDynamic>a.node.levelDynamic)return 1;if(b.node.levelDynamic<a.node.levelDynamic)return-1}return 0}),
u.forEach(function(a){var c=a.plotY;if("undefined"!==typeof c&&!isNaN(c)&&null!==a.y){c=a.shapeArgs;var l=O.styledMode?a.series.colorAttribs(a):l=a.series.pointAttribs(a);a=l["stroke-width"]||0;D=d.color(l.fill).rgba;D[0]/=255;D[1]/=255;D[2]/=255;"treemap"===b.type&&(a=a||1,N=d.color(l.stroke).rgba,N[0]/=255,N[1]/=255,N[2]/=255,h(c.x,c.y,c.width,c.height,N),a/=2);"heatmap"===b.type&&O.inverted&&(c.x=ea.len-c.x,c.y=t.len-c.y,c.width=-c.width,c.height=-c.height);h(c.x+a,c.y+a,c.width-2*a,c.height-2*
a,D)}});else{for(;F<r.length-1;){var B=r[++F];if(aa)break;if(x){u=B[0];var z=B[1];r[F+1]&&(T=r[F+1][0]);r[F-1]&&(P=r[F-1][0]);if(3<=B.length){var ta=B[2];B[2]>c.zMax&&(c.zMax=B[2]);B[2]<c.zMin&&(c.zMin=B[2])}}else u=B,z=A[F],r[F+1]&&(T=r[F+1]),r[F-1]&&(P=r[F-1]),I&&I.length&&(ta=I[F],I[F]>c.zMax&&(c.zMax=I[F]),I[F]<c.zMin&&(c.zMin=I[F]));if(C||null!==u&&null!==z){T&&T>=m&&T<=H&&(ha=!0);P&&P>=m&&P<=H&&(ia=!0);if(Q){x&&(z=B.slice(1,3));var ba=z[0];z=z[1]}else k&&(u=B.x,z=B.stackY,ba=z-B.y);null!==w&&
"undefined"!==typeof w&&null!==p&&"undefined"!==typeof p&&(qa=z>=w&&z<=p);u>H&&M.x<H&&(M.x=u,M.y=z);u<m&&q.x>m&&(q.x=u,q.y=z);if(null!==z||!C)if(null!==z&&(qa||ha||ia)){if((T>=m||u>=m)&&(P<=H||u<=H)&&(ja=!0),ja||ha||ia){ka&&u-P>ka&&f();Z&&(L=U.rgba,a(Z,function(b,a){a=Z[a-1];if("undefined"!==typeof b.value&&z<=b.value){if(!a||z>=a.value)L=d.color(b.color).rgba;return!0}}),L[0]/=255,L[1]/=255,L[2]/=255);if(!y.useGPUTranslations&&(c.skipTranslation=!0,u=ea.toPixels(u,!0),z=t.toPixels(z,!0),u>G&&"points"===
c.drawMode))continue;if(ua){B=ba;if(!1===ba||"undefined"===typeof ba)B=0>z?z:0;Q||k||(B=Math.max(null===sa?w:sa,w));y.useGPUTranslations||(B=t.toPixels(B,!0));l(u,B,0,0,L)}c.hasMarkers&&ja&&!1!==X&&(b.closestPointRangePx=Math.min(b.closestPointRangePx,Math.abs(u-X)));!y.useGPUTranslations&&!y.usePreallocated&&X&&1>Math.abs(u-X)&&E&&1>Math.abs(z-E)?y.debug.showSkipSummary&&++V:(v.step&&!ra&&l(u,E,0,2,L),l(u,z,0,"bubble"===b.type?ta||1:2,L),X=u,E=z,W=!0,ra=!1)}}else f()}else f()}y.debug.showSkipSummary&&
console.log("skipped points:",V);W||!1===C||"line_strip"!==b.drawMode||(q.x<Number.MAX_VALUE&&n(q,!0),M.x>-Number.MAX_VALUE&&n(M))}e()}}function l(){E=[];V.data=K=[];W=[];J&&J.destroy()}function b(b){h&&(h.setUniform("xAxisTrans",b.transA),h.setUniform("xAxisMin",b.min),h.setUniform("xAxisMinPad",b.minPixelPadding),h.setUniform("xAxisPointRange",b.pointRange),h.setUniform("xAxisLen",b.len),h.setUniform("xAxisPos",b.pos),h.setUniform("xAxisCVSCoord",!b.horiz),h.setUniform("xAxisIsLog",b.isLog),h.setUniform("xAxisReversed",
!!b.reversed))}function H(b){h&&(h.setUniform("yAxisTrans",b.transA),h.setUniform("yAxisMin",b.min),h.setUniform("yAxisMinPad",b.minPixelPadding),h.setUniform("yAxisPointRange",b.pointRange),h.setUniform("yAxisLen",b.len),h.setUniform("yAxisPos",b.pos),h.setUniform("yAxisCVSCoord",!b.horiz),h.setUniform("yAxisIsLog",b.isLog),h.setUniform("yAxisReversed",!!b.reversed))}function v(b,a){h.setUniform("hasThreshold",b);h.setUniform("translatedThreshold",a)}function I(a){if(a)G=a.chartWidth||800,x=a.chartHeight||
400;else return!1;if(!(g&&G&&x&&h))return!1;y.debug.timeRendering&&console.time("gl rendering");g.canvas.width=G;g.canvas.height=x;h.bind();g.viewport(0,0,G,x);h.setPMatrix([2/G,0,0,0,0,-(2/x),0,0,0,0,-2,0,-1,1,-1,1]);1<y.lineWidth&&!d.isMS&&g.lineWidth(y.lineWidth);J.build(V.data,"aVertexPosition",4);J.bind();h.setInverted(a.inverted);E.forEach(function(c,l){var e=c.series.options,n=e.marker;var r="undefined"!==typeof e.lineWidth?e.lineWidth:1;var m=e.threshold,p=k(m),q=c.series.yAxis.getThreshold(m);
m=C(e.marker?e.marker.enabled:null,c.series.xAxis.isRadial?!0:null,c.series.closestPointRangePx>2*((e.marker?e.marker.radius:10)||10));n=D[n&&n.symbol||c.series.symbol]||D.circle;if(!(0===c.segments.length||c.segmentslength&&c.segments[0].from===c.segments[0].to)){n.isReady&&(g.bindTexture(g.TEXTURE_2D,n.handle),h.setTexture(n.handle));a.styledMode?n=c.series.markerGroup&&c.series.markerGroup.getStyle("fill"):(n=c.series.pointAttribs&&c.series.pointAttribs().fill||c.series.color,e.colorByPoint&&(n=
c.series.chart.options.colors[l]));c.series.fillOpacity&&e.fillOpacity&&(n=(new w(n)).setOpacity(C(e.fillOpacity,1)).get());n=d.color(n).rgba;y.useAlpha||(n[3]=1);"lines"===c.drawMode&&y.useAlpha&&1>n[3]&&(n[3]/=10);"add"===e.boostBlending?(g.blendFunc(g.SRC_ALPHA,g.ONE),g.blendEquation(g.FUNC_ADD)):"mult"===e.boostBlending||"multiply"===e.boostBlending?g.blendFunc(g.DST_COLOR,g.ZERO):"darken"===e.boostBlending?(g.blendFunc(g.ONE,g.ONE),g.blendEquation(g.FUNC_MIN)):g.blendFuncSeparate(g.SRC_ALPHA,
g.ONE_MINUS_SRC_ALPHA,g.ONE,g.ONE_MINUS_SRC_ALPHA);h.reset();0<c.colorData.length&&(h.setUniform("hasColor",1),l=f(g,h),l.build(c.colorData,"aColor",4),l.bind());h.setColor(n);b(c.series.xAxis);H(c.series.yAxis);v(p,q);"points"===c.drawMode&&(e.marker&&e.marker.radius?h.setPointSize(2*e.marker.radius):h.setPointSize(1));h.setSkipTranslation(c.skipTranslation);"bubble"===c.series.type&&h.setBubbleUniforms(c.series,c.zMin,c.zMax);h.setDrawAsCircle(aa[c.series.type]||!1);if(0<r||"line_strip"!==c.drawMode)for(r=
0;r<c.segments.length;r++)J.render(c.segments[r].from,c.segments[r].to,c.drawMode);if(c.hasMarkers&&m)for(e.marker&&e.marker.radius?h.setPointSize(2*e.marker.radius):h.setPointSize(10),h.setDrawAsCircle(!0),r=0;r<c.segments.length;r++)J.render(c.segments[r].from,c.segments[r].to,"POINTS")}});y.debug.timeRendering&&console.timeEnd("gl rendering");A&&A();l()}function t(b){r();if(b.renderer.forExport)return I(b);M?I(b):setTimeout(function(){t(b)},1)}var h=!1,J=!1,g=!1,G=0,x=0,K=!1,W=!1,V={},M=!1,E=[],
D={},R={column:!0,columnrange:!0,bar:!0,area:!0,arearange:!0},aa={scatter:!0,bubble:!0},y={pointSize:1,lineWidth:1,fillColor:"#AA00AA",useAlpha:!0,usePreallocated:!1,useGPUTranslations:!1,debug:{timeRendering:!1,timeSeriesProcessing:!1,timeSetup:!1,timeBufferCopy:!1,timeKDTree:!1,showSkipSummary:!1}};return V={allocateBufferForSingleSeries:function(b){var c=0;y.usePreallocated&&(b.isSeriesBoosting&&(c=n(b)),J.allocate(c))},pushSeries:function(b){0<E.length&&E[E.length-1].hasMarkers&&(E[E.length-1].markerTo=
W.length);y.debug.timeSeriesProcessing&&console.time("building "+b.type+" series");E.push({segments:[],markerFrom:W.length,colorData:[],series:b,zMin:Number.MAX_VALUE,zMax:-Number.MAX_VALUE,hasMarkers:b.options.marker?!1!==b.options.marker.enabled:!1,showMarkers:!0,drawMode:{area:"lines",arearange:"lines",areaspline:"line_strip",column:"lines",columnrange:"lines",bar:"lines",line:"line_strip",scatter:"points",heatmap:"triangles",treemap:"triangles",bubble:"points"}[b.type]||"line_strip"});c(b,E[E.length-
1]);y.debug.timeSeriesProcessing&&console.timeEnd("building "+b.type+" series")},setSize:function(b,c){G===b&&x===c||!h||(G=b,x=c,h.bind(),h.setPMatrix([2/G,0,0,0,0,-(2/x),0,0,0,0,-2,0,-1,1,-1,1]))},inited:function(){return M},setThreshold:v,init:function(b,c){function a(b,c){var a={isReady:!1,texture:q.createElement("canvas"),handle:g.createTexture()},d=a.texture.getContext("2d");D[b]=a;a.texture.width=512;a.texture.height=512;d.mozImageSmoothingEnabled=!1;d.webkitImageSmoothingEnabled=!1;d.msImageSmoothingEnabled=
!1;d.imageSmoothingEnabled=!1;d.strokeStyle="rgba(255, 255, 255, 0)";d.fillStyle="#FFF";c(d);try{g.activeTexture(g.TEXTURE0),g.bindTexture(g.TEXTURE_2D,a.handle),g.texImage2D(g.TEXTURE_2D,0,g.RGBA,g.RGBA,g.UNSIGNED_BYTE,a.texture),g.texParameteri(g.TEXTURE_2D,g.TEXTURE_WRAP_S,g.CLAMP_TO_EDGE),g.texParameteri(g.TEXTURE_2D,g.TEXTURE_WRAP_T,g.CLAMP_TO_EDGE),g.texParameteri(g.TEXTURE_2D,g.TEXTURE_MAG_FILTER,g.LINEAR),g.texParameteri(g.TEXTURE_2D,g.TEXTURE_MIN_FILTER,g.LINEAR),g.bindTexture(g.TEXTURE_2D,
null),a.isReady=!0}catch(Y){}}var d=0,n=["webgl","experimental-webgl","moz-webgl","webkit-3d"];M=!1;if(!b)return!1;for(y.debug.timeSetup&&console.time("gl setup");d<n.length&&!(g=b.getContext(n[d],{}));d++);if(g)c||l();else return!1;g.enable(g.BLEND);g.blendFunc(g.SRC_ALPHA,g.ONE_MINUS_SRC_ALPHA);g.disable(g.DEPTH_TEST);g.depthFunc(g.LESS);h=e(g);if(!h)return!1;J=f(g,h);a("circle",function(b){b.beginPath();b.arc(256,256,256,0,2*Math.PI);b.stroke();b.fill()});a("square",function(b){b.fillRect(0,0,
512,512)});a("diamond",function(b){b.beginPath();b.moveTo(256,0);b.lineTo(512,256);b.lineTo(256,512);b.lineTo(0,256);b.lineTo(256,0);b.fill()});a("triangle",function(b){b.beginPath();b.moveTo(0,512);b.lineTo(256,0);b.lineTo(512,512);b.lineTo(0,512);b.fill()});a("triangle-down",function(b){b.beginPath();b.moveTo(0,0);b.lineTo(256,512);b.lineTo(512,0);b.lineTo(0,0);b.fill()});M=!0;y.debug.timeSetup&&console.timeEnd("gl setup");return!0},render:t,settings:y,valid:function(){return!1!==g},clear:r,flush:l,
setXAxis:b,setYAxis:H,data:K,gl:function(){return g},allocateBuffer:function(b){var a=0;y.usePreallocated&&(b.series.forEach(function(b){b.isSeriesBoosting&&(a+=n(b))}),J.allocate(a))},destroy:function(){l();J.destroy();h.destroy();g&&(m(D,function(b){D[b].handle&&g.deleteTexture(D[b].handle)}),g.canvas.width=1,g.canvas.height=1)},setOptions:function(b){p(!0,y,b)}}}});t(f,"modules/boost/boost-attach.js",[f["parts/Globals.js"],f["modules/boost/wgl-renderer.js"]],function(e,f){var d=e.win.document,
w=d.createElement("canvas");return function(k,q){var p=k.chartWidth,m=k.chartHeight,a=k,A=k.seriesGroup||q.group,t=d.implementation.hasFeature("www.http://w3.org/TR/SVG11/feature#Extensibility","1.1");a=k.isChartSeriesBoosting()?k:q;t=!1;a.renderTarget||(a.canvas=w,k.renderer.forExport||!t?(a.renderTarget=k.renderer.image("",0,0,p,m).addClass("highcharts-boost-canvas").add(A),a.boostClear=function(){a.renderTarget.attr({href:""})},a.boostCopy=function(){a.boostResizeTarget();a.renderTarget.attr({href:a.canvas.toDataURL("image/png")})}):
(a.renderTargetFo=k.renderer.createElement("foreignObject").add(A),a.renderTarget=d.createElement("canvas"),a.renderTargetCtx=a.renderTarget.getContext("2d"),a.renderTargetFo.element.appendChild(a.renderTarget),a.boostClear=function(){a.renderTarget.width=a.canvas.width;a.renderTarget.height=a.canvas.height},a.boostCopy=function(){a.renderTarget.width=a.canvas.width;a.renderTarget.height=a.canvas.height;a.renderTargetCtx.drawImage(a.canvas,0,0)}),a.boostResizeTarget=function(){p=k.chartWidth;m=k.chartHeight;
(a.renderTargetFo||a.renderTarget).attr({x:0,y:0,width:p,height:m}).css({pointerEvents:"none",mixedBlendMode:"normal",opacity:1});a instanceof e.Chart&&a.markerGroup.translate(k.plotLeft,k.plotTop)},a.boostClipRect=k.renderer.clipRect(),(a.renderTargetFo||a.renderTarget).clip(a.boostClipRect),a instanceof e.Chart&&(a.markerGroup=a.renderer.g().add(A),a.markerGroup.translate(q.xAxis.pos,q.yAxis.pos)));a.canvas.width=p;a.canvas.height=m;a.boostClipRect.attr(k.getBoostClipRect(a));a.boostResizeTarget();
a.boostClear();a.ogl||(a.ogl=f(function(){a.ogl.settings.debug.timeBufferCopy&&console.time("buffer copy");a.boostCopy();a.ogl.settings.debug.timeBufferCopy&&console.timeEnd("buffer copy")}),a.ogl.init(a.canvas)||e.error("[highcharts boost] - unable to init WebGL renderer"),a.ogl.setOptions(k.options.boost||{}),a instanceof e.Chart&&a.ogl.allocateBuffer(k));a.ogl.setSize(p,m);return a.ogl}});t(f,"modules/boost/boost-utils.js",[f["parts/Globals.js"],f["modules/boost/boostable-map.js"],f["modules/boost/boost-attach.js"]],
function(e,f,d){function w(){var a=Array.prototype.slice.call(arguments),d=-Number.MAX_VALUE;a.forEach(function(a){if("undefined"!==typeof a&&null!==a&&"undefined"!==typeof a.length&&0<a.length)return d=a.length,!0});return d}function k(a,d,c){a&&d.renderTarget&&d.canvas&&!(c||d.chart).isChartSeriesBoosting()&&a.render(c||d.chart)}function q(a,d){a&&d.renderTarget&&d.canvas&&!d.chart.isChartSeriesBoosting()&&a.allocateBufferForSingleSeries(d)}function p(d,e,c,l,b,f){b=b||0;l=l||3E3;for(var n=b+l,
k=!0;k&&b<n&&b<d.length;)k=e(d[b],b),++b;k&&(b<d.length?f?p(d,e,c,l,b,f):a.requestAnimationFrame?a.requestAnimationFrame(function(){p(d,e,c,l,b)}):setTimeout(function(){p(d,e,c,l,b)}):c&&c())}function m(){var d=0,e,c=["webgl","experimental-webgl","moz-webgl","webkit-3d"],l=!1;if("undefined"!==typeof a.WebGLRenderingContext)for(e=t.createElement("canvas");d<c.length;d++)try{if(l=e.getContext(c[d]),"undefined"!==typeof l&&null!==l)return!0}catch(b){}return!1}var a=e.win,t=a.document,C=e.pick,x={patientMax:w,
boostEnabled:function(a){return C(a&&a.options&&a.options.boost&&a.options.boost.enabled,!0)},shouldForceChartSeriesBoosting:function(a){var d=0,c=0,e=C(a.options.boost&&a.options.boost.allowForce,!0);if("undefined"!==typeof a.boostForceChartBoost)return a.boostForceChartBoost;if(1<a.series.length)for(var b=0;b<a.series.length;b++){var k=a.series[b];0!==k.options.boostThreshold&&!1!==k.visible&&"heatmap"!==k.type&&(f[k.type]&&++c,w(k.processedXData,k.options.data,k.points)>=(k.options.boostThreshold||
Number.MAX_VALUE)&&++d)}a.boostForceChartBoost=e&&(c===a.series.length&&0<d||5<d);return a.boostForceChartBoost},renderIfNotSeriesBoosting:k,allocateIfNotSeriesBoosting:q,eachAsync:p,hasWebGLSupport:m,pointDrawHandler:function(a){var e=!0;this.chart.options&&this.chart.options.boost&&(e="undefined"===typeof this.chart.options.boost.enabled?!0:this.chart.options.boost.enabled);if(!e||!this.isSeriesBoosting)return a.call(this);this.chart.isBoosting=!0;if(a=d(this.chart,this))q(a,this),a.pushSeries(this);
k(a,this)}};e.hasWebGLSupport=m;return x});t(f,"modules/boost/boost-init.js",[f["parts/Globals.js"],f["modules/boost/boost-utils.js"],f["modules/boost/boost-attach.js"]],function(e,f,d){var t=e.addEvent,k=e.fireEvent,q=e.extend,p=e.Series,m=e.seriesTypes,a=e.wrap,w=function(){},C=f.eachAsync,x=f.pointDrawHandler,n=f.allocateIfNotSeriesBoosting,r=f.renderIfNotSeriesBoosting,c=f.shouldForceChartSeriesBoosting,l;return function(){e.extend(p.prototype,{renderCanvas:function(){function b(b,a){var c=!1,
d="undefined"===typeof h.index,e=!0;if(!d){if(na){var l=b[0];var f=b[1]}else l=b,f=q[a];O?(na&&(f=b.slice(1,3)),c=f[0],f=f[1]):la&&(l=b.x,f=b.stackY,c=f-b.y);va||(e=f>=G&&f<=E);if(null!==f&&l>=A&&l<=x&&e)if(b=m.toPixels(l,!0),aa){if(void 0===S||b===R){O||(c=f);if(void 0===Y||f>da)da=f,Y=a;if(void 0===S||c<ca)ca=c,S=a}b!==R&&(void 0!==S&&(f=g.toPixels(da,!0),Q=g.toPixels(ca,!0),fa(b,f,Y),Q!==f&&fa(b,Q,S)),S=Y=void 0,R=b)}else f=Math.ceil(g.toPixels(f,!0)),fa(b,f,a)}return!d}function a(){k(c,"renderedCanvas");
delete c.buildKDTree;c.buildKDTree();pa.debug.timeKDTree&&console.timeEnd("kd tree building")}var c=this,e=c.options||{},f=!1,h=c.chart,m=this.xAxis,g=this.yAxis,p=e.xData||c.processedXData,q=e.yData||c.processedYData,t=e.data;f=m.getExtremes();var A=f.min,x=f.max;f=g.getExtremes();var G=f.min,E=f.max,D={},R,aa=!!c.sampling,y=!1!==e.enableMouseTracking,Q=g.getThreshold(e.threshold),O=c.pointArrayMap&&"low,high"===c.pointArrayMap.join(","),la=!!e.stacking,ma=c.cropStart||0,va=c.requireSorting,na=!p,
ca,da,S,Y,wa="x"===e.findNearestPointBy,oa=this.xData||this.options.xData||this.processedXData||!1,fa=function(b,a,c){b=Math.ceil(b);l=wa?b:b+","+a;y&&!D[l]&&(D[l]=!0,h.inverted&&(b=m.len-b,a=g.len-a),xa.push({x:oa?oa[ma+c]:!1,clientX:b,plotX:b,plotY:a,i:ma+c}))};f=d(h,c);h.isBoosting=!0;var pa=f.settings;if(this.visible){if(this.points||this.graph)this.animate=null,this.destroyGraphics();h.isChartSeriesBoosting()?(this.markerGroup&&this.markerGroup!==h.markerGroup&&this.markerGroup.destroy(),this.markerGroup=
h.markerGroup,this.renderTarget&&(this.renderTarget=this.renderTarget.destroy())):(this.markerGroup===h.markerGroup&&(this.markerGroup=void 0),this.markerGroup=c.plotGroup("markerGroup","markers",!0,1,h.seriesGroup));var xa=this.points=[];c.buildKDTree=w;f&&(n(f,this),f.pushSeries(c),r(f,this,h));h.renderer.forExport||(pa.debug.timeKDTree&&console.time("kd tree building"),C(la?c.data:p||t,b,a))}}});["heatmap","treemap"].forEach(function(b){m[b]&&a(m[b].prototype,"drawPoints",x)});m.bubble&&(delete m.bubble.prototype.buildKDTree,
a(m.bubble.prototype,"markerAttribs",function(b){return this.isSeriesBoosting?!1:b.apply(this,[].slice.call(arguments,1))}));m.scatter.prototype.fill=!0;q(m.area.prototype,{fill:!0,fillOpacity:!0,sampling:!0});q(m.column.prototype,{fill:!0,sampling:!0});e.Chart.prototype.callbacks.push(function(b){t(b,"predraw",function(){b.boostForceChartBoost=void 0;b.boostForceChartBoost=c(b);b.isBoosting=!1;!b.isChartSeriesBoosting()&&b.didBoost&&(b.didBoost=!1);b.boostClear&&b.boostClear();b.canvas&&b.ogl&&b.isChartSeriesBoosting()&&
(b.didBoost=!0,b.ogl.allocateBuffer(b));b.markerGroup&&b.xAxis&&0<b.xAxis.length&&b.yAxis&&0<b.yAxis.length&&b.markerGroup.translate(b.xAxis[0].pos,b.yAxis[0].pos)});t(b,"render",function(){b.ogl&&b.isChartSeriesBoosting()&&b.ogl.render(b)})})}});t(f,"modules/boost/boost-overrides.js",[f["parts/Globals.js"],f["parts/Utilities.js"],f["modules/boost/boost-utils.js"],f["modules/boost/boostables.js"],f["modules/boost/boostable-map.js"]],function(e,f,d,t,k){var q=f.isNumber,p=d.boostEnabled,m=d.shouldForceChartSeriesBoosting;
f=e.Chart;var a=e.Series;d=e.Point;var w=e.seriesTypes,C=e.addEvent,x=e.pick,n=e.wrap,r=e.getOptions().plotOptions;f.prototype.isChartSeriesBoosting=function(){return x(this.options.boost&&this.options.boost.seriesThreshold,50)<=this.series.length||m(this)};f.prototype.getBoostClipRect=function(a){var c={x:this.plotLeft,y:this.plotTop,width:this.plotWidth,height:this.plotHeight};a===this&&this.yAxis.forEach(function(b){c.y=Math.min(b.pos,c.y);c.height=Math.max(b.pos-this.plotTop+b.len,c.height)},
this);return c};a.prototype.getPoint=function(a){var c=a,b=this.xData||this.options.xData||this.processedXData||!1;!a||a instanceof this.pointClass||(c=(new this.pointClass).init(this,this.options.data[a.i],b?b[a.i]:void 0),c.category=x(this.xAxis.categories?this.xAxis.categories[c.x]:c.x,c.x),c.dist=a.dist,c.distX=a.distX,c.plotX=a.plotX,c.plotY=a.plotY,c.index=a.i);return c};n(a.prototype,"searchPoint",function(a){return this.getPoint(a.apply(this,[].slice.call(arguments,1)))});n(d.prototype,"haloPath",
function(a){var c=this.series,b=this.plotX,d=this.plotY,e=c.chart.inverted;c.isSeriesBoosting&&e&&(this.plotX=c.yAxis.len-d,this.plotY=c.xAxis.len-b);var f=a.apply(this,Array.prototype.slice.call(arguments,1));c.isSeriesBoosting&&e&&(this.plotX=b,this.plotY=d);return f});n(a.prototype,"markerAttribs",function(a,d){var b=d.plotX,c=d.plotY,e=this.chart.inverted;this.isSeriesBoosting&&e&&(d.plotX=this.yAxis.len-c,d.plotY=this.xAxis.len-b);var f=a.apply(this,Array.prototype.slice.call(arguments,1));this.isSeriesBoosting&&
e&&(d.plotX=b,d.plotY=c);return f});C(a,"destroy",function(){var a=this,d=a.chart;d.markerGroup===a.markerGroup&&(a.markerGroup=null);d.hoverPoints&&(d.hoverPoints=d.hoverPoints.filter(function(b){return b.series===a}));d.hoverPoint&&d.hoverPoint.series===a&&(d.hoverPoint=null)});n(a.prototype,"getExtremes",function(a){if(!this.isSeriesBoosting||!this.hasExtremes||!this.hasExtremes())return a.apply(this,Array.prototype.slice.call(arguments,1))});["translate","generatePoints","drawTracker","drawPoints",
"render"].forEach(function(c){function d(b){var a=this.options.stacking&&("translate"===c||"generatePoints"===c);if(!this.isSeriesBoosting||a||!p(this.chart)||"heatmap"===this.type||"treemap"===this.type||!k[this.type]||0===this.options.boostThreshold)b.call(this);else if(this[c+"Canvas"])this[c+"Canvas"]()}n(a.prototype,c,d);"translate"===c&&"column bar arearange columnrange heatmap treemap".split(" ").forEach(function(b){w[b]&&n(w[b].prototype,c,d)})});n(a.prototype,"processData",function(a){function c(a){return b.chart.isChartSeriesBoosting()||
(a?a.length:0)>=(b.options.boostThreshold||Number.MAX_VALUE)}var b=this,d=this.options.data;p(this.chart)&&k[this.type]?(c(d)&&"heatmap"!==this.type&&"treemap"!==this.type&&!this.options.stacking&&this.hasExtremes&&this.hasExtremes(!0)||(a.apply(this,Array.prototype.slice.call(arguments,1)),d=this.processedXData),(this.isSeriesBoosting=c(d))?this.enterBoost():this.exitBoost&&this.exitBoost()):a.apply(this,Array.prototype.slice.call(arguments,1))});C(a,"hide",function(){this.canvas&&this.renderTarget&&
(this.ogl&&this.ogl.clear(),this.boostClear())});a.prototype.enterBoost=function(){this.alteredByBoost=[];["allowDG","directTouch","stickyTracking"].forEach(function(a){this.alteredByBoost.push({prop:a,val:this[a],own:Object.hasOwnProperty.call(this,a)})},this);this.directTouch=this.allowDG=!1;this.stickyTracking=!0;this.animate=null;this.labelBySeries&&(this.labelBySeries=this.labelBySeries.destroy())};a.prototype.exitBoost=function(){(this.alteredByBoost||[]).forEach(function(a){a.own?this[a.prop]=
a.val:delete this[a.prop]},this);this.boostClear&&this.boostClear()};a.prototype.hasExtremes=function(a){var c=this.options,b=this.xAxis&&this.xAxis.options,d=this.yAxis&&this.yAxis.options,e=this.colorAxis&&this.colorAxis.options;return c.data.length>(c.boostThreshold||Number.MAX_VALUE)&&q(d.min)&&q(d.max)&&(!a||q(b.min)&&q(b.max))&&(!e||q(e.min)&&q(e.max))};a.prototype.destroyGraphics=function(){var a=this,d=this.points,b,e;if(d)for(e=0;e<d.length;e+=1)(b=d[e])&&b.destroyElements&&b.destroyElements();
["graph","area","tracker"].forEach(function(b){a[b]&&(a[b]=a[b].destroy())})};t.forEach(function(a){r[a]&&(r[a].boostThreshold=5E3,r[a].boostData=[],w[a].prototype.fillOpacity=!0)})});t(f,"modules/boost/named-colors.js",[f["parts/Globals.js"]],function(e){var f={aliceblue:"#f0f8ff",antiquewhite:"#faebd7",aqua:"#00ffff",aquamarine:"#7fffd4",azure:"#f0ffff",beige:"#f5f5dc",bisque:"#ffe4c4",black:"#000000",blanchedalmond:"#ffebcd",blue:"#0000ff",blueviolet:"#8a2be2",brown:"#a52a2a",burlywood:"#deb887",
cadetblue:"#5f9ea0",chartreuse:"#7fff00",chocolate:"#d2691e",coral:"#ff7f50",cornflowerblue:"#6495ed",cornsilk:"#fff8dc",crimson:"#dc143c",cyan:"#00ffff",darkblue:"#00008b",darkcyan:"#008b8b",darkgoldenrod:"#b8860b",darkgray:"#a9a9a9",darkgreen:"#006400",darkkhaki:"#bdb76b",darkmagenta:"#8b008b",darkolivegreen:"#556b2f",darkorange:"#ff8c00",darkorchid:"#9932cc",darkred:"#8b0000",darksalmon:"#e9967a",darkseagreen:"#8fbc8f",darkslateblue:"#483d8b",darkslategray:"#2f4f4f",darkturquoise:"#00ced1",darkviolet:"#9400d3",
deeppink:"#ff1493",deepskyblue:"#00bfff",dimgray:"#696969",dodgerblue:"#1e90ff",feldspar:"#d19275",firebrick:"#b22222",floralwhite:"#fffaf0",forestgreen:"#228b22",fuchsia:"#ff00ff",gainsboro:"#dcdcdc",ghostwhite:"#f8f8ff",gold:"#ffd700",goldenrod:"#daa520",gray:"#808080",green:"#008000",greenyellow:"#adff2f",honeydew:"#f0fff0",hotpink:"#ff69b4",indianred:"#cd5c5c",indigo:"#4b0082",ivory:"#fffff0",khaki:"#f0e68c",lavender:"#e6e6fa",lavenderblush:"#fff0f5",lawngreen:"#7cfc00",lemonchiffon:"#fffacd",
lightblue:"#add8e6",lightcoral:"#f08080",lightcyan:"#e0ffff",lightgoldenrodyellow:"#fafad2",lightgrey:"#d3d3d3",lightgreen:"#90ee90",lightpink:"#ffb6c1",lightsalmon:"#ffa07a",lightseagreen:"#20b2aa",lightskyblue:"#87cefa",lightslateblue:"#8470ff",lightslategray:"#778899",lightsteelblue:"#b0c4de",lightyellow:"#ffffe0",lime:"#00ff00",limegreen:"#32cd32",linen:"#faf0e6",magenta:"#ff00ff",maroon:"#800000",mediumaquamarine:"#66cdaa",mediumblue:"#0000cd",mediumorchid:"#ba55d3",mediumpurple:"#9370d8",mediumseagreen:"#3cb371",
mediumslateblue:"#7b68ee",mediumspringgreen:"#00fa9a",mediumturquoise:"#48d1cc",mediumvioletred:"#c71585",midnightblue:"#191970",mintcream:"#f5fffa",mistyrose:"#ffe4e1",moccasin:"#ffe4b5",navajowhite:"#ffdead",navy:"#000080",oldlace:"#fdf5e6",olive:"#808000",olivedrab:"#6b8e23",orange:"#ffa500",orangered:"#ff4500",orchid:"#da70d6",palegoldenrod:"#eee8aa",palegreen:"#98fb98",paleturquoise:"#afeeee",palevioletred:"#d87093",papayawhip:"#ffefd5",peachpuff:"#ffdab9",peru:"#cd853f",pink:"#ffc0cb",plum:"#dda0dd",
powderblue:"#b0e0e6",purple:"#800080",red:"#ff0000",rosybrown:"#bc8f8f",royalblue:"#4169e1",saddlebrown:"#8b4513",salmon:"#fa8072",sandybrown:"#f4a460",seagreen:"#2e8b57",seashell:"#fff5ee",sienna:"#a0522d",silver:"#c0c0c0",skyblue:"#87ceeb",slateblue:"#6a5acd",slategray:"#708090",snow:"#fffafa",springgreen:"#00ff7f",steelblue:"#4682b4",tan:"#d2b48c",teal:"#008080",thistle:"#d8bfd8",tomato:"#ff6347",turquoise:"#40e0d0",violet:"#ee82ee",violetred:"#d02090",wheat:"#f5deb3",white:"#ffffff",whitesmoke:"#f5f5f5",
yellow:"#ffff00",yellowgreen:"#9acd32"};return e.Color.prototype.names=f});t(f,"modules/boost/boost.js",[f["parts/Globals.js"],f["modules/boost/boost-utils.js"],f["modules/boost/boost-init.js"]],function(e,f,d){f=f.hasWebGLSupport;f()?d():"undefined"!==typeof e.initCanvasBoost?e.initCanvasBoost():e.error(26)});t(f,"masters/modules/boost.src.js",[],function(){})});
//# sourceMappingURL=boost.js.map