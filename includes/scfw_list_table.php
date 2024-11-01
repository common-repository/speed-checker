<?php
/*
 * =======================================
 * WEBSITE SPEED LIST TABLE
 * =======================================
 *
 *
*/
//Our class extends the WP_List_Table class, so we need to make sure that it's there
if (!class_exists('WP_List_Table')) {
    require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class SCFW_LIST_TABLE extends WP_List_Table {
    function __construct() {

        parent::__construct( array(
            'singular'  => 'scfw_page_report',
            'plural'    => 'scfw_page_reports',
            'ajax'      => false
        ));
    }
    public function get_columns() {
        return $columns = array('url' => __('Url'), 'type' => __('Type'), 'desktop_score' => __('Score (Desktop)'), 'mobile_score' => __('Score (Mobile)'), 'last_updated' => __('Last Updated'));
    }
    public static function get_score() {
        $options = get_option('scfw_speed_test_options');
        if (isset($options['scfw_report'])) {
            return $options['scfw_report'];
        }
    }
    public function prepare_items() {
        $columns = $this->get_columns();
        $this->_column_headers = array($columns);
        $per_page =  10;
        $this->items = $this->get_score();
        //print_r($this->items);exit;
        if( !empty($this->items )){
            $page = ! empty( $_GET['paged'] ) ? (int) $_GET['paged'] : 1;
            $total_items =  count($this->items);
            $total_pages = ceil( $total_items /10 );
            $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
            $page = min($page, $total_items); //get last page when $_GET['page'] > $totalPages
            $offset = ($page - 1) * $per_page;

            $this->items = array_slice( $this->items, $offset, $per_page );
            $this->set_pagination_args(array('total_items' =>  $total_items, 'per_page' => $per_page,'total_pages' => $total_pages));
           }
    }
    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'url':
                return $item[$column_name];
            case 'type':
                return $item[$column_name];
            case 'desktop_score':
                return $this->progress_bar($item[$column_name]);
            case 'mobile_score':
                return $this->progress_bar($item[$column_name]);
            case 'last_updated':
                return $this->last_updated($item[$column_name]);
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
                
        }
    }
    public function last_updated($value) {
        return date("Y-m-d h:i:s", $value);
    }

    public function column_mobile_score( $item ) {
        if ( empty( $item['mobile_score'] ) ) {
             return esc_html__( 'Loading...', 'scfw' );
        }
         return $this->progress_bar($item['mobile_score']);
    }
    public function column_desktop_score( $item ) {
        if ( empty( $item['desktop_score'] ) ) {
             return esc_html__( 'Loading...', 'scfw' );
        }
         return $this->progress_bar($item['desktop_score']);
    }
   
    public function column_url($item) {
        $actions = array('View Url' => sprintf('<a href="%s" target="_blank">%s</a>', esc_url($item['url']), esc_html__('View URL', 'gpagespeedi')));
        return sprintf('%1$s %2$s', $item['url'], $this->row_actions($actions));
    }
    public function no_items() { ?>
        <h3>Pagespeed Reports were not found.</h3>
      <ol>
            <li><?php esc_html_e('Make sure your Google API key is typed on the Options page.', 'scfw') ?></li>
            <li><?php esc_html_e('Make sure "PageSpeed Insights API" is enabled on the Google Console Services tab', 'scfw') ?><a href='https://console.cloud.google.com/apis/library/pagespeedonline.googleapis.com?project=carbide-program-148818'>
                <?php esc_html_e('here','scfw');?></a></li>
            <li><?php esc_html_e('Make sure that your URLs are publicly accessible.', 'scfw') ?></li>
      </ol>
    <?php
    }
    public function progress_bar($score) { ?>
        <div class="wrapper">
            <div class="progress-bar">
                <div class="bar" data-size="<?php echo esc_attr( $score ); ?>">
                    <span class="perc"></span>
                </div>
            </div>    
       </div>
    <?php
    }
}
$scfw_list_table = new SCFW_LIST_TABLE();
$scfw_list_table->prepare_items();

$scfw_list_table->display();
?>