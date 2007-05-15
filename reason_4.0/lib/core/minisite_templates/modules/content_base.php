<?php

	reason_include_once( 'minisite_templates/modules/default.php' );

	$GLOBALS[ '_module_class_names' ][ basename( __FILE__, '.php' ) ] = 'ContentModule';

	class ContentModule extends DefaultMinisiteModule
	{
		var $content;
		
		function init( $args = array() )
		{
			$this->content = $this->cur_page->get_value( 'content' );
		}
		function has_content()
		{
			if( empty( $this->content ) )
				return false;
			elseif(strlen($this->content) < 256)
			{
				$trimmed = trim(strip_tags($this->content,'<img><hr><script><embed><object><form>'));
				if(empty($trimmed))
					return false;
			}
			return true;
		}
		function run()
		{
			$this->process();
			echo '<div id="pageContent">';
			echo $this->content;
			echo '</div>';
		}
		function process()
		{
			if (!empty($this->textonly))
				$this->textonly_process();
		}
		function textonly_process()
		{
			if(strstr($this->content, '<img'))
			{
				// Transform images with alt attributes
				$this->content = preg_replace('-<img\s[^>]*alt=(\'|")(.*)\1[^>]*>-isuU', '[$2]', $this->content);
				
				// Transform images without alt attributes, but with title attributes
				$this->content = preg_replace('-<img\s[^>]*title=(\'|")(.*)\1[^>]*>-isuU', '[$2]', $this->content);
				
				// Transform images with neither alt nor title attributes
				$this->content = preg_replace('-<img\s[^>]*>-isuU', '[IMAGE]', $this->content);
			}
		}
		
		function get_documentation()
		{
			return '<p>Displays the current page\'s textual content</p>';
		}
	}
?>
