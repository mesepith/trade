<?php 
	$nestedStyle = !empty($nestedStyle) ? $nestedStyle : "";
	$this->load->view("doctype-script", $nestedStyle) 
?>
<?php 

    $bodyHeaderData = !empty($bodyHeaderData) ? $bodyHeaderData : "";
    $this->load->view("body-header", $bodyHeaderData); 
?>


<?php 

	$nestedData = !empty($nestedData) ? $nestedData : "";
	$this->load->view("$content", $nestedData) 
?>


<?php

    $this->load->view("body-footer");
    
?>

<?php 

    $nestedScript = !empty($nestedScript) ? $nestedScript : "";
    $this->load->view("body-footer-scripts", $nestedScript) 
?>

