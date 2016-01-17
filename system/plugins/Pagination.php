<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Pagination Plugin
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

class Pagination
{
	public $config = [];
	public $base_url;
	public $per_page;
	public $total_rows;
	public $uri_segment;
	public $total_pages;
	public $current_page;

	public $main_tag_open	= '<div><ul style="list-style:none;">';
	public $main_tag_close	= '</ul></div>';
	public $first_link		= 'İlk';
	public $last_link		= 'Son';
	public $first_tag_open	= '<li style="display:inline;padding:3px 7px;margin:0 5px;border:1px solid #bc5858;">';
	public $first_tag_close	= '</li>';
	public $prev_link 		= 'Önceki';
	public $prev_tag_open	= '<li style="display:inline;padding:3px 7px;margin:0 5px;border:1px solid #bc5858;">';
	public $prev_tag_close 	= '</li>';
	public $next_link 		= 'Sonraki';
	public $next_tag_open	= '<li style="display:inline;padding:3px 7px;margin:0 5px;border:1px solid #bc5858;">';
	public $next_tag_close 	= '</li>';
	public $last_tag_open	= '<li style="display:inline;padding:3px 7px;margin:0 5px;border:1px solid #bc5858;">';
	public $last_tag_close	= '</li>';
	public $cur_tag_open	= '<li style="display:inline;padding:3px 7px;margin:0 5px;border:1px solid #bc5858;background-color:#bc5858;color:#fff;">';
	public $cur_tag_close	= '</li>';
	public $num_tag_open	= '<li style="display:inline;padding:3px 7px;margin:0 5px;border:1px solid #bc5858;">';
	public $num_tag_close	= '</li>';


	public function init($config)
	{
		// Config
		$this->config 		= $config;

		try {
			// Set base pagination URL
			if(array_key_exists('base_url', $this->config))
				$this->base_url 		= $config['base_url'];
			else
				throw new Exception('Sayfalama yapılacak URL adresini belirtiniz. {base_url}');

			// Set per page
			if(array_key_exists('per_page', $this->config))
				$this->per_page 		= $this->config['per_page'];
			else
				throw new Exception('Her bir sayfada gösterilecek kayıt sayısını belirtiniz. {per_page}');

			// Set total record count
			if(array_key_exists('total_rows', $this->config))
				$this->total_rows 		= $this->config['total_rows'];
			else
				throw new Exception('Sayfalama yapılacak toplam kayıt sayısını belirtiniz. {total_rows}');

			// Set uri segment of page number
			if(array_key_exists('uri_segment', $this->config))
				$this->uri_segment 		= $this->config['uri_segment'];
			else
				throw new Exception('Sayfa numarasının bulunduğu URL segmentini belirtiniz. {uri_segment}');

			if(array_key_exists('per_page', $this->config) && array_key_exists('total_rows', $this->config)) {
				// Toplam sayfa sayısı
				if($this->total_rows % $this->per_page != 0) {
					$this->total_pages 	= ceil($this->total_rows / $this->per_page);
				} else {
					$this->total_pages 	= $this->total_rows / $this->per_page;
				}
			} else {
				throw new Exception('Her bir sayfada gösterilecek kayıt sayısı ve toplam kayıt sayısını belirtiniz.');
			}
		} catch(Exception $e) {
			echo $e->getMessage();
		}

		// Pagination number main html open tags
		if(array_key_exists('main_tag_open', $this->config))
			$this->main_tag_open 	= $this->config['main_tag_open'];

		// Pagination number main html close tag
		if(array_key_exists('main_tag_close', $this->config))
			$this->main_tag_close 	= $this->config['main_tag_close'];

		// First page link in pagination numbers
		if(array_key_exists('first_link', $this->config))
			$this->first_link 		= $this->config['first_link'];

		// Last page link in pagination numbers
		if(array_key_exists('last_link', $this->config))
			$this->last_link 		= $this->config['last_link'];

		// First page link html open tag
		if(array_key_exists('first_tag_open', $this->config))
			$this->first_tag_open 	= $this->config['first_tag_open'];

		// First page link html close tag
		if(array_key_exists('first_tag_close', $this->config))
			$this->first_tag_close 	= $this->config['first_tag_close'];

		// String for previous page link
		if(array_key_exists('prev_link', $this->config))
			$this->prev_link 		= $this->config['prev_link'];

		// Previous page link html open tag
		if(array_key_exists('prev_tag_open', $this->config))
			$this->prev_tag_open 	= $this->config['prev_tag_open'];

		// Previous page link html close tag
		if(array_key_exists('prev_tag_close', $this->config))
			$this->prev_tag_close 	= $this->config['prev_tag_close'];

		// String for next page link
		if(array_key_exists('next_link', $this->config))
			$this->next_link 		= $this->config['next_link'];

		// Next page link html open tag
		if(array_key_exists('next_tag_open', $this->config))
			$this->next_tag_open 	= $this->config['next_tag_open'];

		// Next page link html close tag
		if(array_key_exists('next_tag_close', $this->config))
			$this->next_tag_close 	= $this->config['next_tag_close'];

		// Last page link html open tag
		if(array_key_exists('last_tag_open', $this->config))
			$this->last_tag_open 	= $this->config['last_tag_open'];

		// Last page link html close tag
		if(array_key_exists('last_tag_close', $this->config))
			$this->last_tag_close 	= $this->config['last_tag_close'];

		// Current page link html open tag
		if(array_key_exists('cur_tag_open', $this->config))
			$this->cur_tag_open 	= $this->config['cur_tag_open'];

		// Current page link html close tag
		if(array_key_exists('cur_tag_close', $this->config))
			$this->cur_tag_close 	= $this->config['cur_tag_close'];

		// Page links html open tag
		if(array_key_exists('num_tag_open', $this->config))
			$this->num_tag_open 	= $this->config['num_tag_open'];

		// Page links html close tag
		if(array_key_exists('num_tag_close', $this->config))
			$this->num_tag_close 	= $this->config['num_tag_close'];
	}

	public function get_links()
	{
		// Set Current Page
		if(is_numeric(get_segments($this->uri_segment)))
			$this->current_page = get_segments($this->uri_segment);
		else
			$this->current_page = 1;

		// Page numbers
		if($this->total_pages > 1) {

			$links = $this->main_tag_open;

			if($this->current_page > 1) {
				// First Page Link
				if($this->first_link !== false)
					$links .= $this->first_tag_open . '<a href="' . $this->base_url . '/1">' . $this->first_link . '</a>' . $this->first_tag_close;

				// Previous Page Link
				$links .= $this->prev_tag_open . '<a href="' . $this->base_url . '/' . ($this->current_page - 1) . '">' . $this->prev_link . '</a>' . $this->prev_tag_close;
			}

			if($this->total_pages > 4 && $this->current_page > 4) {
				for($i = $this->current_page - 3; $i < $this->current_page; $i++) {
					$links .= $this->num_tag_open . '<a href="' . $this->base_url . '/' . $i . '">' . $i . '</a>' . $this->num_tag_close;
				}

				$links .= $this->cur_tag_open . $i . $this->cur_tag_close;

				if($this->current_page != $this->total_pages) {
					if($this->current_page + 4 > $this->total_pages) $last_page = $this->current_page + ($this->total_pages-$this->current_page); else $last_page = $this->current_page + 3;
					for($i = $this->current_page + 1; $i <= $last_page; $i++) {
						$links .= $this->num_tag_open . '<a href="' . $this->base_url . '/' . $i . '">' . $i . '</a>' . $this->num_tag_close;
					}
				}
			} elseif($this->total_pages > 4 && $this->current_page < 5) {
				if($this->current_page + 4 > $this->total_pages) $last_page = $this->current_page + ($this->total_pages-$this->current_page); else $last_page = $this->current_page + 3;
				for($i = 1; $i <= $last_page; $i++) {
					if($i == $this->current_page)
						$links .= $this->cur_tag_open . $i . $this->cur_tag_close;
					else
						$links .= $this->num_tag_open . '<a href="' . $this->base_url . '/' . $i . '">' . $i . '</a>' . $this->num_tag_close;
				}
			} else {
				for($i = 1; $i <= $this->total_pages; $i++)
				{
					if($i == $this->current_page)
						$links .= $this->cur_tag_open . $i . $this->cur_tag_close;
					else
						$links .= $this->num_tag_open . '<a href="' . $this->base_url . '/' . $i . '">' . $i . '</a>' . $this->num_tag_close;
				}
			}

			if($this->current_page < $this->total_pages) {
				// Next Page Link
				$links .= $this->next_tag_open . '<a href="' . $this->base_url . '/' . ($this->current_page + 1) . '">' . $this->next_link . '</a>' . $this->next_tag_close;

				// Last Page Link
				if($this->last_link !== false)
					$links .= $this->last_tag_open . '<a href="' . $this->base_url . '/' . $this->total_pages . '">' . $this->last_link . '</a>' . $this->last_tag_close;
			}

			$links .= $this->main_tag_close;

			return $links;

		}		
	}

}

?>