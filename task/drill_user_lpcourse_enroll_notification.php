<?php

namespace mod_certificate\task;
require_once('pushnotifications.php');

class drill_user_lpcourse_enroll_notification extends \core\task\scheduled_task
{   
   /**
    
    * Get a descriptive name for this task (shown to admins).    
    *     
    * @return string    
    */
    
    public function get_name()
    {
        return get_string('user_lpcourse_enroll_notification', 'mod_certificate');
    }
    
    /**     
     * Run forum cron.    
     */
    public function execute()
    {
        
        global $DB;
        $error_file        = __FILE__;
        $error_functioname = __FUNCTION__;
        $msg_payload       = "";
        
        $subject    = get_string('email_subject_course_expiry', 'mod_certificate', '');
        $emailbody  = get_string('email_body', 'mod_certificate', '');
        $msg_notify = get_string('course_expiry_notification', 'mod_certificate', '');
        
        $records = $DB->get_recordset_sql("SELECT coursename,deviceid,useremail,firstname,notifydate FROM vw_user_course_enrollment_notification");
        
        if (is_null($records) || empty($records)) {
            
            exit;
        } else {
            foreach ($records as $id => $student) {
                try {
                    $data = new \stdClass();
                    
                    $data->coursename = $student->coursename;
                    $data->deviceid   = $student->deviceid;
                    $data->useremail  = $student->useremail;
                    $data->firstname  = $student->firstname;
                    $data->notifydate = $student->notifydate;
                    
                    if (time() == (string)($data->notifydate)) // (string)($data->notifydate)
                        {
                        $eol    = PHP_EOL;
                        $header = "From:" . "baiju@emedsim.com" . $eol;
                        $header .= "MIME-Version: 1.0" . $eol;
                        $header .= "Return-Path:" . "baiju@emedsim.com" . $eol;
                        $header .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
                        
                        $find[]     = '[Course Name]';
                        $replace[]  = (string) ($data->coursename);
                        $find[]     = '[Student First Name]';
                        $replace[]  = (string) ($data->firstname);
                        $email_body = str_replace($find, $replace, $emailbody);
                        
                        if (mail((string) ($data->useremail), $subject, $email_body, $header, '-f' . "baiju@emedsim.com")) {
                            $find[]      = '[Course Name]';
                            $replace[]   = (string) ($data->coursename);
                            $msg_payload = str_replace($find, $replace, $msg_notify);
                            
                            \pushnotifications::iOS($msg_payload, (string) ($data->deviceid));
                            
                        } else {
                            exit;
                        }
                    }
                }
                catch (\Exception $e) {
                    myErrorHandler($error_file, $error_functioname, $e->getMessage());
                }
            }
            
        }
        
    }
}