function Camera(video) {
  this.video = video;
  this.canvas = document.createElement('canvas');
  this.ctx = this.canvas.getContext('2d');
  this.imgdata = null;
}
Camera.prototype.init = function(constraints, success, error) {
  if (navigator.mediaDevices.getUserMedia) {
      //最新的标准API
      navigator.mediaDevices.getUserMedia(constraints).then(success).catch(error);
  } else if (navigator.webkitGetUserMedia) {
      //webkit核心浏览器
      navigator.webkitGetUserMedia(constraints,success, error)
  } else if (navigator.mozGetUserMedia) {
      //firfox浏览器
      navigator.mozGetUserMedia(constraints, success, error);
  } else if (navigator.getUserMedia) {
      //旧版API
      navigator.getUserMedia(constraints, success, error);
  }
  this.canvas.width = this.video.width;
  this.canvas.height = this.video.height;
}

Camera.prototype.open = function (){
    var $this = this;
    var config = {
        video: {
          width:  512,
          height: 384
        }
    };
  // navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
  if (navigator.mediaDevices.getUserMedia || navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia) {
      this.init(config, getStream, noStream);
  }
  function getStream(stream) {
      this.video.srcObject = stream;
      this.video.onerror = function() {
        stream.stop();
      };
      stream.onended = noStream;
      var that = this.video;
      this.video.onloadedmetadata = function() {
        if(stream.active){
            that.play();
        }else{
            $this.init(config, getStream, noStream);
        }
      }
  }
  function noStream(err) {
    console.log(err);
    // $this.init(config, getStream, noStream);
  }
  return this;
}

Camera.prototype.photograph = function() {
  this.ctx.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);
  return this.canvas.toDataURL('image/png');
}