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
    
    //public $descr;
    
    public function getWord(){
        
        $wikipedia_url = 'https://en.wikipedia.org/w/api.php?format=json&action=query&generator=random&grnnamespace=0&prop=revisions|images&rvprop=content&grnlimit=1';
        
        $article = json_decode(file_get_contents($wikipedia_url));
       
        
        $properties = $article->query->pages;
        foreach ($properties as $property){
           $article = $property;
            
        }
        
        $title = $article->title;
        $title = preg_replace('/[^a-zA-Z_\s]/u','',$title);
        
        $descr_level1 = $article->revisions[0];
        
        foreach ($descr_level1 as $l1){
            $descr_level2 = $l1;
            
        }
        
        preg_match_all('/(\[\[Category.*\]\])+/Uu',$descr_level2,$ar_description);
        
        $description = implode('',$ar_description[1]);
        $description = str_replace("]][[",", ",$description);
        $description = substr($description,2,-2);
        
        $this->word = strtolower($title);
        $this->description = $description;
        $this->keyboard = 'latin';
        //$this->descr = $descr_level2;
        
        return $this;
        
    }
}
