<?php

/**
 * Class to store and send contact details
 * 
 */
namespace Libraries;
use \Libraries\Loader;

class Contact {

    public function __construct(){

         $loader = new Loader();
         $loader->add_action('wp_ajax_nopriv_submitContactForm', $this, 'submitContactForm' );
         $loader->add_action('wp_ajax_submitContactForm', $this, 'submitContactForm' );
         $loader->run();
        
    }

    public function submitContactForm(){
        
        global $wpdb;
        $nonce = $_POST['nextNonce'];
        if (wp_verify_nonce($nonce, 'myajax-next-nonce')){
            $wpdb->insert(
                'wp_contact_submissions',
                array(
                    'fName' => trim($_POST['fName']),
                    'lName' => trim($_POST['lName']),
                    'email' => trim($_POST['email']),
                    'state' => trim($_POST['state']),
                    'submit_date' => date('Y-m-d H:m:s'),
                )
            );
            $status = array( 'action' => 'Contact Submitted', 'status' => 'ok' );
        } else {
            $status = array( 'action' => 'Contact Submitted', 'status' => 'error' );
        }
        wp_send_json($status);
    }


}