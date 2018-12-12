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
        
        $locale = (env('WIKIPEDIA_LOCALE') ? env('WIKIPEDIA_LOCALE') : 'en' );
        
        $wikipedia_url = 'https://'. $locale .'.wikipedia.org/w/api.php?format=json&action=query&generator=random&grnnamespace=0&prop=revisions|images&rvprop=content&grnlimit=1';
        
        $article = json_decode(file_get_contents($wikipedia_url));
       
        
        $properties = $article->query->pages;
        foreach ($properties as $property){
           $article = $property;
            
        }
        
        $title = $article->title;
        $title = preg_replace('/[^(\w\s)]/u','',$title);
        $title = preg_replace('/[\d]/u','',$title);
        $title = preg_replace('/(\(.*\))/u','',$title);
        
        
        $descr_level1 = $article->revisions[0];
        
        foreach ($descr_level1 as $l1){
            $descr_level2 = $l1;
            
        }
        
        
        $cat_string = ($locale == 'en' ? 'Category' : 'Категория');
        preg_match_all('/(\[\['.$cat_string.'.*\]\])+/Uu',$descr_level2,$ar_description);
        
        
        $description = implode('',$ar_description[1]);
       
        $description = str_replace("]][["," ",$description);
        $description = str_replace($cat_string.":","",$description);
        $description = substr($description,2,-2);
        $title = mb_strtolower($title);
        $this->word = $title;
        $description = $this->removeObviousCategories($title,$description);
        $this->description = $description;
        
        return $this;
        
    }
    
    protected function removeObviousCategories($title, $description){
        
        $description = mb_strtolower($description);
        
        $ar_description = preg_split('/\s/u', $description,-1, PREG_SPLIT_NO_EMPTY);
        
        $ar_title = preg_split('/\s/u', $title,-1, PREG_SPLIT_NO_EMPTY);
        
        $ar_result = Array();
        
        foreach ($ar_description as $d_word){
        
            $put = 1;
            foreach ($ar_title as $t_word){
            
            if ((mb_strpos($d_word, $t_word)!==false || mb_strpos($t_word, $d_word)!== false) && mb_strlen($d_word)>3){ $put = 0; }
            
            }
            if ($put==1){$ar_result[] = $d_word;}
        }
        
        $description = implode(' ',$ar_result);
        
        return $description;
        
    }
}
