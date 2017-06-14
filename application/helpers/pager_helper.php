<?php

/**
 * @author Litkovskiy
 * @copyright 2010
 */
defined('BASEPATH') OR exit('No direct script access allowed');

 function prepare_pager_config() {
    return array(
        'uri_segment'       => 3
        ,'per_page'         => 8
        ,'num_links'        => 6
        ,'full_tag_open'    => '<ul class="pagination pull-left mrgt-0">'
        ,'full_tag_close'   => '</ul>'

        ,'first_link'       => false
        ,'first_tag_open'   => '<li>'
        ,'first_tag_close'  => '</li>'

        ,'last_link'        => false
        ,'last_tag_open'    => false
        ,'last_tag_close'   => false
            //next page links
        ,'next_link'        => '&raquo;'
        ,'next_tag_open'    => '<li>'
        ,'next_tag_close'   => '</li>'
            //prev page links
        ,'prev_link'        => '&laquo;'
        ,'prev_tag_open'    => '<li>'
        ,'prev_tag_close'   => '</li>'
            //current page links
        ,'cur_tag_open'     => '<li class="active"><a>'
        ,'cur_tag_close'    => '</a></li>'
            //digits links
        ,'num_tag_open'     => '<li>'
        ,'num_tag_close'    => '</li>'
        ,'use_page_numbers' => true
        ,'first_url'        => base_url()
     );
}
