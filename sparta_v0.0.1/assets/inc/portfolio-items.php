<?php 
	$portfolioImageDir = '../img/portfolio/';
	// checking the directory path is correct
	// $scannedDir = scandir($portfolioImageDir);
	// print_r($scannedDir);
	
	if (is_dir($portfolioImageDir)){
	  if ($dh = opendir($portfolioImageDir)){
	    while (($file = readdir($dh)) !== false){
		      $images[] = $file;
	    }
	    closedir($dh);
	    // print_r($images);
	    // Do a grep on all the files in the directory and make sure that they are png,svg,gif or jpg
	    $findImages = preg_grep("/^.*(\.[Jj][Pp][Gg]|\.[Ss][Vv][Gg]|\.[Gg][Ii][Ff]|\.[Jj][Pp][Ee][Gg]|\.[Pp][Nn][Gg])$/", $images);

	    foreach ($findImages as $key => $value) {
		    $imgName = explode(".", $value);
		    echo "<div class=\"masonry-item col-xs-6 col-sm-6 col-md-4\">";
		    echo "<div class=\"waypoint portfolio-bloc \">";
		    echo "<figure>";
	    	echo "<a href=\"#{$imgName[0]}\" data-toggle=\"modal\" data-target=\"#{$imgName[0]}\"><img src=\"../img/portfolio/$value\" alt=\"{$imgName[0]}\" class=\"img-responsive msny\" /></a>";
		    echo "</figure>";
	    	echo "</div>";
	    	echo "</div>";
	    }
	  }
	} else {
		echo "Portfolio Folder Is Missing!";
	}

?> 

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>