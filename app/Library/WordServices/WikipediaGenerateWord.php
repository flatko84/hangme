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
        
        $locale = (env('WIKIPEDIA_LOCALE')=='bg' ? 'bg' : 'en' );
        
        $wikipedia_url = 'https://'. $locale .'.wikipedia.org/w/api.php?format=json&action=query&generator=random&grnnamespace=0&prop=revisions|images&rvprop=content&grnlimit=1';
        
        $article = json_decode(file_get_contents($wikipedia_url));
       
        
        $properties = $article->query->pages;
        foreach ($properties as $property){
           $article = $property;
            
        }
        
        $title = $article->title;
        $title = preg_replace('/[^(\w\s)]/u','',$title);
        
        $descr_level1 = $article->revisions[0];
        
        foreach ($descr_level1 as $l1){
            $descr_level2 = $l1;
            
        }
        
        
        $cat_string = ($locale == 'en' ? 'Category' : 'Категория');
        preg_match_all('/(\[\['.$cat_string.'.*\]\])+/Uu',$descr_level2,$ar_description);
        
        $description = implode('',$ar_description[1]);
        $description = str_replace("]][[",", ",$description);
        $description = substr($description,2,-2);
        
        $this->word = mb_strtolower($title);
        $this->description = $description;
        $this->keyboard = ( $locale == 'en' ? 'latin' : 'cyr');
        //$this->descr = $descr_level2;
        
        return $this;
        
    }
}
