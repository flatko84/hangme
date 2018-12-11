<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Library\WordServices;

use App\Library\ServiceGenerateWord;

/**
 * Description of WikipediaGenerateWord
 *
 * @author vdonkov
 */
class WikipediaGenerateWord implements ServiceGenerateWord  {
    
    public $word;
    public $description;
    public $keyboard;
    
    public function getWord(){
        
        $wikipedia_url = 'https://en.wikipedia.org/w/api.php?format=json&action=query&generator=random&grnnamespace=0&prop=revisions|images&rvprop=content&grnlimit=1';
        
        $article = json_decode(file_get_contents($wikipedia_url));
        
        //$this->word = $article->query->title;
        
        $properties = $article->query->pages;
        foreach ($properties as $property){
           $article = $property;
            
        }
        
        $test_descr = $article->revisions[0];
        
        $this->word = strtolower($article->title);
        $this->description = '';
        $this->keyboard = 'latin';
        
        return $this;
        
    }
}
