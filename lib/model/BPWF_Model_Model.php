<?php 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Class to add and delete the favorite action 
 */
class BPWF_Model_Model 
{
    private $db;
    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $this->db->prefix . 'bpwhofav';
    }

    // Add fav enteires in db

    public function add($activity_id, $user_id)
    {
        $this->db->insert( 
	        $this->table, 
	        array( 
		        'time' => current_time( 'mysql' ), 
                'activity_id' => $activity_id,
                'fav_user_id' => $user_id 
	        ) 
        );
    }
    /**
     * Get all user ids who favorited the activity
     */

    public function get_ids($activity_id)
    { 
        $avatar_number = 3;
        $fav_user_ids = $this->db->get_results('SELECT fav_user_id FROM '.$this->table.' WHERE activity_id = '.$activity_id.' ORDER BY time DESC LIMIT 0,'.$avatar_number , ARRAY_N);
	    $user_ids = array();
        foreach ($fav_user_ids as $user_id)
        {
            $user_ids[] = $user_id[0];
        }
        return $user_ids;
    }

    // Get Count of fav ids

    public function get_count($activity_id)
    { 
        $fav_count = $this->db->get_var('SELECT COUNT(*) FROM '.$this->table.' WHERE activity_id = '.$activity_id);
        return $fav_count;   
    }

    // Delete fav enteries when activity is deleted 

    public function delete($activity_id)
    {
        $this->db->delete( $this->table, array( 'activity_id' => $activity_id ), array( '%d' ) );    
    }

    // Resync for older activites

    public function resync_fav($activity_id)
    {
        $query = 'SELECT user_id FROM '.$this->db->base_prefix."usermeta WHERE meta_key = 'bp_favorite_activities' AND (meta_value LIKE '%:$activity_id;%' OR meta_value LIKE '%:\"$activity_id\";%') ";
        $users = $this->db->get_results($query, ARRAY_A);
        if(!empty(array_unique($users)))
        {
            foreach ($users as $user)
            {
                $this->add($activity_id, $user['user_id']);
            }
        }

    }
}