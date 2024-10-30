<?php 
/**
 * Class to output front end i.e favorite info
 */
class BPWF_View_Front 
{
    public function __construct($user_ids, $fav_count)
    {
        $this->show_faces($user_ids, $fav_count);
    }

    // out avatars of users who favorited the activity
    
    public function show_faces($user_ids, $fav_count)
    {
        $totla_fav_count = $fav_count;
        $user_to_show_face = count($user_ids);
        $more_people_string = $fav_count - $user_to_show_face;
        if(!empty($user_ids))
        {
        ?>
        <div class="bwf">
        
        <?php
            foreach($user_ids as $user_id)
            {
                $img_url = bp_core_fetch_avatar ( 
                    array(  'item_id' => $user_id, 
                        'type'    => 'thumb',
                        'html'   => FALSE    
                    )) ;
                echo '<a href="'.bp_core_get_userlink($user_id, '', true).'" ><img src="'.$img_url.'"></a>';
            } 

            $bpwf_fav_more_text = __('more favorited this','bpwf');
            $bpwf_fav_text = __('favorited this','bpwf');

            if( $more_people_string > 0) 
            {
                echo '<span> + '.$more_people_string.' '.$bpwf_fav_more_text.'</span>';
            }
            elseif( $more_people_string == 0 )
            {
                echo '<span>'.$bpwf_fav_text.'</span>'; 
            }
        ?>
        
        </div>
        <?php
        }
    }

}