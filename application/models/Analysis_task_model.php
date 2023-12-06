<?php

/*
 * @author : ZAHIR
 * DESC:Analysis task model
 */

class Analysis_task_model extends CI_Model {
    
     /*
     * @author: ZAHIR
     * DESC: Check option chain analysis with IV is done or not
     */
    
    function checkAnalysisDone($topic){
        
        $this->db->where('status', 1);    
        $this->db->where('topic', $topic);    
        $this->db->where('done_date', date('Y-m-d')); 
        $this->db->limit(1); 
        $this->db->select('count(*) AS ispresent');
        $query = $this->db->get('analysis_task');
        
        if( $query->result()[0]->ispresent > 0 ){
            
            return 'done';
            
        }else{
            
            return 'not_done';
            
        }
    } 
    
     /*
     * @author: ZAHIR
     * DESC: When option chain analysis with IV is done then set flag as done
     */
    function insertOcIvAnalysisDone(){
          
        $data['topic'] = 'oc_iv_analysis';
        $data['done_date'] = date('Y-m-d');
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert('analysis_task', $data);
        
    } 
    
    
    /*
     * @author: ZAHIR
     * DESC: When option chain analysis with premium decay is done then set flag as done
     */
    
    function insertOcPDAnalysisDone(){
          
        $data['topic'] = 'oc_pd_analysis';
        $data['done_date'] = date('Y-m-d');
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert('analysis_task', $data);
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: When option chain analysis with option pain is done then set flag as done
     */
    
    function insertOcOPAnalysisDone(){
          
        $data['topic'] = 'oc_op_analysis';
        $data['done_date'] = date('Y-m-d');
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert('analysis_task', $data);
        
    }
    /*
     * @author: ZAHIR
     * DESC: When option chain analysis of some topic is done then set flag as done
     */
    
    function insertOcAnalysisDone($topic){
          
        $data['topic'] = $topic;
        $data['done_date'] = date('Y-m-d');
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert('analysis_task', $data);
        
    }
    
    
    /*
     * @author: ZAHIR
     * DESC: update company name which is calculated last in option chain 
     */
    function ocCalculationDone($company_id, $company_symbol, $topic){
        
        $this->db->where('topic', $topic);
        $this->db->update('analysis_last_calc', array('company_id' => $company_id, 'company_symbol'=>$company_symbol, 'created_at_date'=>date('Y-m-d'),'updated_at'=> date("Y-m-d H:i:s") ));
        
    }
    
    
    /*
     * @author: ZAHIR
     * DESC: Fetch last company name which is used for calculation
     */
    function lastCalculatedCompany($topic){
        
        $this->db->where('status', 1); 
        $this->db->where('topic', $topic);
        $this->db->where('created_at_date', date('Y-m-d'));
        $this->db->select('company_id, updated_at');
        $query = $this->db->get('analysis_last_calc');
        
        if (count($query->result()) > 0 && !empty( $query->result()[0]->company_id ) && $query->result()[0]->company_id > 0) {
        
            return $query->result()[0];
            
        }else{
            
            return false;
        }
        
        return $query->result();
        
    }
}
    
   
