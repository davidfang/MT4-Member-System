var dashSize = 2;

function Painter(canvasId, paintImplement, data) {
    this.canvas = document.getElementById(canvasId);
    if (!this.canvas.getContext) return;
    this.ctx = this.canvas.getContext('2d');
    this.data = data;
    this.paintImplement = paintImplement;
    this.width = this.canvas.width;
    this.height = this.canvas.height;
}
Painter.prototype = {
    paint: function () {
        var pctx = this.paintImplement;
        var data = this.data;
        var ctx = this.ctx;
       
        if (typeof pctx.initialize == 'function') pctx.initialize(this);
        if (pctx.start) pctx.start.call(this);

        if (typeof pctx.paintItems == 'function') {
            pctx.paintItems.call(this);
        }
        else {
            var dataLength = ((typeof pctx.getDataLength == 'function') ? pctx.getDataLength.call(this) : this.data.length);
            for (var i = 0; i < dataLength; i++) {
                var x = pctx.getX ? pctx.getX.call(this, i) : undefined;
                var y = pctx.getY ? pctx.getY.call(this, i) : undefined;
                pctx.paintItem.call(this, i, x, y);
            }
        }
        if (pctx.end) pctx.end.call(this);        
      
    },
    drawHLine: function (color, x0, y0, w, lineWidth, lineStyle) {
        var ctx = this.ctx;
        ctx.strokeStyle = color;
        if (y0 * 10 % 10 == 0) y0 += .5;
        if (lineStyle && lineStyle == 'dashed') {
            var width = 0;
            do {
                this.drawHLine(color, width, y0, dashSize, 1, 'solid');
                width += dashSize * 3;
            } while (width < w);
        }
        else {
            ctx.beginPath();
            ctx.moveTo(x0, y0);
            ctx.lineTo(x0 + w, y0);
            ctx.stroke();
        }
    },
    drawVLine: function (color, x0, y0, h, lineWidth, lineStyle) {
        var ctx = this.ctx;
        ctx.strokeStyle = color;
        if (x0 * 10 % 10 == 0) x0 += .5;
        if (lineStyle && lineStyle == 'dashed') {
            var height = 0;
            do {
                this.drawVLine(color, x0, height, dashSize, 1);
                height += dashSize * 3;//间隔
            } while (height < h);
        }
        else {
            ctx.beginPath();
            ctx.moveTo(x0, y0);
            ctx.lineTo(x0, y0 + h);
            ctx.stroke();

        }
    },
    setData: function (data) {
        this.data = data;
    },
    setPainterImplement: function (implement) {
        this.paintImplement = implement;
    }
};
