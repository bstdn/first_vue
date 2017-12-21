<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Pagination Class
 *
 * @package Transformers
 * @subpackage Libraries
 * @category Pagination
 * @author bstdn
 */
class MY_Pagination extends CI_Pagination {

    protected $current_page = 0;

    /**
     * Query string segment
     *
     * @var string
     */
    protected $current_page_var = 'page';

    /**
     * Max Total Number of items
     *
     * @var int
     */
    protected $max_total_rows = 0;

    /**
     * Use page numbers flag
     *
     * Whether to use actual page numbers instead of an offset
     *
     * @var    bool
     */
    protected $use_page_numbers = true;

    /**
     * Constructor
     *
     * @param array $params Initialization parameters
     */
    public function __construct($params = array()) {
        parent::__construct($params);
        $this->CI =& get_instance();
        log_message('debug', 'MY_Pagination Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Get the pagination links
     *
     * @return array|string
     */
    public function get_links() {
        // If our item count or per-page total is zero there is no need to continue.
        if($this->total_rows == 0 OR $this->per_page == 0) {
            return '';
        }

        $this->_set_max_total();

        // Calculate the total number of pages
        $num_pages = (int)ceil($this->total_rows / $this->per_page);

        // Is there only one page? Hm... nothing more to do here then.
        if($num_pages === 1) {
            return '';
        }

        // Check the user defined number of links.
        $this->num_links = (int)$this->num_links;

        if($this->num_links < 0) {
            show_error('Your number of links must be a non-negative number.');
        }

        // Determine the current page number.
        $base_page = $this->use_page_numbers ? 1 : 0;

        $cp = $this->CI->input->get_post($this->current_page_var);
        $cp = $cp === false || !ctype_digit($cp) ? 0 : $cp;

        $this->cur_page = empty($this->current_page) ? $cp : $this->current_page;

        // If something isn't quite right, back to the default base page.
        if(!ctype_digit($this->cur_page) OR ($this->use_page_numbers && (int)$this->cur_page === 0)) {
            $this->cur_page = $base_page;
        } else {
            // Make sure we're using integers for comparisons later.
            $this->cur_page = (int)$this->cur_page;
        }

        // Is the page number beyond the result range?
        // If so, we show the last page.
        if($this->use_page_numbers) {
            if($this->cur_page > $num_pages) {
                $this->cur_page = $num_pages;
            }
        } elseif($this->cur_page > $this->total_rows) {
            $this->cur_page = ($num_pages - 1) * $this->per_page;
        }

        $uri_page_number = $this->cur_page;

        // If we're using offset instead of page numbers, convert it
        // to a page number, so we can generate the surrounding number links.
        if(!$this->use_page_numbers) {
            $this->cur_page = (int)floor(($this->cur_page / $this->per_page) + 1);
        }

        // Calculate the start and end numbers. These determine
        // which number to start and end the digit links with.
        $start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
        $end = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

        // And here we go...
        $output = array();

        // Render the "First" link.
        if($this->cur_page > ($this->num_links + 1 + !$this->num_links)) {
            $output['first'] = 0;
        }

        // Render the "Previous" link.
        if($this->cur_page !== 1) {
            $i = ($this->use_page_numbers) ? $uri_page_number - 1 : $uri_page_number - $this->per_page;
            $output['prev'] = $i;
        }

        $output['pages'] = array();

        // Render the pages
        for($loop = $start - 1; $loop <= $end; $loop++) {
            $i = ($this->use_page_numbers) ? $loop : ($loop * $this->per_page) - $this->per_page;
            if($i >= $base_page) {
                if($this->cur_page == $loop) {
                    $output['pages'][] = array('number' => '', 'text' => $loop);
                } else {
                    $n = ($i == $base_page) ? $base_page : $i;
                    $output['pages'][] = array('number' => $n, 'text' => $loop);
                }
            }
        }

        // Render the "next" link
        if($this->cur_page < $num_pages) {
            $output['next'] = $this->use_page_numbers ? $this->cur_page + 1 : $this->cur_page * $this->per_page;
        }

        // Render the "Last" link
        if(($this->cur_page + $this->num_links + !$this->num_links) < $num_pages) {
            $output['last'] = $this->use_page_numbers ? $num_pages : ($num_pages * $this->per_page) - $this->per_page;
        }

        return $output;
    }

    /**
     * Set Max Total Number
     */
    private function _set_max_total() {
        $this->total_rows = $this->max_total_rows > 0 && $this->total_rows > $this->max_total_rows ? $this->max_total_rows : $this->total_rows;
    }
}
