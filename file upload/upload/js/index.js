;(function(doc){
  
  var oOpenModBtn = doc.getElementsByClassName('js-open-mod-btn')[0],
      oCloseModBtn = doc.getElementsByClassName('js-close-mod-btn')[0],
      oUploadMod = doc.getElementsByClassName('js-upload-modal')[0],
      oUploadArea = doc.getElementsByClassName('js-upload-area')[0],
      oProgressBar = oUploadArea.getElementsByClassName('js-progress-bar')[0],
      oPercent = oUploadArea.getElementsByClassName('js-percent')[0],
      oVideoInput = doc.getElementById('videoFile'),
      maxSize = 3221225472;

  var init = function(){
    bindEvent();
  }

  function bindEvent(){
  	oOpenModBtn.addEventListener('click', showModal.bind(this, true), false);
  	oCloseModBtn.addEventListener('click', showModal.bind(this, false), false);
  	oVideoInput.addEventListener('change', uploadFile, false);
  }

  function showModal(show){
  	if(show){
  		oUploadMod.className += ' show';
  	}else{
  		oUploadMod.className = 'upload-modal-wrap js-upload-modal';
  	}
  }

  function uploadFile(){
  	var oFile = this.files[0],
        fileLen = oFile.length,
        fileName = oFile.name,
        fileSize = oFile.size;

    if(oFile > 1){
    	throw new Error('一次只可上传一个视频');
    }

    if(!/\.(mp4)$/.test(fileName)){
    	throw new Error('您上传的『' + fileName + '』文件不是MP4视频格式');
    }

    if(fileSize > maxSize){
    	throw new Error('您上传的『' + fileName + '』视频大小超出可上传文件的最大的大小');
    }
    doUpload(oFile);
  }

  function doUpload(file){
    var fd = new FormData();
    fd.append('file', file);

    var o = window.XMLHttpRequest ?
            new window.XMLHttpRequest() :
            new ActiveXObject('Microsoft.XMLHTTP');

    if(!o){
      throw new Error('您的浏览器不支持AJAX异步发起HTTP请求');
    }

    o.open('post', 'server/upload.php');
    
    oUploadArea.className = 'upload-area js-upload-area uploading';
    o.upload.onprogress = function(e){
      var e = e || window.event;
      oProgressBar.style.width = e.loaded / e.total * 100 + '%';
      oPercent.innerHTML = (e.loaded / e.total * 100).toFixed(1) + '%';
    }

    o.upload.onload = function(){
    	oUploadArea.className = 'upload-area js-upload-area uploading finished';
    	oPercent.innerHTML = '0.0%';
    }
    o.send(fd);
  }

  init();
})(document);
















