<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class to extend the request of favorite and unfavorite action
 */

 class BPWF_Controller_Controller
 {
    private $model;

    public function __construct()
     {
         $this->model = new BPWF_Model_Model();
         add_action('bp_activity_add_user_favorite', array($this, 'add_favorite' ), 10, 2);
         add_action('bp_activity_remove_user_favorite', array($this, 'remove_favorite'), 10, 2);
         add_action('bp_activity_entry_content', array($this, 'user_who_favorited'),20); // Low priority
         add_action('bp_activity_delete', array($this, 'delete_favorite'), 10, 1);
         
     }

     // Action when an activity is favorited

     public function add_favorite($activity_id, $user_id)
     {
        $this->model->add($activity_id, $user_id);
     }

     // Action when an activity is unfavorited

     public function remove_favorite($activity_id, $user_id)
     {
        $this->model->delete($activity_id, $user_id);
     }

     // Show users avatar 

     public function user_who_favorited()
     {
        $activity_id =  (int) bp_get_activity_id();
        $fav_count = $this->model->get_count($activity_id);
        if(empty($fav_count))
        {
            // Resync Fav Count 
            $this->model->resync_fav($activity_id);
            $fav_count = $this->model->get_count($activity_id);
        }
        $user_ids = (array) $this->model->get_ids($activity_id);
        new BPWF_View_Front($user_ids, $fav_count); 
     }

     // Action when an activity is deleted

     public function delete_favorite($args)
     {
        $this->model->delete($args['id']);
     }
 }