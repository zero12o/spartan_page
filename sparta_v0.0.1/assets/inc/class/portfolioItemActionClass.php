<?php
	require_once ('db/database.php');
	class portfolioItemAction {
		// 
		public $status = array('status' => array('success' => 0,'failed' => null));
		
		protected function connect() {
			return new MysqliDb ("localhost", "root", "cloud777", "mobius_site_data");
		}
		public function imageUpload($imageUpload) {
			$temp_name = $imageUpload['portfolioImageDir']['tmp_name'];
			$image_name = basename($imageUpload['portfolioImageDir']['name']);
			$upload_status = false;

			if(preg_match("/^.*(\.[Jj][Pp][Gg]|\.[Ss][Vv][Gg]|\.[Gg][Ii][Ff]|\.[Jj][Pp][Ee][Gg]|\.[Pp][Nn][Gg])$/",$image_name)){
				if ($imageUpload['portfolioImageDir']['size'] < 5242880) {
					if (move_uploaded_file($temp_name,"../../img/porfolio/".$image_name)) {
						$upload_status = true;
					} else {
						$upload_status = false;
					}
				} else {
					$upload_status = false;
				}
			} else {
				$upload_status = false;
			} 

			return $upload_status;
		}
		public function addItem($itemData,$imageUpload){ 
			if ($this->imageUpload($imageUpload)) {
				echo "File Uploaded";
			}
			// $dbMZ = $this->connect();
			// $itemData['portfolioItemPostDate'] = $dbMZ->now();
			// if ($dbMZ->insert ("portfolio_items",$itemData)) {
			// 	$this->status['status'] = array("success" => 1);
			// } else {
			// 	$this->status['status'] = array("failed" => $dbMZ->getLastError());
			// }

		}		
		// public function editItem($itemData,$imageUpload){ 
		// 	$dbMZ = $this->connect();
		// 	$dbMZ->update("portfolio_items");
		// }		
		// public function deleteItem($itemData){ 
		// 	$dbMZ = $this->connect();
		// 	$dbMZ->rawQuery("DELETE FROM portfolio_items WHERE id = ?");
		// }
		public function __destruct(){
			return $this->status;
		}
	}
?>

