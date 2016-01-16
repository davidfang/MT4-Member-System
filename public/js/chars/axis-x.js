
function xAxis(options){
  this.options = options;
}
xAxis.prototype = {
    initialize: function (painter) { painter.options = this.options; },
    start: function () {
        var ctx = this.ctx;
        ctx.save();
        ctx.fillStyle = this.options.color;
        ctx.font = this.options.font;
        if (this.options.textBaseline) ctx.textBaseline = this.options.textBaseline;
        ctx.translate(this.options.region.x, this.options.region.y);
    },
    getY: function () { return 0; },
    getX: function (i) {
        if (i == 0) return -25;
        var w = this.ctx.measureText(this.data[i]).width;
        if (i == this.data.length - 1) return this.options.region.width - w+20;
        return ((this.options.region.width * i / (this.data.length - 1)) - w / 2);
    },
    paintItem: function (i, x, y) {
        this.ctx.fillText(this.data[i], x+20, y);
    },
    end: function () {
        this.ctx.restore();
    }
};