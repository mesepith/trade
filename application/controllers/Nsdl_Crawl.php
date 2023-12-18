<?php


class Nsdl_Crawl extends MX_Controller {
    
    public function crawlNsdlSector(){

        // Load the simple_html_dom library manually
        require_once APPPATH . 'third_party/Simple_html_dom/simple_html_dom.php';
        
        // Load the simple_html_dom library
        $this->load->library('simple_html_dom');

        // URL of the website
        $url = 'https://www.fpi.nsdl.co.in/web/StaticReports/Fortnightly_Sector_wise_FII_Investment_Data/FIIInvestSector_Nov302023.html';

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL session and get the HTML content
        $html = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
            exit;
        }

        // Close cURL session
        curl_close($ch);

        // Load the HTML content into the simple_html_dom object
        $htmlDom = new simple_html_dom();
        $htmlDom->load($html);
        echo $html;
        // Find the table element
        $table = $htmlDom->find('table', 0);

        if ($table) {
            // Initialize an array to store the crawled data
            $data = [];

            // Loop through each row in the table
            foreach ($table->find('tr') as $row) {
                $rowData = [];

                // Loop through each cell in the row
                foreach ($row->find('td') as $cell) {
                    // Add the cell text to the row data array
                    $rowData[] = $cell->plaintext;
                }

                // Add the row data to the main data array
                $data[] = $rowData;
            }

            // Display the crawled data (you can modify this part based on your requirements)
            foreach ($data as $row) {
                echo implode("\t", $row) . PHP_EOL;
            }
        } else {
            echo "Table not found.\n";
        }

        // Release resources
        $htmlDom->clear();
        
    }
   
}
