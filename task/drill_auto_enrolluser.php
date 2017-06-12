<?php

namespace mod_certificate\task;
require_once('pushnotifications.php');


class drill_auto_enrolluser extends \core\task\scheduled_task{
    
    /**
     
     * Get a descriptive name for this task (shown to admins).
     
     *
     
     * @return string
     
     */
    
    public function get_name() {
        
        return get_string('autoenrollmentlearningplan', 'mod_certificate');
        
    }
    
    /**
     
     * Run forum cron.
     
     */
    public function execute() {
        
        global $DB;
        $name="";
        $description="";
        $descriptionformat ="";
        $userid="";
        $templateid="";
        $timecreated="";
        $timemodified=0;
        $origtemplateid=NULL;
        $status=0;
        $duedate=0;
        $reviewerid=NULL;

			 $DATA = $DB->get_recordset_sql ("Select name,description,descriptionformat,userid,templateid from vw_new_enrolled_user_lp");
			 
				foreach ($DATA as $id => $rec) {
   				   
       			   	 $record = new \stdClass();      			   	       				 
                                 $record ->name = $rec->name;
                                 $record ->description= $rec->description;
                                 $record ->descriptionformat=$rec->descriptionformat;
                                 $record ->userid= $rec->userid;    
                                 $record->origtemplateid=$origtemplateid;
                                 $record ->templateid=$rec->templateid;
            			         $record->status= $status;
            			         $record->duedate= $duedate;
            			         $record->reviewerid= $reviewerid;
            			         $record->timecreated= time();
            			         $record->timemodified = $timemodified;
            			         $record->usermodified = $userid;
			                                 			              
                 //   try {
                          $DB->insert_record('competency_plan', $record);
                //    } catch (\MoodleDatabaseException $err) {
               //         myErrorLog($err->getMessage());
                //    }                         			              
			                                 			                                 
			      

          }
    
    }
    
}
