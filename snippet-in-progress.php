
<?php

//For set global filters

class BP_Custom_Activity_Filter {
    function __construct() {
        add_filter( 'bp_activity_get', array( $this, 'filter_activities_by_user_role' ), 10, 2 );
    }


    function filter_activities_by_user_role( $activities, $args ) {
        if ( ! is_array( $activities['activities'] ) ) {
            return $activities;
        }

        $filtered_activities = array();
        foreach ( $activities['activities'] as $activity ) {
            $user_id = $activity->user_id;
            $user = get_userdata( $user_id );
            if ( in_array( 'administrator', (array) $user->roles ) ) {
                $filtered_activities[] = $activity;
            }
        }

        $activities['activities'] = $filtered_activities;
        $activities['total'] = count( $filtered_activities );

        return $activities;
    }
}

new BP_Custom_Activity_Filter();

?>

