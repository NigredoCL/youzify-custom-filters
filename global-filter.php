
<?php

//For set global filters. It works fine on functions.php

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
            //here you change the filter, you can use xprofile_get_field_data to get custom fields, for exaple show only "administrator" posts
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
-----------------------------------------------------------------------------------------------------------------------
<?php //Working in a "real" filter selectable in dropdown filter menu. This is not finish

class BP_Custom_Activity_Filter {
    function __construct() {
        add_filter( 'bp_get_activity_show_filters_options', array( $this, 'edit_wall_filter' ) );
        add_filter( 'bp_activity_get', array( $this, 'filter_activities_by_city' ), 10, 2 );
    }

    function edit_wall_filter( $filters ) {
        // No this add the new filter on menu
        $filters['city_1'] = __( 'City 1', 'textdomain' );
        return $filters;
    }

    function filter_activities_by_city( $activities, $args ) {
        if ( ! is_array( $activities['activities'] ) ) {
            return $activities;
        }

        if ( isset( $args['filter']['object'] ) && $args['filter']['object'] === 'temuco' ) {
            $filtered_activities = array();
            foreach ( $activities['activities'] as $activity ) {
                $user_id = $activity->user_id;
                // This has to be searched in the wp_bp_xprofile_data table of each installation, the "field_id" must be entered. Ideally a checkbox with predefined options, 
                // so that one can be sure that one is doing correct checks, in my example the desired field id is 16
                $ciudad = xprofile_get_field_data( 16, $user_id );
                
                if ( $ciudad === 'City 1' ) {
                    $filtered_activities[] = $activity;
                }
            }

            $activities['activities'] = $filtered_activities;
            $activities['total'] = count( $filtered_activities );
        }

        return $activities;
    }
}

new BP_Custom_Activity_Filter();



