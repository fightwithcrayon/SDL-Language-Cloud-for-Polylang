<?php

class Polylang_SDL_Unpack_XLIFF {
	private $doc;
	private $xliff_storage_path;
	private $attributes = array();
	private $structure = array();

    public function __construct() {
    	$this->doc = new DOMDocument();
        $files = new Polylang_SDL_Files;
        $this->xliff_storage_path = $files->getFolder('extracted');
    }
    private function verbose($msg) {
    	if($this->verbose === true) {
    		echo '<b>Console: </b>'. $msg .'<br />';
    	}
    }

    public function convert($filename){
    	$this->doc->load($this->xliff_storage_path . $filename);
    	$this->extract_attributes();
    	$this->extract_structure();
    	return $this->structure;
    }
    private function extract_attributes(){
    	$file = $this->doc->getElementsByTagName('file');
    	$this->attributes['source-language'] = $file[0]->getAttribute('source-language');
    	$this->attributes['target-language'] = $file[0]->getAttribute('target-language');
    }
    private function extract_structure(){
    	$units = $this->doc->getElementsByTagName('trans-unit');
    	foreach($units as $unit) {
    		if($unit->getAttribute('resname') == 'title') {
    			$this->structure['title'] = $unit->getElementsByTagName('target')[0]->nodeValue;
    		} else if($unit->getAttribute('resname') == 'body') {
    			$this->structure['body'] = $unit->getElementsByTagName('target')[0]->nodeValue;
    		} else if($unit->getAttribute('resname') == 'taxonomy') {
    			$type = $unit->getAttribute('wp_taxonomy');
                $id = $unit->getAttribute('wp_id');
    			$this->structure['taxonomy'][$type][$id] = $unit->getElementsByTagName('target')[0]->nodeValue;
    		} else if($unit->getAttribute('resname') == 'meta') {
                $this->structure['meta'][$unit->getAttribute('wp_meta')] = $unit->getElementsByTagName('target')[0]->nodeValue;
            }
    	}
    }
}

?>