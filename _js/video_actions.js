var vid = document.getElementById("hello_world");
var pauseButton = document.querySelector(".title .video-btn");

pauseButton.addEventListener("click", function() {
  if (vid.paused) {
    vid.play();
    pauseButton.innerHTML = '<i class="fa fa-play fa-inverse"></i>';
  } else {
    vid.pause();
    pauseButton.innerHTML = '<i class="fa fa-pause fa-inverse"></i>';
  }
})

